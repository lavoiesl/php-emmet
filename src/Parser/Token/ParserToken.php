<?php

namespace Lavoiesl\Emmet\Parser\Token;

abstract class ParserToken
{
    protected $name;

    protected $lexerTokens;

    public function __construct($name, array $lexerTokens)
    {
        $this->name = $name;

        $this->lexerTokens = $lexerTokens;
    }

    public function getTokens()
    {
        return $this->lexerTokens;
    }

    abstract public function process(\DOMNode $context);

    abstract public function __toString();

    public static function getParserRules()
    {
        return array();
    }
}