<?php

namespace Lavoiesl\Emmet\Parser\Token;

class LexerToken
{
    public $name;

    public $value;

    public $matched;

    public $offset;

    public $length;

    public function __construct($name, $value, $matched, $offset, $length)
    {
        $this->name    = $name;
        $this->value   = $value;
        $this->matched = $matched;
        $this->offset  = $offset;
        $this->length  = $length;
    }

    public function __toString()
    {
        return $this->name . ' ('.$this->matched.')';
    }
}
