<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class ElementToken extends ParserToken
{
    public $name = null;

    public function __construct(array $tokens)
    {
        parent::__construct($tokens);

        $this->name = $tokens[0]->value;
    }

    public function process(\DOMNode $context)
    {
        return $context->append($this->name);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_ATOM')),
            new ParserRule(__CLASS__, 'atom', array('T_ATOM'), null),
        );
    }

    public function __toString()
    {
        return $this->name;
    }
}
