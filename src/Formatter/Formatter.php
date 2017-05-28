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

        $this->alterations($node, new \DOMXpath($document), $formatOutput);
        $html = $document->saveXML($node, LIBXML_NOEMPTYTAG);

        $html = str_replace('="'.AttributeToken::DEFAULT_EMPTY.'"', '', $html);
        $html = preg_replace('/><\/(?:'.implode('|', $this->inline_elements).')>/i', '>', $html);
        $html = preg_replace('/^<\\?xml version.+\n/', '', $html);

        if (!$formatOutput) {
            $html = trim($html);
        }

        return $html;
    }

    protected function alterations(\DOMNode $node, \DOMXPath $xpath, $formatOutput)
    {
        if ($formatOutput) {
            // Find non-empty
            $scripts = $xpath->query('//script[. != \'\']', $node);
            foreach ($scripts as $script) {
                $content = trim($script->nodeValue, "\r\n");
                // Detect current indentation based on first line.
                preg_match('/^[ \t]*/', $content, $match);

                $trim = $match[0];
                $depth = substr_count($script->getNodePath(), '/');
                $padding = str_repeat('  ', $depth); // indentation of 2 spaces
                $content = preg_replace("/^${trim}/m", $padding, $content);

                $script->nodeValue = PHP_EOL . $content . PHP_EOL . str_repeat('  ', $depth - 1);
            }
        }
    }
}
