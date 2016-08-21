<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class DocumentToken extends ParentToken
{
    public function process(\DOMNode $context)
    {
        return $context instanceof \DOMDocument ? $context : $context->ownerDocument;
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_DOCUMENT')),
            new ParserRule(__CLASS__, 'atom', array('T_DOCUMENT'), null),
        );
    }
}
