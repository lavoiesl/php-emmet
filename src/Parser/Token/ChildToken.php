<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class ChildToken extends ElementToken
{
    public function __construct($name, array $lexerTokens)
    {
        if (null === $this->element_name) {
            $this->element_name = $lexerTokens[1]->value;
        }

        parent::__construct($name, $lexerTokens);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_CHILD', 'T_ATOM')),
        );
    }
}
