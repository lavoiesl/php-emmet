<?php

namespace Lavoiesl\Emmet;

use Lavoiesl\Emmet\Parser\Parser;
use Lavoiesl\Emmet\Parser\Lexer;
use Lavoiesl\Emmet\Helper;

class Emmet implements \ArrayAccess
{
    use ElementCommon;

    private $helpers = array();

    private $parser;

    public function __construct()
    {
        $this->addHelper(new Helper\ContentHelper);
        $this->addHelper(new Helper\FlowHelper);
        $this->addHelper(new Helper\TagHelper);

        $this->parser = new Parser(new Lexer);
    }

    public function getParser()
    {
        return $this->parser;
    }

    public function getEmmet()
    {
        return $this;
    }

    public function getDocument()
    {
        return $this->create();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getNode()
    {
        return $this->getDocument()->getNode();
    }

    public function offsetGet($offset)
    {
        $document = $this->getDocument();

        return $document->processEmmet($offset, $document);
    }

    public function __invoke($options)
    {
        return $this->create($options);
    }

    public function create($options = null)
    {
        $document = new Document;
        $document->setEmmet($this);
        if ($options !== null) {
            $document->options($options);
        }

        return $document;
    }

    public function addHelper($helper)
    {
        $methods = get_class_methods($helper);

        foreach ($methods as $method) {
            switch ($method) {
                case '__construct':
                    break;
                default:
                    $this->helpers[$method] = $helper;
                    break;
            }
        }
    }

    public function callHelper($name, $context, array $args)
    {
        if (isset($this->helpers[$name])) {
            array_unshift($args, $context);
            $return = call_user_func_array(array($this->helpers[$name], $name), $args);

            return is_object($return) ? $return : $context;
        } else {
            throw new \BadFunctionCallException("Helper $name does not exist.");
        }
    }
}
