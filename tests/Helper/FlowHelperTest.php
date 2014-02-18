<?php

namespace Lavoiesl\Emmet\Test\Helper;

use Lavoiesl\Emmet\Test\AbstractEmmetTest;

class FlowHelperTest extends AbstractEmmetTest
{

    public function testWith()
    {
        $html = $this->emmet->create('inline')->div->with(function() {
            $this['a.foo'];
            $this['a.bar'];
        });

        $this->assertEquals('<div><a class="foo"></a><a class="bar"></a></div>', ''.$html);
    }

    public function testEach()
    {
        $html = $this->emmet->create('inline')->div->each(['foo', 'bar'], function($_, $class) {
            $this["a.$class"];
            return false; // break
        });
        $this->assertEquals('<div><a class="foo"></a></div>', ''.$html);

        $html = $this->emmet->create('inline')->div->each(['foo', 'bar'], function($_, $class) {
            $this["a.$class"];
        });
        $this->assertEquals('<div><a class="foo"></a><a class="bar"></a></div>', ''.$html);

        $html = $this->emmet->create('inline')->div->each(['foo' => 'bar', 'baz' => 'qux'], function($key, $class) {
            $this["a#$key.$class"];
        });
        $this->assertEquals('<div><a id="foo" class="bar"></a><a id="baz" class="qux"></a></div>', ''.$html);
    }

    public function testRepeat()
    {
        $html = $this->emmet->create('inline')->div->repeat(2, function($i) {
            $this["hr"];
            return false; // break
        });
        $this->assertEquals('<div><hr></div>', ''.$html);

        $html = $this->emmet->create('inline')->div->repeat(2, function($i) {
            $this["hr"];
        });
        $this->assertEquals('<div><hr><hr></div>', ''.$html);

        $html = $this->emmet->create('inline')->div->repeat(2, function($i) {
            $this->_->attr("data-$i", $i);
        });
        $this->assertEquals('<div data-0="0" data-1="1"></div>', ''.$html);
    }
}
