<?php

namespace Lavoiesl\Emmet;

trait ElementCommon
{
    abstract public function getEmmet();
    abstract public function getDocument();
    abstract public function getNode();


    public function offsetExists($offset)
    {
        // unsupported
    }

    public function offsetGet($offset)
    {
        return $this->getDocument()->processEmmet($offset, $this);
    }

    public function offsetSet($offset, $value)
    {
        // unsupported
    }

    public function offsetUnset($offset)
    {
        // unsupported
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __call($name, $args)
    {
        return $this->getEmmet()->callHelper($name, $this, $args);
    }

    public function append($element)
    {
        $dom = $this->getDocument()->createElement($element);

        return $this->appendChild($dom);
    }

    public function setRef(&$ref)
    {
        $ref = $this;

        return $this;
    }

    public function __toString()
    {
        return $this->getDocument()->elementToString($this->getNode());
    }
}
