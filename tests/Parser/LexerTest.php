<?php

namespace Lavoiesl\Emmet\Test\Parser;

use Lavoiesl\Emmet\Parser\Lexer;

class LexerTest extends \PHPUnit_Framework_TestCase
{
    protected $lexer;

    public function setUp()
    {
        $this->lexer = new Lexer;
    }

    /**
     * @dataProvider tokensProvider
     */
    public function testTokens($string, array $expected)
    {
        $tokens = $this->lexer->parse($string);
        $tokens = array_map(function($token) { return $token->name; }, $tokens);

        $this->assertEquals($tokens, $expected);
    }

    public function tokensProvider()
    {
        return array(
            array('a', array('T_ATOM')),
            array('.foo', array('T_CLASS', 'T_ATOM')),
            array('>foo', array('T_CHILD', 'T_ATOM')),
            array('+foo', array('T_SIBLING', 'T_ATOM')),
            array('#foo', array('T_ID', 'T_ATOM')),
            array('{foo}', array('T_TEXT')),
            array('{foo\\}}', array('T_TEXT')),
            array('[x]', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_CLOSE')),
            array('[x=]', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_ATTR_CLOSE')),
            array('[x="a"]', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_STRING', 'T_ATTR_CLOSE')),
            array('[x=a]', array('T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_ATOM', 'T_ATTR_CLOSE')),
            array('a.foo#bar[href="#"]{baz}', array('T_ATOM', 'T_CLASS', 'T_ATOM', 'T_ID', 'T_ATOM', 'T_ATTR_OPEN', 'T_ATOM', 'T_ATTR_SEPARATOR', 'T_STRING', 'T_ATTR_CLOSE', 'T_TEXT')),

            array('_', array('T_PARENT')),
            array('__', array('T_DOCUMENT')),
            array('a_', array('T_ATOM', 'T_PARENT')),
            array('a__', array('T_ATOM', 'T_DOCUMENT')),
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @dataProvider invalidArgumentProvider
     */
    public function testInvalidArgument($input)
    {
        $this->lexer->parse($input);
    }

    public function invalidArgumentProvider()
    {
        return array(
            array(null),
            array(new \stdClass),
        );
    }

    /**
     * @expectedException Lavoiesl\Emmet\Parser\Exception\LexerException
     * @dataProvider invalidElementProvider
     */
    public function testInvalidElement($string)
    {
        $this->lexer->parse($string);
    }

    public function invalidElementProvider()
    {
        return array(
            array('?'),
            array('_a'),
            array('__a'),
            array('[foo="""]'),
        );
    }
}
