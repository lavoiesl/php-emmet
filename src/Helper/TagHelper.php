<?php

namespace Lavoiesl\Emmet\Helper;

use Lavoiesl\Emmet\Document;
use Lavoiesl\Emmet\Parser\Token\AttributeNullToken;

class TagHelper
{
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
