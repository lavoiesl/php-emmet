<?php

namespace Lavoiesl\Emmet\Parser\Token;

abstract class ParserToken
{
    public $offset;

    public function __construct(array $tokens)
    {
        $this->offset = $tokens[0]->offset;
    }

    abstract public function process(\DOMNode $context);

    abstract public function __toString();

    abstract public static function getParserRules();
}
