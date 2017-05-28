<?php

namespace Lavoiesl\Emmet\Parser\Token;

class LexerToken
{
    public $name;

    public $value;

    public $offset;

    public function __construct($name, $value, $offset)
    {
        $this->name    = $name;
        $this->value   = $value;
        $this->offset  = $offset;
    }

    public function __toString()
    {
        return $this->name;
    }
}
