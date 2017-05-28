<?php

namespace Lavoiesl\Emmet\Test;

use Lavoiesl\Emmet\Emmet;
use PHPUnit\Framework\TestCase;

abstract class AbstractEmmetTest extends TestCase
{
    protected $emmet;

    public function setUp()
    {
        $this->emmet = new Emmet;
    }
}
