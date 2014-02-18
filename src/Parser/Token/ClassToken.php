<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;
use Lavoiesl\Emmet\Parser\Exception\TokenUsageException;

class ClassToken extends AttributeToken
{
    public function __construct($name, array $lexerTokens)
    {
        $this->attr_name = 'class';

        parent::__construct($name, $lexerTokens);

        $this->attr_value = $lexerTokens[1]->value;
    }

    public function process(\DOMNode $context)
    {
        if (method_exists($context, 'setAttribute')) {
            $class = $context->getAttribute('class');
            $class = $class ? $class . " " . $this->attr_value : $this->attr_value;
            $context->setAttribute('class', $class);

            return $context;
        } else {
            throw new TokenUsageException($this, 'Trying to set an attribute on unsupported node: '. $context->nodeName);
        }
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_CLASS', 'T_ATOM')),
        );
    }
}
