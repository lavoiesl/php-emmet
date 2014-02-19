<?php

namespace Lavoiesl\Emmet\Parser\Exception;

class LexerException extends Exception
{
    public function __construct($offset, $line)
    {
        $excerpt = substr($line, $offset, 20);

        parent::__construct("Unknown parser error at offset $offset: '$excerpt'");
    }
}
