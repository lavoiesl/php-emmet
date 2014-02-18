<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class AttributeEmptyToken extends AttributeToken
{
    public function __construct($name, array $lexerTokens)
    {
        if (null === $this->attr_value) {
            $this->attr_value = '';
        }

        parent::__construct($name, $lexerTokens);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_ATTR_CLOSE')),
        );
    }
}
