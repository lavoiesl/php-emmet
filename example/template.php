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
        $p = $this['p'];
        $p['arbitrary'] = "true";
        $p->h('Arbitrary PHP in the middle using <code>with</code>.');
      })

      ->h2->h('Usage of the <code>repeat</code> function.')->_
      ->ul
        ->repeat(3, function ($i) {
          $this['li']->h("Step #$i");
        })->_

      ->comment("A comment")

      ->h2->h('Usage of the <code>each</code> function.')->_
      ->div
        ->each(['success', 'info', 'warning'], function ($_, $class) {
          $this->alert($class, "A '${class}' alert.");
        })->_

      ->p->h('This is some parsed <i>HTML</i>.')->_
      ->p->t('This is some literal <i>HTML</i>.')->_
      ->p->a['[href="xhprof.php?n=1000"]{See the XHProf run of running this example 1000 times.}_']
->__;
