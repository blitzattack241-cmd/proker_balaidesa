<?php
header('Content-Type: text/plain; charset=utf-8');

echo "debug import handler test\n";
echo "GET test: " . (isset($_GET['test']) ? $_GET['test'] : 'none') . "\n";
?>