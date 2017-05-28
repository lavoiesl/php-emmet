<?php

namespace Lavoiesl\Emmet\Test\Helper;

use Lavoiesl\Emmet\Test\AbstractEmmetTest;

class HelperTest extends AbstractEmmetTest
{
    public function testInvalidHelper()
    {
        $this->expectException('BadFunctionCallException', "Helper foo does not exist.");
        $this->emmet->foo();
    }

    public function testHelper()
    {
        $this->emmet->addHelper(new TestHelper);
        $this->assertTrue(true);
    }
}

class TestHelper
{
    public function __construct()
    {
    }
}
