<?php

namespace Lavoiesl\Emmet\Parser;

use Lavoiesl\Emmet\Parser\Exception\ParserException;

class Parser
{
    protected $lexer;

    protected $rules = array();

    protected $rulesTree = null;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = new $lexer;
        $this->loadDefaultRules();
    }

    protected function loadDefaultRules()
    {
        $baseClass = __NAMESPACE__ . '\\Token\\ParserToken';

        foreach (glob(__DIR__ . '/Token/*Token.php') as $file) {
            $name = basename($file, '.php');
            $class = __NAMESPACE__ . '\\Token\\' . $name;

            if (is_subclass_of($class, $baseClass)) {
                foreach ($class::getParserRules() as $rule) {
                    $this->addRule($rule);
                }
            }
        }
    }

    /**
     * Build an index of all the rules.
     * Each key is a lexer token, which contains the subsequent lexer tokens.
     *
     * For a ParserRule with [lexerToken1, lexerToken2]:
     * {
     *     state => {
     *         lexerToken1 => {
     *             lexerToken2 => {
     *                 _rules: [ParserRule]
     *             }
     *         }
     *     }
     * }
     */
    public function compile()
    {
        foreach ($this->rules as $state => $rules) {
            $this->rulesTree[$state] = [];

            foreach ($rules as $rule) {
                $tree =& $this->rulesTree[$state];

                foreach ($rule->tokens as $token) {
                    if (!array_key_exists($token, $tree)) {
                        $tree[$token] = [];
                    }
                    $tree =& $tree[$token];
                }

                if (!array_key_exists('_rules', $tree)) {
                    $tree['_rules'] = [];
                }
                array_push($tree['_rules'], $rule);
            }
        }
    }

    public function addRule(ParserRule $rule)
    {
        $this->rules[$rule->stateIn][] = $rule;
    }

    public function parse($input, $state = 'default')
    {
        return $this->parseTokens($this->lexer->parse($input), $state);
    }

    protected function parseTokens($tokens, $state = 'default')
    {
        if ($this->rulesTree === null) {
            throw new IllegalStateException("Must call Parser#compile() before using.");
        }

        $parserTokens = array();
        $tokensCount = count($tokens);
        $i = 0;

        while ($i < $tokensCount) {
            // Start of a new token
            $tree = $this->rulesTree[$state];

            for ($j = $i; $j < $tokensCount; $j++) {
                $tokenName = $tokens[$j]->name;

                if (array_key_exists($tokenName, $tree)) {
                    $tree = $tree[$tokenName];

                    if (array_key_exists('_rules', $tree)) {
                        // Potential matches, invoke validators
                        $tokensSlice = array_slice($tokens, $i, $j - $i + 1);

                        foreach ($tree['_rules'] as $rule) {
                            $validator = $rule->validator;
                            if (!$validator || $validator($tokensSlice)) {
                                $parserTokens[] = $rule->createToken($tokensSlice);

                                $state = $rule->stateOut;
                                $i = $j + 1;
                                continue 3;
                            }
                        }
                    }
                    // Else: Continue diving down the tree
                } else {
                    // Unrecognized token.
                    throw new ParserException($tokens[$j]);
                }
            }

            throw new ParserException($tokens[$i]);
        }

        return $parserTokens;
    }

}
