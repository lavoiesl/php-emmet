<?php

namespace Lavoiesl\Emmet\Parser;

use Lavoiesl\Emmet\Parser\Exception\LexerException;
use Lavoiesl\Emmet\Parser\Token\LexerToken;

class Lexer
{
    protected $firstChars = array(
        '"'  => array('/^"([^"]*)"/' => "T_STRING"),
        "'"  => array("/^'([^']*)'/" => "T_STRING"),
        '_'  => array('/^(_)$/' => "T_PARENT",
                      '/^(__)$/' => "T_DOCUMENT"),
        '{'  => array('/^\{((?:\\\}|[^}])*)\}/' => "T_TEXT"),
    );

    protected $firstCharsRegex = array(
        '/^"([^"]*)"/'            => "T_STRING",
        "/^'([^']*)'/"            => "T_STRING",
        "/^(_)$/"                 => "T_PARENT",
        "/^(__)$/"                => "T_DOCUMENT",
        "/^\{((?:\\\}|[^}])*)\}/" => "T_TEXT",
    );

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
            if ($token = $this->match($input, $offset)) {
                $offset += $token->length;

                $tokens[] = $token;
                $tokenNames[] = $token->name;
            } else {
                throw new LexerException($offset, $input);
            }
        }

        return $tokens;
    }

    protected function match($input, $offset)
    {
        $string = substr($input, $offset);
        $first = $string[0];

        if (isset($this->singles[$first])) {
            return new LexerToken(
                $this->singles[$first],
                '',
                $first,
                $offset,
                1
            );
        }

        if (isset($this->firstChars[$first])) {
            // shortcut when the first caracter is definitive
            foreach ($this->firstChars[$first] as $pattern => $name) {
                if (preg_match($pattern, $string, $matches)) {
                    return new LexerToken(
                        $name,
                        $matches[1],
                        $matches[0],
                        $offset,
                        strlen($matches[0])
                    );
                }
            }

            return false;
        }

        foreach ($this->complexes as $pattern => $name) {
            if (preg_match($pattern, $string, $matches)) {
                return new LexerToken(
                    $name,
                    $matches[1],
                    $matches[0],
                    $offset,
                    strlen($matches[0])
                );
            }
        }

        return false;
    }
}
