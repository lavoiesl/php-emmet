<!doctype html>
<?php

include 'bootstrap.php';

$start = microtime(true);
echo include 'template.php';

echo '<pre>Rendered in ' . number_format(1000 * (microtime(true) - $start), 2) . ' ms</pre>';