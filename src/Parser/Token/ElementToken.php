<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;

class ElementToken extends ParserToken
{
    protected $element_name;

    public function __construct($name, array $lexerTokens)
    {
        if (null === $this->element_name) {
            $this->element_name = $lexerTokens[0]->value;
        }

        parent::__construct($name, $lexerTokens);
    }

    public function process(\DOMNode $context)
    {
        return $context->append($this->element_name);
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
        return $this->element_name;
    }
}
