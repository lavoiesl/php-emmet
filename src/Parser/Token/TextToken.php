<?php

namespace Lavoiesl\Emmet\Parser\Token;

use Lavoiesl\Emmet\Parser\ParserRule;
use Lavoiesl\Emmet\Parser\Exception\TokenUsageException;

class TextToken extends ParserToken
{
    protected $text;

    public function __construct($name, array $lexerTokens)
    {
        if (null === $this->text) {
            $this->text = $lexerTokens[0]->value;
        }

        parent::__construct($name, $lexerTokens);
    }

    public function process(\DOMNode $context)
    {
        if ($context instanceof \DOMDocument) {
            throw new TokenUsageException($this, 'Trying to set text on unsupported node: ' . $context->nodeName);
        }

        return $context->t($this->text);
    }

    public static function getParserRules()
    {
        return array(
            new ParserRule(__CLASS__, 'default', array('T_TEXT')),
        );
    }

    public function __toString()
    {
        return $this->text;
    }
}
