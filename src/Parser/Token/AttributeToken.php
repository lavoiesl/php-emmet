<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;
use Lavoiesl\Emmet\Parser\Exception\TokenUsageException;

class AttributeToken extends ParserToken
{
    const DEFAULT_EMPTY = 'DEFAULT_EMPTY_ATTRIBUTE';

    public $name = null;

    public $value = null;

    public function __construct(array $tokens)
    {
        parent::__construct($tokens);

        $this->name = $tokens[1]->value;

        if (isset($tokens[3])) {
            $this->value = $tokens[3]->value;
        } else {
            $this->value = self::DEFAULT_EMPTY;
        }
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_CLOSE')),
            new ParserRule(__CLASS__, 'default', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_ATTR_CLOSE')),
            new ParserRule(__CLASS__, 'default', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_ATOM', 'T_ATTR_CLOSE')),
            new ParserRule(__CLASS__, 'default', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_STRING', 'T_ATTR_CLOSE')),
        );
    }

    public function process(\DOMNode $context)
    {
        if (method_exists($context, 'setAttribute')) {
            $context->setAttribute($this->name, $this->value);

            return $context;
        } else {
            throw new TokenUsageException($this, 'Trying to set an attribute on unsupported node: '. $context->nodeName);
        }
    }

    public function __toString()
    {
        return $this->name . ($this->value === null || $this->value === self::DEFAULT_EMPTY ? '' : "=\"{$this->value}\"");
    }
}
