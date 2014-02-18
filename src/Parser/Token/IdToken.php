<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class IdToken extends AttributeToken
{
    public function __construct($name, array $lexerTokens)
    {
        $this->attr_name = 'id';

        parent::__construct($name, $lexerTokens);

        $this->attr_value = $lexerTokens[1]->value;
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_ID', 'T_ATOM')),
        );
    }
}
