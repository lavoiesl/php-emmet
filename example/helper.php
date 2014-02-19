<?php

class BootstrapHelper
{
    public function alert($context, $class, $html)
    {
        return $context["div.alert.alert-$class"]->h($html);
    }
}

$emmet->addHelper(new BootstrapHelper);
