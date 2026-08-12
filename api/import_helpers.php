<?php
// Don't auto-require koneksi.php - let the caller decide
// if (!defined('IMPORT_HELPERS_SKIP_DB')) {
//     require_once __DIR__ . '/../koneksi.php';
// }

// Attempt to load Composer autoload for PhpSpreadsheet and other deps
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoloadPath) && PHP_VERSION_ID >= 80200) {
    require_once $autoloadPath;
} else {
    // Minimal manual autoload fallback for PhpSpreadsheet classes if vendor autoload is not present
    $manualAutoloadMap = [
        'PhpOffice\\PhpSpreadsheet\\' => __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/',
        // Required by the PhpSpreadsheet readers when Composer's autoloader
        // cannot run on the deployed PHP version.
        'Composer\\Pcre\\' => __DIR__ . '/../vendor/composer/pcre/src/',
        'Psr\\SimpleCache\\' => __DIR__ . '/../vendor/psr/simple-cache/src/',
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

function normalize_header($value): string
{
    // Empty cells and malformed source text must not be passed as null to
    // mb_strtolower(). preg_replace() with a UTF-8 pattern can return null
    // for invalid byte sequences, so normalize that result as well.
    $value = is_scalar($value) ? trim((string) $value) : '';
    $value = preg_replace('/\x{FEFF}/u', '', $value) ?? '';
    $value = mb_strtolower($value, 'UTF-8');
    // remove non-alnum and replace with space
    $value = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $value) ?? '';
    $value = preg_replace('/\s+/', ' ', $value) ?? '';
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
        // Pass the legacy backslash escape character explicitly. PHP 8.4 warns
        // when it is omitted, while this retains the parser's existing behavior.
        while (($data = fgetcsv($handle, 0, $delimiter, '"', '\\')) !== false) {
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
        // The installed PhpSpreadsheet release requires PHP 8.2+. On an older
        // runtime, loading its Xls reader causes an uncatchable parse error;
        // return a normal import error instead.
        if (PHP_VERSION_ID < 80200) {
            throw new RuntimeException('Dukungan file XLS pada server memerlukan PHP 8.2 atau lebih baru.');
        }

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
        // Source columns that are not stored by the application.
        'no' => 'skip',
        'rt' => 'rt',
        'rw' => 'rw',
        'rt rw' => 'rt_rw',
        'no kk' => 'no_kk',
        'no_ktp' => 'nik',
        'no nik' => 'nik',
        'nik' => 'nik',
        'nama' => 'nama',
        'anggota keluarga' => 'nama',
        'kepala kk' => 'kepala_kk',
        'jenis kelamin' => 'jenis_kelamin',
        'jk' => 'jenis_kelamin',
        'status keluarga' => 'status_keluarga',
        'setatus dalam keluarga' => 'status_keluarga',
        'status dalam keluarga' => 'status_keluarga',
        'ttl' => 'ttl',
        'tempat lahir' => 'tempat_lahir',
        'tanggal lahir' => 'tgl_lahir',
        'tgl lahir' => 'tgl_lahir',
        'status pernikahan' => 'status_pernikahan',
        'agama' => 'agama',
        'kewarganegaraan' => 'kewarganegaraan',
        'suku' => 'suku',
        'etnis' => 'suku',
        'etnis suku' => 'suku',
        'pendidikan' => 'pendidikan',
        'pekerjaan' => 'pekerjaan',
    ];
    // load user synonyms if present
    $synFile = __DIR__ . '/../data/import_mappings.json';
    if (is_file($synFile)) {
        $json = json_decode(file_get_contents($synFile), true);
        if (is_array($json) && isset($json['synonyms']) && is_array($json['synonyms'])) {
            foreach ($json['synonyms'] as $canon => $arr) {
                if (!in_array($canon, importable_mapping_fields(), true)) {
                    continue;
                }
                foreach ($arr as $alias) {
                    $k = normalize_header($alias);
                    $map[$k] = $canon;
                }
            }
        }
    }
    return $map;
}

/**
 * The resident import intentionally accepts only these source fields. Every
 * other source column remains mapped to "skip" and is never persisted.
 */
function importable_mapping_fields(): array
{
    return [
        'rt',
        'rw',
        'rt_rw',
        'no_kk',
        'kepala_kk',
        'nik',
        'nama',
        'jenis_kelamin',
        'status_keluarga',
        'ttl',
        'tempat_lahir',
        'tgl_lahir',
        'status_pernikahan',
        'agama',
        'kewarganegaraan',
        'suku',
        'pendidikan',
        'pekerjaan',
    ];
}

/**
 * Exact header structure used by the recurring "Data Penduduk Desa
 * Berugenjang" legacy .xls report. Position is intentionally part of the
 * match so other spreadsheets still use the generic importer below.
 */
function berugenjang_population_report_mapping(): array
{
    return [
        0 => 'skip',
        1 => 'rt',
        2 => 'rw',
        3 => 'no_kk',
        4 => 'kepala_kk',
        5 => 'skip',
        6 => 'nik',
        7 => 'nama',
        8 => 'jenis_kelamin',
        9 => 'status_keluarga',
        10 => 'tempat_lahir',
        11 => 'tgl_lahir',
        12 => 'status_pernikahan',
        13 => 'agama',
        14 => 'kewarganegaraan',
        15 => 'suku',
        16 => 'pendidikan',
        17 => 'pekerjaan',
    ];
}

function is_berugenjang_population_report_header(array $row): bool
{
    $expected = [
        'no', 'rt', 'rw', 'no kk', 'kepala kk', 'no', 'no nik',
        'anggota keluarga', 'jenis kelamin', 'setatus dalam keluarga',
        'tempat lahir', 'tanggal lahir', 'status pernikahan', 'agama',
        'kewarganegaraan', 'etnis suku', 'pendidikan', 'pekerjaan',
    ];

    foreach ($expected as $index => $header) {
        if (normalize_header($row[$index] ?? '') !== $header) {
            return false;
        }
    }

    return true;
}

function is_berugenjang_section_title_row(array $row): bool
{
    $values = array_values(array_filter($row, static function ($value): bool {
        return trim((string) $value) !== '';
    }));

    return count($values) === 1
        && preg_match('/^rt\s*\d+\s+rw\s*\d+$/i', trim((string) $values[0])) === 1;
}

function is_empty_import_row(array $row): bool
{
    foreach ($row as $value) {
        if (trim((string) $value) !== '') {
            return false;
        }
    }

    return true;
}

function detect_berugenjang_population_report_layout(array $rows): ?array
{
    foreach ($rows as $index => $row) {
        if (!is_array($row) || !is_berugenjang_population_report_header($row)) {
            continue;
        }

        return [
            'profile' => 'berugenjang_population_report',
            'header_index' => $index,
            'headers' => array_values($row),
            'mapping' => berugenjang_population_report_mapping(),
            'fields' => array_values(array_unique(array_filter(berugenjang_population_report_mapping(), static function ($field): bool {
                return $field !== 'skip';
            }))),
        ];
    }

    return null;
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

function import_mapping_for_headers(array $headers): array
{
    $mapping = [];
    $canon = canonical_map();

    foreach ($headers as $idx => $header) {
        $mapping[$idx] = $canon[normalize_header($header)] ?? 'skip';
    }

    return $mapping;
}

function build_import_record(array $row, array $mapping): array
{
    $record = [];
    $allowedFields = importable_mapping_fields();

    foreach ($row as $idx => $cell) {
        $field = $mapping[$idx] ?? 'skip';
        if (!is_string($field) || $field === 'skip' || !in_array($field, $allowedFields, true)) {
            continue;
        }

        $value = is_string($cell) ? trim($cell) : $cell;
        if ($field === 'rt_rw') {
            $record = array_merge($record, parse_rt_rw_value($value));
        } elseif ($field === 'ttl') {
            $record = array_merge($record, parse_ttl_value($value));
        } else {
            $record[$field] = $value;
        }
    }

    return $record;
}

function normalize_nik_value($value): string
{
    return preg_replace('/\D+/', '', trim((string) $value)) ?? '';
}

function is_header_like_row(array $row): bool
{
    $canon = canonical_map();
    $matches = 0;

    foreach ($row as $cell) {
        $field = $canon[normalize_header($cell)] ?? null;
        if ($field !== null && $field !== 'skip') {
            $matches++;
        }
    }

    return $matches >= 3;
}

function is_import_header_row(array $row, array $layout): bool
{
    if (($layout['profile'] ?? null) === 'berugenjang_population_report') {
        return is_berugenjang_population_report_header($row);
    }

    return is_header_like_row($row);
}

function is_import_non_data_row(array $row, array $layout): bool
{
    if (($layout['profile'] ?? null) !== 'berugenjang_population_report') {
        return false;
    }

    return is_empty_import_row($row) || is_berugenjang_section_title_row($row);
}

/**
 * Finds the one supported resident-data header row. A source layout must have
 * the NIK and Anggota Keluarga/Nama columns plus at least four recognised
 * fields; otherwise it is rejected before any data can be written.
 */
function detect_import_layout(array $rows): array
{
    $berugenjangLayout = detect_berugenjang_population_report_layout($rows);
    if ($berugenjangLayout !== null) {
        return $berugenjangLayout;
    }

    $best = null;

    foreach ($rows as $index => $row) {
        if (!is_array($row)) {
            continue;
        }

        $mapping = import_mapping_for_headers($row);
        $fields = array_values(array_unique(array_filter($mapping, static function ($field): bool {
            return $field !== 'skip';
        })));

        if (!in_array('nik', $fields, true) || !in_array('nama', $fields, true) || count($fields) < 4) {
            continue;
        }

        $candidate = [
            'profile' => 'generic',
            'header_index' => $index,
            'headers' => array_values($row),
            'mapping' => $mapping,
            'fields' => $fields,
        ];

        if ($best === null || count($candidate['fields']) > count($best['fields'])) {
            $best = $candidate;
        }
    }

    if ($best === null) {
        throw new InvalidArgumentException('Format header tidak dikenali. File harus memuat kolom NIK dan ANGGOTA KELUARGA/Nama.');
    }

    $hasDataRow = false;
    for ($i = $best['header_index'] + 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (!is_array($row) || is_import_header_row($row, $best) || is_import_non_data_row($row, $best)) {
            continue;
        }

        $record = build_import_record($row, $best['mapping']);
        if (strlen(normalize_nik_value($record['nik'] ?? '')) === 16 && trim((string) ($record['nama'] ?? '')) !== '') {
            $hasDataRow = true;
            break;
        }
    }

    if (!$hasDataRow) {
        throw new InvalidArgumentException('Tidak ditemukan baris data valid setelah header. Periksa susunan header Excel dan nilai NIK.');
    }

    return $best;
}

function parse_rt_rw_value($value): array
{
    $value = trim((string) $value);
    if (!preg_match('/^(?:rt\.?\s*)?(\d+)\s*\/\s*(?:rw\.?\s*)?(\d+)$/i', $value, $matches)) {
        return [];
    }

    return ['rt' => $matches[1], 'rw' => $matches[2]];
}

function parse_ttl_value($value): array
{
    $value = trim((string) $value);
    if ($value === '') {
        return [];
    }

    $parts = preg_split('/\s*,\s*/', $value, 2);
    if (count($parts) !== 2) {
        return ['tempat_lahir' => $value];
    }

    $tempat = trim($parts[0]);
    $tanggal = parse_date_value(trim($parts[1]));
    $result = [];
    if ($tempat !== '') {
        $result['tempat_lahir'] = $tempat;
    }
    if ($tanggal !== null) {
        $result['tgl_lahir'] = $tanggal;
    }

    return $result;
}

function parse_date_value($value)
{
    if ($value === null || $value === '') {
        return null;
    }
    if (is_numeric($value)) {
        try {
            if (class_exists('\\PhpOffice\\PhpSpreadsheet\\Shared\\Date')) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }

            // Excel's 1900 date system includes a non-existent leap day. Using
            // 1899-12-30 preserves the same serial-date result without relying
            // on PhpSpreadsheet being available at runtime.
            $days = (int) floor((float) $value);
            return (new DateTimeImmutable('1899-12-30'))->modify(($days >= 0 ? '+' : '') . $days . ' days')->format('Y-m-d');
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
