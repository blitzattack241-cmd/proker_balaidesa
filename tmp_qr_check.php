<?php
$dir = __DIR__ . '/pages';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($rii as $file) {
    if (!$file->isFile() || $file->getFilename() !== 'cetak.php') continue;
    $lines = file($file->getPathname(), FILE_IGNORE_NEW_LINES);
    foreach ($lines as $i => $line) {
        if (strpos($line, 'tampilkanQR(') !== false) {
            $start = max(0, $i - 5);
            $before = array_slice($lines, $start, $i - $start);
            $beforeText = implode("\n", $before);
            if (preg_match('/(<strong|<p class="nama|<p class="ttd-nama|VIWIT|KISWO|<strong\s*style|<p style="text-decoration: underline)/i', $beforeText)) {
                echo "NAME_BEFORE_QR: " . $file->getPathname() . ':' . ($i+1) . PHP_EOL;
                echo "---\n" . $beforeText . PHP_EOL . "---\n";
            }
        }
    }
}
