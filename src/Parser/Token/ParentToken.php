<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class ParentToken extends ParserToken
{
    public function process(\DOMNode $context)
    {
        return null === $context->parentNode ? $context : $context->parentNode;
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_PARENT')),
        );
    }

    public function __toString()
    {
        return '';
    }
}
