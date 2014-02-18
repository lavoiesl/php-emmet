<?php

namespace Lavoiesl\Emmet\Test;

class EmmetTest extends AbstractEmmetTest
{
    /**
     * Testing all underscore combinaisons
     */
    public function testUnderscore()
    {
        $foo = $this->emmet
            ->a
                ->b_
                ->c['_']
            ->__
        ;

        $bar = $this->emmet
            ->a
                ->b->_
                ->c__
        ;

        $this->assertEquals('' . $foo, '' . $bar);
    }

    public function testDuplicateDocumentElement()
    {
        $this->setExpectedException('LogicException', 'The document already has a documentElement.');

        $html = $this->emmet
            ->a_
            ->b_
        ->__;
    }

    public function testAttribute()
    {
        $emmet = $this->emmet;

        $html = $this->emmet->create(array('formatOutput' => false))->hr['[foo]'];
        $this->assertEquals('<hr foo>', ''.$html);

        $html = $this->emmet->create('inline')->hr['[foo=]'];
        $this->assertEquals('<hr foo="">', ''.$html);

        $html = $emmet('inline')->hr['[foo=""]'];
        $this->assertEquals('<hr foo="">', ''.$html);

        $html = $emmet('inline')->hr->attr('foo');
        $this->assertEquals('<hr foo>', ''.$html);

        $html = $emmet('inline')->hr->attr('foo', '');
        $this->assertEquals('<hr foo="">', ''.$html);

        $html = $emmet('inline')->hr->attr('foo', 'bar');
        $this->assertEquals('<hr foo="bar">', ''.$html);
    }

    public function testInline()
    {
        $html = $this->emmet
            ->a['#foo']
                ->hr['.bar']
        ->__;

        $html->formatOutput = false;

        $this->assertEquals('<a id="foo"><hr class="bar"></a>', ''.$html);
    }

    public function testText()
    {
        $html = $this->emmet->a['{foo}{ bar}']['{baz}'];

        $this->assertEquals('<a>foo barbaz</a>', ''.$html);
    }

    public function testScript()
    {
        $html = $this->emmet->script->t('var x = {};');

        $this->assertEquals('<script>var x = {};</script>', ''.$html);
    }

    public function testRef()
    {
        $html = $this->emmet->create('inline')->a->setRef($a)->b->__;
        $a['.foo'];

        $this->assertEquals('<a class="foo"><b></b></a>', ''.$html);
    }

    public function testFull()
    {
        $html = $this->emmet
            ->html['#html']
                ->body
                    ->div['.container']
                        ->input['[disabled]_']
                        ->repeat(2, function() {
                            $this->p['a+b'];
                        })->_
                    ->footer['{footer}']
        ->__;

        $expected = <<<HTML
<html id="html">
  <body>
    <div class="container">
      <input disabled>
      <p>
        <a></a>
        <b></b>
      </p>
      <p>
        <a></a>
        <b></b>
      </p>
    </div>
    <footer>footer</footer>
  </body>
</html>
HTML;
        $this->assertEquals($expected, ''.$html);
    }

    /**
     * This is weird usage, but should not throw errors
     */
    public function testArrayAccess()
    {
        $this->emmet->a->__->_;
        $html = $this->emmet->html->__;

        $html['foo'] = 'bar';
        isset($html['foo']);
        unset($html['foo']);
    }

    public function testEmpty()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid input: "".');

        $this->emmet[''];
    }

    public function testInvalidOptions()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unknown document option: "foobar".');

        $this->emmet->create('foobar');
    }

    /**
     * @dataProvider invalidHtmlProvider
     */
    public function testInvalidHtml($string, $message)
    {
        $this->setExpectedException('Lavoiesl\Emmet\Parser\Exception\TokenUsageException', $message);

        $this->emmet[$string];
    }

    public function invalidHtmlProvider()
    {
        return array(
            array('[href]', 'Usage error at offset 0: Trying to set an attribute on unsupported node: #document'),
            array('.foo', 'Usage error at offset 0: Trying to set an attribute on unsupported node: #document'),
            array('{text}', 'Usage error at offset 0: Trying to set text on unsupported node: #document'),
        );
    }
}
