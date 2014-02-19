<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class IdToken extends AttributeToken
{
    public function __construct(array $tokens)
    {
        parent::__construct($tokens);

        $this->name = 'id';
        $this->value = $tokens[1]->value;
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_ID', 'T_ATOM')),
        );
    }
}
