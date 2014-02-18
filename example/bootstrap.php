<?php

use Lavoiesl\Emmet\Emmet;

require '../vendor/autoload.php';

class BootstrapHelper
{
    public function alert($context, $class, $html)
    {
        return $context["div.alert.alert-$class"]->h($html);
    }
}

$emmet = new Emmet;
$emmet->addHelper(new BootstrapHelper);
