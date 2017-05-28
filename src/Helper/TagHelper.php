<?php

namespace Lavoiesl\Emmet\Helper;

class TagHelper
{
    public function doctype($context, $qualifiedName = 'html', $publicId = null, $systemId = null)
    {
        $imp = new \DOMImplementation();
        $context->appendChild($imp->createDocumentType($qualifiedName, $publicId, $systemId));
    }

    public function stylesheet($context, $href, array $attrs = array())
    {
        $node = $context
            ->append('link')
            ->attr('rel', 'stylesheet')
            ->attr('href', $href);

        self::setAttributes($node, $attrs);
    }

    public function script($context, $src, array $attrs = array())
    {
        $node = $context
            ->append('script')
            ->attr('src', $src);

        self::setAttributes($node, $attrs);
    }

    public function comment($context, $comment)
    {
        $node = $context->getDocument()->createComment(' ' . $comment . ' ');
        $context->appendChild($node);
    }

    private static function setAttributes($context, array $attrs)
    {
        foreach ($attrs as $attr => $value) {
            $context->attr($attr, $value);
        }
    }
}
