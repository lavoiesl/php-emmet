<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;
use Lavoiesl\Emmet\Parser\Exception\TokenUsageException;

class AttributeToken extends ParserToken
{
    protected $attr_name = null;

    protected $attr_value = null;

    public function __construct($name, array $lexerTokens)
    {
        if (null === $this->attr_name) {
            $this->attr_name = $lexerTokens[1]->value;
        }

        parent::__construct($name, $lexerTokens);
    }

    public function process(\DOMNode $context)
    {
        if (method_exists($context, 'setAttribute')) {
            $context->setAttribute($this->attr_name, $this->attr_value);

            return $context;
        } else {
            throw new TokenUsageException($this, 'Trying to set an attribute on unsupported node: '. $context->nodeName);
        }
    }

    public function __toString()
    {
        return $this->attr_name . ($this->attr_value === null ? '' : "=\"{$this->attr_value}\"");
    }
}
