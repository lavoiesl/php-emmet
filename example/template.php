<?php

return $emmet
->doctype()
->html
  ->head
    ->meta['[charset="utf-8"]_']
    ->title['{Hi!}_']
    ->stylesheet("//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css")->_

  ->body
    ->div['.container']

      ->h1->t('Hi!')->_

      ->with(function () {
        if (true) {
          $this['p']->h('Arbitrary PHP in the middle using <code>with</code>.');
        }
      })

      ->repeat(3, function ($i) {
        $this['p']->h('Usage of the <code>repeat</code> function: ' . $i);
      })

      ->comment("foreach + custom helper")
      ->div
        ->each(['success', 'info', 'warning'], function ($_, $class) {
          $this->alert($class, 'Usage of the <code>each</code> function: '. $class);
        })->_

      ->p->h('This is some parsed <i>HTML</i>.')->_
      ->p->t('This is some literal <i>HTML</i>.')->_
      ->p->a['[href="xhprof.php?n=1000"]{See the XHProf run of running this example 1000 times.}_']
->__;
