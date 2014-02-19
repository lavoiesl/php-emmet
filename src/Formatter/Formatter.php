<?php

namespace Lavoiesl\Emmet\Formatter;

use Lavoiesl\Emmet\Parser\Token\AttributeToken;

class Formatter
{
    public function formatNode(\DOMNode $node, $formatOutput = true)
    {
        $document = $node instanceof \DOMDocument ? $node : $node->ownerDocument;
        $document->formatOutput = $formatOutput;
        $document->preserveWhiteSpace = true;

        $html = $document->saveXML($node, LIBXML_NOEMPTYTAG);

        $html = str_replace('="'.AttributeToken::DEFAULT_EMPTY.'"', '', $html);
        $html = preg_replace('/><\/(?:'.implode('|', $this->inline_elements).')>/i', '>', $html);

        return $html;
    }
}
