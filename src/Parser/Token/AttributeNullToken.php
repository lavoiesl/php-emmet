<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class AttributeNullToken extends AttributeToken
{
    const DEFAULT_EMPTY = 'DEFAULT_EMPTY_ATTRIBUTE';

    public function __construct($name, array $lexerTokens)
    {
        parent::__construct($name, $lexerTokens);

        $this->attr_value = self::DEFAULT_EMPTY;
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_CLOSE')),
        );
    }

    public function __toString()
    {
        return $this->attr_name;
    }
}
