<?php

namespace Lavoiesl\Emmet\Helper;

class FlowHelper
{
    public function each($context, $items, \Closure $closure)
    {
        $closure = $closure->bindTo($context);

        foreach ($items as $key => $item) {
            if ($closure($key, $item) === false) {
                break;
            }
        }
    }

    public function repeat($context, $count, \Closure $closure)
    {
        $closure = $closure->bindTo($context);

        for ($i=0; $i < $count; $i++) {
            if ($closure($i) === false) {
                break;
            }
        }
    }

    public function with($context, \Closure $closure)
    {
        $closure = $closure->bindTo($context);
        $closure();
    }
}
