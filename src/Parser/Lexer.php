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

    public function parse($lines)
    {
        if (is_string($lines)) {
            $lines = explode("\n", $lines);
        } elseif (!is_array($lines)) {
            throw new \InvalidArgumentException("Input must be a string or an array of lines");
        }

        $tokens = array();

        foreach ($lines as $i => $line) {
            $offset = 0;
            $length = strlen($line);

            while($offset < $length) {
                if ($token = $this->match(substr($line, $offset))) {
                    $token->line   = $i;
                    $token->offset = $offset;
                    $offset += strlen($token->matched);

                    $tokens[] = $token;
                } else {
                    throw new LexerException($i, $offset, $line);
                }
            }
        }

        return $tokens;
    }

    protected function match($string)
    {
        $first = $string[0];
        if (isset($this->singles[$first])) {
            return new LexerToken(
                $this->singles[$first],
                $first,
                $first
            );
        }

        if (isset($this->firstChars[$first])) {
            // shortcut when the first caracter is definitive
            foreach ($this->firstChars[$first] as $pattern => $name) {
                if (preg_match($pattern, $string, $matches)) {
                    return new LexerToken(
                        $name,
                        $matches[1],
                        $matches[0]
                    );
                }
            }

            return false;
        }

        foreach($this->complexes as $pattern => $name) {
            if (preg_match($pattern, $string, $matches)) {
                return new LexerToken(
                    $name,
                    $matches[1],
                    $matches[0]
                );
            }
        }

        return false;
    }
}
