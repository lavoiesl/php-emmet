<?php

namespace Lavoiesl\Emmet\Parser;

use Lavoiesl\Emmet\Parser\Token\ParserToken;

class ParserRule
{
    public $tokenName = null;

    public $stateIn = null;

    public $tokens = array();

    public $stateOut = null;

    public $validator = null;

    public function __construct($tokenClass, $stateIn, array $tokens, $stateOut = null)
    {
        $pieces = explode('\\', $tokenClass);
        $tokenName = $pieces[count($pieces) - 1];

        $this->tokenClass = $tokenClass;
        $this->tokenName  = preg_replace('/Token$/', '', $tokenName);
        $this->stateIn    = $stateIn;
        $this->tokens     = $tokens;
        $this->stateOut   = $stateOut === null ? $stateIn : $stateOut;
    }

    public function validate(array $tokens)
    {
        return $this->validator === null ? true : call_user_func($this->validator, $tokens);
    }

    public function createToken(array $tokens)
    {
        $class = $this->tokenClass;
        return new $class($this->tokenName, $tokens);
    }
}
