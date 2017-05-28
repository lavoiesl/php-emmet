<?php

namespace Lavoiesl\Emmet;

use Lavoiesl\Emmet\Formatter\Html5Formatter;

class Document extends \DOMDocument implements \ArrayAccess
{
    use ElementCommon;

    private static $emmet;

    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        parent::__construct($version, $encoding);

        $this->registerNodeClass('DOMElement', 'Lavoiesl\\Emmet\\Element');
        $this->registerNodeClass('DOMDocument', __CLASS__);

        $this->formatOutput = true;
    }

    public static function getEmmet()
    {
        return self::$emmet;
    }

    public static function setEmmet(Emmet $emmet)
    {
        self::$emmet = $emmet;
    }

    public function getDocument()
    {
        return $this;
    }

    public function getNode()
    {
        return $this->documentElement;
    }

    public function options($options)
    {
        if (is_string($options)) {
            switch ($options) {
                case 'inline':
                    $options = array('formatOutput' => false);
                    break;

                default:
                    throw new \InvalidArgumentException('Unknown document option: "' . $options . '".');
                    break;
            }
        }

        foreach ($options as $name => $value) {
            $this->$name = $value;
        }
    }

    public function processEmmet($string, \DOMNode $context, $state = 'default')
    {
        $tokens = self::$emmet->getParser()->parse($string, $state);

        if (!$tokens) {
            throw new \InvalidArgumentException('Invalid input: "' . $string . '".');
        }

        foreach ($tokens as $token) {
            $context = $token->process($context);
        }

        return $context;
    }

    public function appendChild(\DOMNode $node)
    {
        if ($this->documentElement === null) {
            return parent::appendChild($node);
        } else {
            throw new \LogicException('The document already has a documentElement.');
        }
    }

    public function elementToString(\DOMNode $element = null)
    {
        $formatter = new Html5Formatter();

        return $formatter->formatNode($element, $this->formatOutput);
    }

    public function __toString()
    {
        return $this->elementToString($this);
    }
}
