<?php

namespace Lavoiesl\Emmet\Test\Helper;

use Lavoiesl\Emmet\Test\AbstractEmmetTest;

class ContentHelperTest extends AbstractEmmetTest
{
    public function testParsedHtml()
    {
        $html = $this->emmet->create('inline')->h('This is some parsed <i>HTML</i>.');
        $this->assertEquals("This is some parsed \n<i>HTML</i>\n.", ''.$html);

        $html = $this->emmet->create('inline')->div->h('This is some parsed <i>HTML</i>.');
        $this->assertEquals('<div>This is some parsed <i>HTML</i>.</div>', ''.$html);

        $html = $this->emmet->create('inline')->div->h('<p>This is some parsed <i>HTML</i>.</p>');
        $this->assertEquals('<div><p>This is some parsed <i>HTML</i>.</p></div>', ''.$html);
    }

    public function testLiteralHtml()
    {
        $html = $this->emmet->create('inline')->div->t('This is some literal <HTML/>.');
        $this->assertEquals('<div>This is some literal &lt;HTML/&gt;.</div>', ''.$html);
    }
}
