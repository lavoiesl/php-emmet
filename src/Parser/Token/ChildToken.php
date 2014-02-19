<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class ChildToken extends ElementToken
{
    public function __construct(array $tokens)
    {
        parent::__construct($tokens);

        $this->name = $tokens[1]->value;
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_CHILD', 'T_ATOM')),
        );
    }
}
