<?php

include 'bootstrap.php';

$start = microtime(true);
$emmet = include 'template.php';

$emmet->navigate('/html/body/div')->pre->t('Rendered in ' . number_format(1000 * (microtime(true) - $start), 2) . ' ms');

echo $emmet;
