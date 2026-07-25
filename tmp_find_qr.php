<?php
$dir = __DIR__ . '/pages';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($rii as $file) {
    if (!$file->isFile() || $file->getFilename() !== 'cetak.php') continue;
    $lines = file($file->getPathname(), FILE_IGNORE_NEW_LINES);
    foreach ($lines as $i => $line) {
        if (strpos($line, 'tampilkanQR(') !== false) {
            $start = max(0, $i - 5);
            $slice = array_slice($lines, $start, min(11, count($lines) - $start));
            $before = array_slice($slice, 0, $i - $start);
            $after = array_slice($slice, $i - $start + 1);
            if (preg_match('/(nama|nama-kades|ttd-nama|strong.*<u|<p class="nama|<p class="ttd-nama)/i', implode("\n", $before))) {
                echo "FOUND_NAME_BEFORE_QR: " . $file->getPathname() . ':' . ($i+1) . "\n";
            }
            if (!preg_match('/(nama|nama-kades|ttd-nama|strong.*<u|<p class="nama|<p class="ttd-nama)/i', implode("\n", $after))) {
                echo "FOUND_QR_NOT_FOLLOWED_BY_NAME: " . $file->getPathname() . ':' . ($i+1) . "\n";
            }
        }
    }
}
