<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../koneksi.php';

if (!$koneksi) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit();
}

// Helper: Format Normalisasi Status Perkawinan (Diperbarui)
function formatStatusPerkawinan($status)
{
    $val = strtolower(trim((string) $status));
    if (empty($val))
        return 'Belum Kawin';

    if (strpos($val, 'janda') !== false || strpos($val, 'duda') !== false) {
        return 'Janda / Duda';
    } elseif (strpos($val, 'cerai tercatat') !== false || strpos($val, 'tercatat') !== false) {
        return 'Cerai Tercatat';
    } elseif (strpos($val, 'cerai mati') !== false) {
        return 'Cerai Mati';
    } elseif (strpos($val, 'cerai hidup') !== false) {
        return 'Cerai Hidup';
    } elseif (strpos($val, 'cerai') !== false) {
        return 'Cerai';
    } elseif (strpos($val, 'belum') !== false) {
        return 'Belum Kawin';
    } elseif (strpos($val, 'kawin') !== false || strpos($val, 'nikah') !== false) {
        return 'Kawin';
    }
    return 'Belum Kawin';
}

// Helper: Parse tempat_tgl_lahir - Handle multiple date formats
function extractTglLahir($tempat_tgl_lahir)
{
    if (!$tempat_tgl_lahir)
        return '';
    $str = trim($tempat_tgl_lahir);

    // 1. Ekstrak format YYYY-MM-DD
    if (preg_match('/(\d{4}-\d{2}-\d{2})/', $str, $matches)) {
        return $matches[1];
    }

    // 2. Ekstrak format DD-MM-YYYY -> YYYY-MM-DD
    if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $str, $matches)) {
        return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
    }

    // 3. Ekstrak format DD/MM/YYYY -> YYYY-MM-DD
    if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $str, $matches)) {
        return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
    }

    return '';
}

// Helper: Extract tempat lahir dengan menghapus pola tanggal dari string
function extractTempatLahir($tempat_tgl_lahir)
{
    if (!$tempat_tgl_lahir)
        return '';

    $str = trim($tempat_tgl_lahir);

    if (strpos($str, ',') !== false) {
        $parts = explode(',', $str);
        return trim($parts[0]);
    }

    $cleanStr = preg_replace('/\b(\d{4}-\d{2}-\d{2}|\d{2}-\d{2}-\d{4}|\d{2}\/\d{2}\/\d{4})\b/', '', $str);

    return trim($cleanStr);
}

// Helper: Hitung umur dari tanggal lahir
function calculateAge($birthDate)
{
    if (!$birthDate)
        return 0;
    try {
        $birthDateTime = new DateTime($birthDate);
        $today = new DateTime();
        $age = $today->diff($birthDateTime)->y;
        return (int) max(0, $age);
    } catch (Exception $e) {
        return 0;
    }
}

// 1. Jika melakukan pencarian dinamis (autocomplete select2)
$searchTerm = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $searchTerm = trim($_GET['search']);
} elseif (isset($_GET['q']) && $_GET['q'] !== '') {
    $searchTerm = trim($_GET['q']);
}

if ($searchTerm !== '') {
    $searchPattern = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $searchTerm);
    $search = mysqli_real_escape_string($koneksi, '%' . $searchPattern . '%');
    $identifierSearch = str_replace(['_', '*'], '', $searchTerm);
    $identifierPattern = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $identifierSearch);
    $identifierLike = mysqli_real_escape_string($koneksi, '%' . $identifierPattern . '%');
    $identifierWhere = $identifierSearch !== ''
        ? " OR REPLACE(REPLACE(nik, '_', ''), '*', '') LIKE '$identifierLike'
            OR REPLACE(REPLACE(no_kk, '_', ''), '*', '') LIKE '$identifierLike'"
        : '';

    // Keep autocomplete results consistent with the Residents-page search.
    $query = "SELECT id, nik, no_kk, nama, jenis_kelamin, tempat_tgl_lahir, tempat_lahir, tgl_lahir, pekerjaan, alamat, rt, rw, status_pernikahan, status_keluarga 
              FROM tb_penduduk 
              WHERE nama LIKE '$search' OR nik LIKE '$search' OR no_kk LIKE '$search'
                    OR pekerjaan LIKE '$search' OR kepala_kk LIKE '$search' OR suku LIKE '$search'$identifierWhere
              ORDER BY nama ASC
              LIMIT 15";

    $result = mysqli_query($koneksi, $query);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $alamat_lengkap = trim(($row['alamat'] ?? '') . ' ' . ($row['rt'] ? 'RT ' . sprintf("%03d", $row['rt']) : '') . ' ' . ($row['rw'] ? '/ RW ' . sprintf("%03d", $row['rw']) : ''));

        // Extract tanggal lahir dan tempat lahir
        $tgl_lahir = $row['tgl_lahir'] ?: extractTglLahir($row['tempat_tgl_lahir']);
        $tempat_lahir = $row['tempat_lahir'] ?: extractTempatLahir($row['tempat_tgl_lahir']);
        $umur = calculateAge($tgl_lahir);

        $data[] = [
            'id' => (string) $row['id'],
            'text' => $row['nama'] . " | NIK: " . ltrim((string) $row['nik'], '_*') . " | KK: " . (ltrim((string) ($row['no_kk'] ?? ''), '_*') ?: '-'),
            'nik' => ltrim((string) $row['nik'], '_*'),
            'no_kk' => ltrim((string) ($row['no_kk'] ?? ''), '_*'),
            'nama' => $row['nama'],
            'tempat_tgl_lahir' => $row['tempat_tgl_lahir'],
            'tgl_lahir' => $tgl_lahir,
            'tanggal_lahir' => $tgl_lahir,
            'tempat_lahir' => $tempat_lahir,
            'status_pernikahan' => formatStatusPerkawinan($row['status_pernikahan'] ?? ''),
            'status_keluarga' => $row['status_keluarga'] ?? '',
            'umur' => (int) $umur,
            'pekerjaan' => $row['pekerjaan'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'alamat_lengkap' => $alamat_lengkap,
            'alamat' => $row['alamat'] ?? '',
            'rt' => $row['rt'] ?? '',
            'rw' => $row['rw'] ?? '',
            '_debug_tgl' => [
                'raw' => $row['tempat_tgl_lahir'],
                'parsed_tgl' => $tgl_lahir,
                'parsed_tempat' => $tempat_lahir,
                'age' => (int) $umur
            ]
        ];
    }

    echo json_encode(['results' => $data]);
    exit();
}

// 2. Jika mengambil detail 1 penduduk berdasarkan ID yang dipilih
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $query = "SELECT * FROM tb_penduduk WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $tgl_lahir = $row['tgl_lahir'] ?: extractTglLahir($row['tempat_tgl_lahir']);
        $tempat_lahir = $row['tempat_lahir'] ?: extractTempatLahir($row['tempat_tgl_lahir']);
        $umur = calculateAge($tgl_lahir);

        $row['tgl_lahir'] = $tgl_lahir;
        $row['tanggal_lahir'] = $tgl_lahir;
        $row['tempat_lahir'] = $tempat_lahir;
        $row['status_pernikahan'] = formatStatusPerkawinan($row['status_pernikahan'] ?? '');
        $row['umur'] = (int) $umur;
        $row['alamat_lengkap'] = trim(($row['alamat'] ?? '') . " RT " . sprintf("%03d", $row['rt']) . " / RW " . sprintf("%03d", $row['rw']));
        echo json_encode(['status' => 'success', 'data' => $row]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

// 3. Test endpoint untuk debug
if (isset($_GET['test'])) {
    $test_nik = $_GET['test'];
    $search = mysqli_real_escape_string($koneksi, $test_nik);
    $query = "SELECT id, nik, nama, tempat_tgl_lahir, status_pernikahan FROM tb_penduduk WHERE nik LIKE '%$search%' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        echo json_encode([
            'raw_data' => $row,
            'tempat_lahir_parsed' => extractTempatLahir($row['tempat_tgl_lahir']),
            'tgl_lahir_parsed' => extractTglLahir($row['tempat_tgl_lahir']),
            'status_pernikahan_parsed' => formatStatusPerkawinan($row['status_pernikahan'])
        ]);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
    exit();
}
?>
