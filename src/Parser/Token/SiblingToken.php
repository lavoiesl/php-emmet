<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class SiblingToken extends ElementToken
{
    public function __construct($name, array $lexerTokens)
    {
        if (null === $this->element_name) {
            $this->element_name = $lexerTokens[1]->value;
        }

        parent::__construct($name, $lexerTokens);
    }

    public function process(\DOMNode $context)
    {
        $parent = null === $context->parentNode ? $context : $context->parentNode;

        return $parent->append($this->element_name);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_SIBLING', 'T_ATOM')),
        );
    }
}
