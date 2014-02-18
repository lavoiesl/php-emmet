<?php

namespace Lavoiesl\Emmet\Formatter;

class Html5Formatter extends Formatter
{
    protected $inline_elements = array(
        'area',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'frame',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    );

    public function formatNode(\DOMNode $node, $formatOutput = true)
    {
        $html = parent::formatNode($node, $formatOutput);

        $html = preg_replace('/><\/(?:'.implode('|', $this->inline_elements).')>/i', '>', $html);

        return $html;
    }
}
