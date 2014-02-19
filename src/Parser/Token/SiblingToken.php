<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class SiblingToken extends ElementToken
{
    public function __construct(array $tokens)
    {
        parent::__construct($tokens);

        $this->name = $tokens[1]->value;
    }

    public function process(\DOMNode $context)
    {
        $parent = null === $context->parentNode ? $context : $context->parentNode;

        return $parent->append($this->name);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_SIBLING', 'T_ATOM')),
        );
    }
}
