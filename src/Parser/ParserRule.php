<?php

namespace Lavoiesl\Emmet\Parser;

class ParserRule
{
    public $tokenClass = null;

    public $stateIn = null;

    public $tokens = array();

    public $stateOut = null;

    public $validator = null;

    public function __construct($tokenClass, $stateIn, array $tokens, $stateOut = null)
    {
        if (empty($tokens)) {
            throw new IllegalStateException("ParserRule ${tokenClass} contains no tokens");
        }
        $this->tokenClass = $tokenClass;
        $this->stateIn    = $stateIn;
        $this->tokens     = $tokens;
        $this->stateOut   = $stateOut === null ? $stateIn : $stateOut;
    }

    public function createToken(array $tokens)
    {
        $class = $this->tokenClass;

        return new $class($tokens);
    }
}
