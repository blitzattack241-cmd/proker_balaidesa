<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/pages'));
foreach ($files as $file) {
    if (!$file->isFile() || $file->getFilename() !== 'cetak.php') continue;
    $lines = file($file->getPathname(), FILE_IGNORE_NEW_LINES);
    foreach ($lines as $index => $line) {
        if (strpos($line, 'tampilkanQR(') !== false) {
            echo "FILE: {$file->getPathname()} LINE: " . ($index + 1) . PHP_EOL;
            $start = max(0, $index - 4);
            $end = min(count($lines) - 1, $index + 4);
            for ($i = $start; $i <= $end; $i++) {
                $prefix = ($i === $index) ? '>> ' : '   ';
                printf("%s%4d: %s\n", $prefix, $i + 1, $lines[$i]);
            }
            echo str_repeat('-', 60) . PHP_EOL;
        }
    }
}
