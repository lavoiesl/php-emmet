<?php

namespace Lavoiesl\Emmet\Test\Parser;

use Lavoiesl\Emmet\Parser\Lexer;
use Lavoiesl\Emmet\Parser\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    protected $parser;

    public function setUp()
    {
        $this->parser = new Parser(new Lexer);
        $this->parser->compile();
    }

    /**
     * @dataProvider tokensProvider
     */
    public function testTokens($string, $expected)
    {
        $tokens = $this->parser->parse($string);

        $this->assertEquals($expected, implode(' ', $tokens));
    }

    public function tokensProvider()
    {
        return array(
            array('', ''),
            array('_', ''),
            array('__', ''),
            array('a', 'a'),
            array('a>b', 'a b'),
            array('>b', 'b'),
            array('+b', 'b'),
            array('{b}', 'b'),
            array('a{b}', 'a b'),
            array('a{b}{c}', 'a b c'),
            array('.foo', 'class="foo"'),
            array('#foo', 'id="foo"'),
            array('[foo="bar"]', 'foo="bar"'),
            array('[foo]', 'foo'),
            array('[foo=]', 'foo=""'),
            array('[foo=""]', 'foo=""'),
            array('a.foo#bar[href="#"]', 'a class="foo" id="bar" href="#"'),
        );
    }

    /**
     * @expectedException Lavoiesl\Emmet\Parser\Exception\ParserException
     * @dataProvider invalidElementProvider
     */
    public function testInvalidElement($string)
    {
        $this->parser->parse($string);
    }

    public function invalidElementProvider()
    {
        return array(
            array('.'),
            array('+'),
            array('>'),
            array('..'),
            array('#'),
            array('##'),
            array('[]'),
            array('[=]'),
            array('[=""]'),
            array('a.'),
            array('a#'),
        );
    }
}
