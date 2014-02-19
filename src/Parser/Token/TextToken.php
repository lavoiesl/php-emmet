<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;
use Lavoiesl\Emmet\Parser\Exception\TokenUsageException;

class TextToken extends ParserToken
{
    public $value;

    public function __construct(array $tokens)
    {
        parent::__construct($tokens);

        $this->value = $tokens[0]->value;
    }

    public function process(\DOMNode $context)
    {
        if ($context instanceof \DOMDocument) {
            throw new TokenUsageException($this, 'Trying to set text on unsupported node: ' . $context->nodeName);
        }

        return $context->t($this->value);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_TEXT')),
        );
    }

    public function __toString()
    {
        return $this->value;
    }
}
