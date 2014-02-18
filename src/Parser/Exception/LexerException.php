<?php

namespace Lavoiesl\Emmet\Parser\Exception;

class LexerException extends Exception
{
    public function __construct($line_number, $offset, $line)
    {
        $excerpt = substr($line, $offset, 20);
        $line_msg = $line_number === 0 ? '' : "line " . $line_number . ', ';

        parent::__construct("Unknown parser error at {$line_msg}offset $offset: '$excerpt'");
    }
}
