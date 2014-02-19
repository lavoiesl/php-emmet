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
        $element = new ParserRule(__CLASS__, 'default', array('T_ATOM'));

        $element->validator = function(array $tokens) {
            return preg_match('/^[a-z][a-z0-9]*$/i', $tokens[0]->value);
        };

        return array(
            $element,
        );
    }

    public function __toString()
    {
        return $this->name;
    }
}
