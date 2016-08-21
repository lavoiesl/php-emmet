<?php

namespace Lavoiesl\Emmet\Parser\Token;

class LexerToken
{
    public $name;

    public $value;

    public $offset;

    public $length;

    public function __construct($name, $value, $offset, $length)
    {
        $this->name    = $name;
        $this->value   = $value;
        $this->offset  = $offset;
        $this->length  = $length;
    }

    public function __toString()
    {
        return $this->name;
    }
}
