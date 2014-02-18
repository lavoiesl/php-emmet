<?php

namespace Lavoiesl\Emmet\Parser\Token;

class LexerToken
{
    public $name;

    public $value;

    public $matched;

    public $line;

    public $offset;

    public function __construct($name, $value, $matched = null, $line = null, $offset = null)
    {
        $this->name    = $name;
        $this->value   = $value;
        $this->matched = $matched;
        $this->line    = $line;
        $this->offset  = $offset;
    }

    public function __toString()
    {
        return $this->name . ' ('.$this->matched.')';
    }
}