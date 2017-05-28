<?php

namespace Lavoiesl\Emmet;

class Element extends \DOMElement implements \ArrayAccess
{
    use ElementCommon;

    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->removeAttribute($offset);
    }

    public function getEmmet()
    {
        return $this->getDocument()->getEmmet();
    }

    public function getDocument()
    {
        return $this->ownerDocument;
    }

    public function getNode()
    {
        return $this;
    }
}
