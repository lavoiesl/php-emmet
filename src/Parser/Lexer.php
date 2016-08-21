<?php

namespace Lavoiesl\Emmet\Parser;

use Lavoiesl\Emmet\Parser\Exception\LexerException;
use Lavoiesl\Emmet\Parser\Token\LexerToken;

class Lexer
{
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

    protected $strings = array(
        "'" => array("'", '\\', 'T_STRING'),
        '"' => array('"', '\\', 'T_STRING'),
        '{' => array('}', '\\', 'T_TEXT'),
    );

    protected $ends = array(
        '_'  => 'T_PARENT',
        '__' => 'T_DOCUMENT',
    );

    protected $complexes = array(
        "/^([a-z]([a-z0-9\-_]*[a-z0-9])?)/i" => "T_ATOM",
    );

    public function parse($input, &$tokenNames = array())
    {
        if (!is_string($input)) {
            throw new \InvalidArgumentException("Input must be a string");
        }

        $offset = 0;
        $length = strlen($input);
        $tokens = array();

        while ($offset < $length) {
            if ($token = $this->match($input, $offset, $length)) {
                $offset += $token->length;

                $tokens[] = $token;
                $tokenNames[] = $token->name;
            } else {
                throw new LexerException($offset, $input);
            }
        }

        return $tokens;
    }

    protected function match($input, $offset, $length)
    {
        $first = $input[$offset];

        if (isset($this->singles[$first])) {
            return new LexerToken(
                $this->singles[$first],
                '',
                $offset,
                1
            );
        }

        if (isset($this->strings[$first])) {
            $end = $this->strings[$first][0];
            $escape = $this->strings[$first][1];
            $name = $this->strings[$first][2];
            if ($stringToken = $this->matchString($input, $end, $escape, $name, $offset, $length)) {
                return $stringToken;
            }
        }

        $string = substr($input, $offset);

        if (isset($this->ends[$string])) {
            return new LexerToken(
                $this->ends[$string],
                $string,
                $offset,
                strlen($string)
            );
        }

        foreach ($this->complexes as $pattern => $name) {
            if (preg_match($pattern, $string, $matches)) {
                return new LexerToken(
                    $name,
                    $matches[1],
                    $offset,
                    strlen($matches[0])
                );
            }
        }

        return false;
    }

    protected function matchString($input, $end, $escape, $name, $offset, $length)
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
                        $offset,
                        $pos - $offset + 1
                    );
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
