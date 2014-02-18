<?php

namespace Lavoiesl\Emmet\Test;

use Lavoiesl\Emmet\Emmet;

abstract class AbstractEmmetTest extends \PHPUnit_Framework_TestCase
{
    protected $emmet;

    public function setUp()
    {
        $this->emmet = new Emmet;
    }
}
