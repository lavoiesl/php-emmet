<?php

namespace Lavoiesl\Emmet\Test\Helper;

use Lavoiesl\Emmet\Test\AbstractEmmetTest;

class TagHelperTest extends AbstractEmmetTest
{
    public function testStylesheet()
    {
        $html = $this->emmet->create('inline')->head->stylesheet('#')->_;
        $this->assertEquals('<head><link rel="stylesheet" href="#"></head>', ''.$html);
    }

    public function testScript()
    {
        $html = $this->emmet->create('inline')->head->script('#', ['id' => 'foo'])->_;
        $this->assertEquals('<head><script src="#" id="foo"></script></head>', ''.$html);
    }

    public function testComment()
    {
        $html = $this->emmet->create('inline')->div->comment('foo');
        $this->assertEquals('<div><!-- foo --></div>', ''.$html);
    }
}
