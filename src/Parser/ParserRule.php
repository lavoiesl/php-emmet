<?php

namespace Lavoiesl\Emmet\Parser;

class ParserRule
{
    public $tokenName = null;

    public $stateIn = null;

    public $tokens = array();

    public $stateOut = null;

    public $validator = null;

    public function __construct($tokenClass, $stateIn, array $tokens, $stateOut = null)
    {
        $this->tokenClass = $tokenClass;
        $this->stateIn    = $stateIn;
        $this->tokens     = $tokens;
        $this->stateOut   = $stateOut === null ? $stateIn : $stateOut;
    }

    public function validate(array $tokens)
    {
        $validator = $this->validator;
        return $validator === null ? true : $validator($tokens);
    }

    public function createToken(array $tokens)
    {
        $class = $this->tokenClass;

        return new $class($tokens);
    }
}
