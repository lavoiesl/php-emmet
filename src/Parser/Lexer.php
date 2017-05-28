<?php

namespace Lavoiesl\Emmet\Parser;

use Lavoiesl\Emmet\Parser\Exception\LexerException;
use Lavoiesl\Emmet\Parser\Token\LexerToken;

class Lexer
{
    /**
     * Cases where a single character identifies the token with certainty
     */
    protected $singles = array(
        '#' => 'T_ID',
        '.' => 'T_CLASS',
        ':' => 'T_PSEUDO',
        '[' => 'T_ATTR_OPEN',
        ']' => 'T_ATTR_CLOSE',
        '=' => 'T_ATTR_SEPARATOR',
        '>' => 'T_CHILD',
        '+' => 'T_SIBLING',
    );

    /**
     * Delimiters for strings
     */
    protected $strings = array(
        "'" => array("'", '\\', 'T_STRING'),
        '"' => array('"', '\\', 'T_STRING'),
        '{' => array('}', '\\', 'T_TEXT'),
    );

    /**
     * Tokens that only match if at the end of the input.
     * WARNING: If you add something that doesn't start with an underscore,
     *          you must remove the optimization below.
     */
    protected $ends = array(
        '_'  => 'T_PARENT',
        '__' => 'T_DOCUMENT',
    );

    protected $complexes = array(
        '/\G([a-z](?:[a-z0-9\-_]*[a-z0-9])?)/i' => 'T_ATOM',
    );

    public function parse($input)
    {
        if (!is_string($input)) {
            throw new \InvalidArgumentException("Input must be a string");
        }

        $offset = 0;
        $length = strlen($input);
        $tokens = [];

        while ($offset < $length) {
            if ($token = $this->match($input, $length, $offset)) {
                $tokens[] = $token;
            } else {
                throw new LexerException($offset, $input);
            }
        }

        return $tokens;
    }

    protected function match($input, $length, &$offset = 0)
    {
        $first = $input[$offset];

        if ($first === '_') { // Optimization
            foreach ($this->ends as $value => $name) {
                if (substr_compare($input, $value, $offset) === 0) {
                    $t = new LexerToken($name, $value, $offset);
                    $offset += strlen($value);
                    return $t;
                }
            }
        }

        if (array_key_exists($first, $this->singles)) {
            $offset++;
            return new LexerToken(
                $this->singles[$first],
                '',
                $offset - 1
            );
        }

        if (array_key_exists($first, $this->strings)) {
            [$end, $escape, $name] = $this->strings[$first];
            if ($stringToken = $this->matchString($input, $end, $escape, $name, $offset, $length)) {
                return $stringToken;
            }
        }

        foreach ($this->complexes as $pattern => $name) {
            if (preg_match($pattern, $input, $matches, 0, $offset)) {
                $t = new LexerToken(
                    $name,
                    $matches[1],
                    $offset
                );
                $offset += strlen($matches[0]);
                return $t;
            }
        }

        return false;
    }

    public static function matchString($input, $end, $escape, $name, &$offset, $length)
    {
        $delim = $escape . $end;
        $pos = $offset + 1;
        $escaped = false;

        while ($pos < $length) {
            if ($escaped) {
                $escaped = false;
                $pos++;
                continue;
            }

            switch ($input[$pos]) {
                case $end:
                    $t = new LexerToken(
                        $name,
                        substr($input, $offset + 1, $pos - $offset - 1),
                        $offset
                    );
                    $offset = $pos + 1;
                    return $t;

                case $escape:
                    $escaped = true;
                    $pos++;
                    break;

                default:
                    $pos += strcspn($input, $delim, $pos);
                    break;
            }
        }
    }
}
