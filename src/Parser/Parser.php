<?php

namespace Lavoiesl\Emmet\Parser;

use Lavoiesl\Emmet\Parser\Exception\ParserException;

class Parser
{
    protected $lexer;

    protected $rules = array();

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

    public function addRule(ParserRule $rule)
    {
        $this->rules[$rule->stateIn][] = $rule;
    }

    public function parse($input)
    {
        $tokens = $this->lexer->parse($input, $tokenNames);

        return $this->parseTokens($tokens, $tokenNames);
    }

    protected function parseTokens($tokens, $tokenNames)
    {
        $parserTokens = array();
        $state = 'default';
        $i = 0;
        $count_tokens = count($tokens);

        while ($i < $count_tokens) {
            $tokenNamesSlice = array();
            for ($j = 1; $i + $j - 1 < $count_tokens; $j++) {
                $tokenNamesSlice[] = $tokenNames[$i+$j-1];

                foreach ($this->rules[$state] as $rule) {
                    if ($rule->tokens == $tokenNamesSlice) {
                        $tokensSlice = array_slice($tokens, $i, $j);

                        if (!$rule->validator || call_user_func($rule->validator, $tokensSlice)) {
                            $parserTokens[] = $rule->createToken($tokensSlice);

                            $state = $rule->stateOut;
                            $i += $j;
                            continue 3;
                        }
                    }
                }
            }

            throw new ParserException($tokens[$i]);
        }

        return $parserTokens;
    }


}
