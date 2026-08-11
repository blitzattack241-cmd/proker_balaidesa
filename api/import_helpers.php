<?php
if (!defined('IMPORT_HELPERS_SKIP_DB')) {
    require_once __DIR__ . '/../koneksi.php';
}

// Attempt to load Composer autoload for PhpSpreadsheet and other deps
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoloadPath) && PHP_VERSION_ID >= 80200) {
    require_once $autoloadPath;
} else {
    // Minimal manual autoload fallback for PhpSpreadsheet classes if vendor autoload is not present
    $manualAutoloadMap = [
        'PhpOffice\\PhpSpreadsheet\\' => __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/',
    ];
    spl_autoload_register(function ($class) use ($manualAutoloadMap) {
        foreach ($manualAutoloadMap as $prefix => $baseDir) {
            if (strncmp($class, $prefix, strlen($prefix)) !== 0) continue;
            $relativeClass = substr($class, strlen($prefix));
            $relativePath = str_replace('\\', '/', $relativeClass) . '.php';
            $filePath = $baseDir . $relativePath;
            if (is_file($filePath)) {
                require_once $filePath;
                return true;
            }
        }
        return false;
    });
}

// Prefer the standalone SimpleXLSX parser for .xlsx files when Composer is unavailable.
$simpleXlsxPath = __DIR__ . '/../includes/SimpleXLSX.php';
if (is_file($simpleXlsxPath)) {
    require_once $simpleXlsxPath;
}

function normalize_header(string $value): string
{
    $value = trim($value);
    $value = preg_replace('/\x{FEFF}/u', '', $value);
    $value = mb_strtolower($value, 'UTF-8');
    // remove non-alnum and replace with space
    $value = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $value);
    $value = preg_replace('/\s+/', ' ', $value);
    $value = trim($value);
    // remove diacritics
    $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);
    if ($trans !== false) {
        $value = $trans;
    }
    $value = preg_replace('/[^a-z0-9 ]+/', '', strtolower($value));
    return $value;
}

function load_rows_from_file(string $path, string $ext): array
{
    $rows = [];
    if ($ext === 'csv') {
        $delimiter = parse_csv_delimiter($path);
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return [];
        }
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($data) === 1 && $data[0] === null) {
                continue;
            }
            if (isset($data[0])) {
                $data[0] = preg_replace('/^\x{FEFF}/u', '', $data[0]);
            }
            $rows[] = $data;
        }
        fclose($handle);
        return $rows;
    }

    if ($ext === 'xlsx') {
        if (class_exists('Shuchkin\\SimpleXLSX')) {
            $xlsx = \Shuchkin\SimpleXLSX::parseFile($path);
            if (!$xlsx) {
                $error = \Shuchkin\SimpleXLSX::parseError() ?: 'Tidak dapat membaca file XLSX';
                throw new RuntimeException($error);
            }
            return $xlsx->rows();
        }

        if (class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            return $spreadsheet->getActiveSheet()->toArray();
        }

        throw new RuntimeException('Dukungan XLSX memerlukan includes/SimpleXLSX.php atau PhpSpreadsheet melalui Composer.');
    }

    if ($ext === 'xls') {
        if (class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            return $spreadsheet->getActiveSheet()->toArray();
        }

        throw new RuntimeException('Dukungan XLS memerlukan PhpSpreadsheet melalui Composer. Simpan file sebagai CSV atau XLSX sebagai gantinya.');
    }

    // Use PhpSpreadsheet for other types
    if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
        throw new RuntimeException('Kelas PhpSpreadsheet tidak tersedia. Jalankan composer install atau sediakan vendor autoload.');
    }
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($path);
    return $spreadsheet->getActiveSheet()->toArray();
}

function parse_csv_delimiter(string $path): string
{
    $handle = fopen($path, 'r');
    if ($handle === false) {
        return ',';
    }
    $line = fgets($handle);
    fclose($handle);
    if ($line === false) {
        return ',';
    }
    $commaCount = substr_count($line, ',');
    $semicolonCount = substr_count($line, ';');
    return $semicolonCount > $commaCount ? ';' : ',';
}

function canonical_map(): array
{
    $map = [
        'rt' => 'rt',
        'rw' => 'rw',
        'no kk' => 'no_kk',
        'no_ktp' => 'nik',
        'nik' => 'nik',
        'nama' => 'nama',
        'kepala kk' => 'kepala_kk',
        'jenis kelamin' => 'jenis_kelamin',
        'jk' => 'jenis_kelamin',
        'status keluarga' => 'status_keluarga',
        'tempat lahir' => 'tempat_lahir',
        'ttl' => 'tempat_lahir',
        'tanggal lahir' => 'tgl_lahir',
        'tgl lahir' => 'tgl_lahir',
        'status pernikahan' => 'status_pernikahan',
        'agama' => 'agama',
        'kewarganegaraan' => 'kewarganegaraan',
        'suku' => 'suku',
        'pendidikan' => 'pendidikan',
        'pekerjaan' => 'pekerjaan',
        'alamat' => 'alamat',
        'umur' => 'umur',
    ];
    // load user synonyms if present
    $synFile = __DIR__ . '/../data/import_mappings.json';
    if (is_file($synFile)) {
        $json = json_decode(file_get_contents($synFile), true);
        if (is_array($json) && isset($json['synonyms']) && is_array($json['synonyms'])) {
            foreach ($json['synonyms'] as $canon => $arr) {
                foreach ($arr as $alias) {
                    $k = normalize_header($alias);
                    $map[$k] = $canon;
                }
            }
        }
    }
    return $map;
}

function suggest_mappings(array $headers): array
{
    $suggestions = [];
    $canon = canonical_map();
    foreach ($headers as $idx => $h) {
        $norm = normalize_header((string) $h);
        $best = null;
        $bestScore = 0;

        // exact
        if (isset($canon[$norm])) {
            $best = $canon[$norm];
            $bestScore = 100;
        } else {
            // token overlap
            $tokens = $norm === '' ? [] : explode(' ', $norm);
            foreach ($canon as $k => $v) {
                $kTokens = $k === '' ? [] : explode(' ', $k);
                $common = count(array_intersect($tokens, $kTokens));
                if ($common > 0) {
                    $score = 50 + ($common * 10);
                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $best = $v;
                    }
                }
            }
            // levenshtein fallback
            if ($best === null) {
                foreach ($canon as $k => $v) {
                    $dist = levenshtein($norm, $k);
                    $len = max(strlen($norm), strlen($k));
                    $sim = $len > 0 ? (1 - ($dist / $len)) * 100 : 0;
                    if ($sim > $bestScore && $sim > 50) {
                        $bestScore = (int) $sim;
                        $best = $v;
                    }
                }
            }
        }

        $suggestions[$idx] = [
            'original' => $headers[$idx],
            'normalized' => $norm,
            'suggested' => $best,
            'score' => $bestScore,
        ];
    }
    return $suggestions;
}

function parse_date_value($value)
{
    if ($value === null || $value === '') {
        return null;
    }
    if (is_numeric($value)) {
        try {
            if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\Shared\\Date')) return null;
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }
    $ts = strtotime(str_replace('/', '-', (string) $value));
    if ($ts === false) {
        return null;
    }
    return date('Y-m-d', $ts);
}

function ensure_import_logs_table()
{
    global $koneksi;
    $sql = "CREATE TABLE IF NOT EXISTS import_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) DEFAULT NULL,
        user VARCHAR(100) DEFAULT NULL,
        inserted INT DEFAULT 0,
        updated INT DEFAULT 0,
        skipped INT DEFAULT 0,
        failed INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($koneksi, $sql);
}
