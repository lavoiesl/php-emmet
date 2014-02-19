<?php

namespace Lavoiesl\Emmet\Helper;

use Lavoiesl\Emmet\Document;
use Lavoiesl\Emmet\Parser\Token\AttributeToken;

class ContentHelper
{
    public function t($context, $text)
    {
        $text = $context->getDocument()->createTextNode($text);
        $context->appendChild($text);
    }

    public function h($context, $html)
    {
        $fragment = $context->getDocument()->createDocumentFragment();
        $fragment->appendXML($html);
        $context->appendChild($fragment);
    }

    public function attr($context, $name, $value = null)
    {
        if ($value === null) {
            $value = AttributeToken::DEFAULT_EMPTY;
        }

        $context->getNode()->setAttribute($name, $value);
    }
}
