<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../koneksi.php';
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($koneksi) {
    // charset handled centrally in koneksi.php
}

function tableExists(mysqli $koneksi, string $namaTable): bool
{
    $check = mysqli_query($koneksi, "SHOW TABLES LIKE '" . mysqli_real_escape_string($koneksi, $namaTable) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function columnExists(mysqli $koneksi, string $namaTable, string $namaKolom): bool
{
    $check = mysqli_query($koneksi, "SHOW COLUMNS FROM `" . mysqli_real_escape_string($koneksi, $namaTable) . "` LIKE '" . mysqli_real_escape_string($koneksi, $namaKolom) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function findExistingTable(mysqli $koneksi, array $candidates): ?string
{
    foreach ($candidates as $table) {
        if (tableExists($koneksi, $table))
            return $table;
    }
    return null;
}

function chooseColumnExpr(mysqli $koneksi, string $table, array $candidates, string $default = ''): string
{
    foreach ($candidates as $column) {
        if (columnExists($koneksi, $table, $column))
            return "`$column`";
    }
    return "'" . mysqli_real_escape_string($koneksi, $default) . "'";
}

function chooseNomorSuratExpr(mysqli $koneksi, string $table): string
{
    $candidates = ['nomor_surat', 'kode_surat', 'no_surat', 'surat_nomor', 'nomor'];
    $parts = [];

    foreach ($candidates as $column) {
        if (columnExists($koneksi, $table, $column)) {
            $parts[] = "NULLIF(TRIM(`$column`), '')";
        }
    }

    if (empty($parts)) {
        return "''";
    }

    return "TRIM(COALESCE(" . implode(', ', $parts) . ", ''))";
}

function normalizeNomorSuratValue($value): string
{
    if ($value === null) {
        return '';
    }

    if (is_int($value) || is_float($value)) {
        return $value == 0 ? '' : (string) $value;
    }

    $trimmed = trim((string) $value);
    if ($trimmed === '' || $trimmed === '0') {
        return '';
    }

    return $trimmed;
}

function chooseColumnName(mysqli $koneksi, string $table, array $candidates): string
{
    foreach ($candidates as $column) {
        if (columnExists($koneksi, $table, $column))
            return $column;
    }
    return '';
}

function cleanUtf8(?string $string): string
{
    if (empty($string))
        return '';
    if (mb_detect_encoding($string, 'UTF-8', true) && strpos($string, 'â') !== false) {
        return utf8_encode(utf8_decode($string));
    }
    return $string;
}

// Filter Bulan dan Tahun dari URL
$filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$sourceTables = [
    [
        'label' => 'Surat Garapan Sawah',
        'candidates' => ['tb_surat_garapan', 'surat_garapan'],
        'name' => ['nama_penggarap', 'nama_pemohon', 'nama_warga'],
        'tujuan' => ['pekerjaan', 'keperluan', 'perihal'],
        'keterangan' => ['keperluan', 'keterangan', 'keterangan_lain'],
    ],
    [
        'label' => 'Surat Ahli Waris',
        'candidates' => ['tb_surat_waris', 'surat_waris'],
        'name' => ['nama_almarhum', 'nama_pasangan', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan', 'perihal'],
        'keterangan' => ['keperluan', 'keterangan', 'keterangan_lain'],
    ],
    [
        'label' => 'Surat Undangan',
        'candidates' => ['tb_surat_undangan', 'surat_undangan'],
        // PERBAIKAN: Gunakan nama pengundang / pejabat / pemohon
        'name' => ['nama_pengundang', 'nama_pemohon', 'pengundang', 'nama_pejabat'],
        // PERBAIKAN: Gunakan kolom tujuan/penerima surat undangan
        'tujuan' => ['tujuan_surat', 'kepada', 'tujuan', 'penerima', 'acara', 'perihal'],
        'keterangan' => ['keterangan', 'keperluan'],
    ],
    [
        'label' => 'Surat Kelahiran',
        'candidates' => ['tb_surat_kelahiran', 'surat_kelahiran'],
        'name' => ['nama_bayi', 'nama_kepala_keluarga', 'nama_pelapor'],
        'tujuan' => ['nama_kepala_keluarga', 'nomor_kk', 'keperluan'],
        'keterangan' => ['keterangan', 'keterangan_lain'],
    ],
    [
        'label' => 'Surat Kematian',
        'candidates' => ['tb_surat_kematian', 'surat_kematian'],
        'name' => ['nama_jenazah', 'nama_pelapor'],
        // PERBAIKAN: Gunakan keperluan / keterangan alih-alih nama pelapor
        'tujuan' => ['keperluan', 'keterangan', 'tujuan'],
        'keterangan' => ['keterangan', 'keterangan_lain'],
    ],
    [
        'label' => 'Surat Keterangan / Pengantar',
        'candidates' => ['tb_surat_pengantar', 'surat_pengantar'],
        'name' => ['nama_pemohon', 'nama_warga'],
        'tujuan' => ['keperluan', 'keterangan_lain', 'keterangan'],
        'keterangan' => ['keterangan_lain', 'keterangan', 'keperluan'],
    ],
    [
        'label' => 'Surat Domisili',
        'candidates' => ['tb_surat_domisili', 'surat_domisili'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan_lain'],
        'keterangan' => ['keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'Surat Pengantar Dukcapil',
        'candidates' => ['tb_surat_pengantar_dukcapil', 'tb_surat_dukcapil', 'surat_pengantar_dukcapil', 'surat_dukcapil'],
        'name' => ['nama_pemohon', 'created_by'],
        'tujuan' => ['jenis_dikirim', 'banyaknya'],
        'keterangan' => ['keterangan'],
    ],
    [
        'label' => 'SKTM Ibu Hamil',
        'candidates' => ['tb_sktm_bumil', 'sktm_bumil'],
        'name' => ['nama_pemohon', 'nama_warga'],
        'tujuan' => ['keperluan', 'keterangan_lain'],
        'keterangan' => ['keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'SKTM Pembebasan Rawat',
        'candidates' => ['tb_sktm_rawat', 'sktm_rawat', 'tb_sktm_pasien', 'sktm_pasien'],
        'name' => ['nama_pemohon', 'nama_warga'],
        'tujuan' => ['rumah_sakit_tujuan', 'keperluan'],
        'keterangan' => ['keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'SKTM KIS',
        'candidates' => ['tb_sktm_kis', 'sktm_kis'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan'],
        'keterangan' => ['keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'SKTM KIP',
        'candidates' => ['tb_sktm_kip', 'sktm_kip'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan'],
        'keterangan' => ['keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'SKTM Stunting',
        'candidates' => ['tb_sktm_stunting', 'sktm_stunting'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan'],
        'keterangan' => ['keterangan_lain', 'keterangan'],
    ],
];

$suratRows = [];
foreach ($sourceTables as $config) {
    $table = findExistingTable($koneksi, $config['candidates']);
    if (!$table)
        continue;

    $jenis = mysqli_real_escape_string($koneksi, $config['label']);
    $nomor = chooseNomorSuratExpr($koneksi, $table);
    $tanggal = chooseColumnExpr($koneksi, $table, ['tanggal_surat'], '');
    $nama = chooseColumnExpr($koneksi, $table, $config['name'], '');
    $tujuan = chooseColumnExpr($koneksi, $table, $config['tujuan'], '');
    $idColumnName = chooseColumnName($koneksi, $table, ['id', 'id_surat', 'id_kelahiran', 'id_kematian', 'id_penggarap', 'id_sktm', 'id_undangan', 'id_pasien', 'id_waris']);
    $idExpr = $idColumnName ? "`" . $idColumnName . "`" : "0";

    $query = "SELECT
        '" . $jenis . "' AS jenis_surat,
        '" . mysqli_real_escape_string($koneksi, $table) . "' AS sumber_tabel,
        " . $nomor . " AS nomor_surat,
        " . $tanggal . " AS tanggal_surat,
        " . $nama . " AS nama_pemohon,
        " . $tujuan . " AS tujuan,
        '' AS keterangan,
        " . $idExpr . " AS id_value,
        '" . mysqli_real_escape_string($koneksi, $idColumnName) . "' AS id_column
        FROM `" . mysqli_real_escape_string($koneksi, $table) . "`";

    $result = mysqli_query($koneksi, $query);
    if ($result) {
        while ($data = mysqli_fetch_assoc($result)) {
            $suratRows[] = $data;
        }
    }
}

usort($suratRows, function ($a, $b) {
    $left = $a['tanggal_surat'] ?? '';
    $right = $b['tanggal_surat'] ?? '';
    $cmp = strcmp($left, $right);
    if ($cmp !== 0) {
        return $cmp;
    }

    return strcmp(($a['nomor_surat'] ?? ''), ($b['nomor_surat'] ?? ''));
});

// Filter Data
$filteredRows = [];
foreach ($suratRows as $row) {
    $tgl = $row['tanggal_surat'];
    $ts = strtotime($tgl);
    if ($ts !== false && $tgl != '') {
        $m = date('m', $ts);
        $y = date('Y', $ts);
        if ($m === sprintf('%02d', $filterBulan) && $y === $filterTahun) {
            $filteredRows[] = $row;
        }
    }
}

$namaBulanList = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
$namaBulanTerpilih = $namaBulanList[sprintf('%02d', $filterBulan)] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/print-preview-responsive.css">
    <title>Cetak Agenda Surat Keluar - <?php echo $namaBulanTerpilih . ' ' . $filterTahun; ?></title>
    <style>
        /* CSS MURNI DOKUMEN CETAK LANDSCAPE */
        @page {
            size: A4 landscape;
            margin: 10mm 12mm 10mm 12mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
            font-size: 10pt;
        }

        /* TOMBOL CETAK & NAVIGASI */
        .btn-bar {
            background: #222;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-bar button {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-bar button:hover {
            background: #218838;
        }

        /* CONTAINER KOP HEADER DOKUMEN */
        .header-container {
            position: relative;
            margin-bottom: 10px;
        }

        .header {
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header h3 {
            margin: 4px 0;
            font-size: 12pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0 0 0;
            font-style: italic;
            font-size: 10pt;
        }

        /* TABEL AGENDA */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 4px 5px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
            text-transform: uppercase;
        }

        /* STYLE INPUT KETIKAN / EDITABLE */
        .input-editable {
            width: 100%;
            box-sizing: border-box;
            border: 1px dashed #ccc;
            background: #fff8dc;
            font-family: inherit;
            font-size: 9pt;
            padding: 2px 4px;
            outline: none;
            border-radius: 2px;
        }

        .input-editable:focus {
            background: #fff;
            border: 1px solid #007bff;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .input-tanggal-ttd {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 10pt;
            text-align: center;
            width: 100%;
            outline: none;
        }

        /* MATIKAN HEAD REPEAT DI HALAMAN BARU */
        table.data-table thead {
            display: table-row-group;
        }

        /* TANDA TANGAN */
        .footer-ttd {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .footer-ttd table {
            width: 100%;
            border: none;
        }

        .footer-ttd td {
            border: none !important;
            text-align: center;
            vertical-align: top;
            font-size: 10pt;
        }

        /* SEMBUNYIKAN UI LAYAR SAAT DIPRINT */
        @media print {
            .btn-bar {
                display: none !important;
            }

            /* UBAH INPUT MENJADI TEKS POLOS SAAT CETAK */
            .input-editable {
                border: none !important;
                background: transparent !important;
                padding: 0 !important;
            }

            .input-editable:placeholder-shown {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Tombol Cetak Manual -->
    <div class="btn-bar">
        <button onclick="window.print()">🖨️ Cetak Dokumen Ini</button>
    </div>

    <!-- KOP AGENDA -->
    <div class="header-container">
        <div class="header">
            <h2>BUKU AGENDA SURAT KELUAR</h2>
            <h3>PEMERINTAH DESA BERUGENJANG</h3>
            <p>Periode: Bulan <?php echo $namaBulanTerpilih . ' ' . $filterTahun; ?></p>
        </div>
    </div>

    <!-- MODEL A.7 -->
    <div
        style="text-align: right; font-weight: bold; font-family: 'Times New Roman', Times, serif; font-size: 11pt; margin-bottom: 3px;">
        Model A.7
    </div>

    <!-- TABEL DATA AGENDA -->
    <table class="data-table">
        <thead>
            <!-- BARIS 1 -->
            <tr>
                <th rowspan="2" style="width: 4%;">NO</th>
                <th colspan="5">SURAT KELUAR</th>
                <th rowspan="2" style="width: 17%;">KETERANGAN</th>
            </tr>
            <!-- BARIS 2 -->
            <tr>
                <th style="width: 17%;">ISI SINGKAT</th>
                <th style="width: 16%;">NAMA PEMOHON</th>
                <th style="width: 11%;">TANGGAL SURAT</th>
                <th style="width: 18%;">NOMOR SURAT</th>
                <th style="width: 17%;">TUJUAN</th>
            </tr>
            <!-- BARIS 3 -->
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($filteredRows)): ?>
                <?php $no = 1; ?>
                <?php foreach ($filteredRows as $row): ?>
                    <?php
                    $tanggal = '-';
                    if (!empty($row['tanggal_surat']) && strtotime($row['tanggal_surat']) !== false) {
                        $tanggal = date('d/m/Y', strtotime($row['tanggal_surat']));
                    }
                    ?>
                    <tr>
                        <td style="text-align: center; font-weight: bold;"><?php echo $no++; ?></td>

                        <!-- ISI SINGKAT (EDITABLE) -->
                        <td>
                            <input type="text" class="input-editable font-bold"
                                value="<?php echo htmlspecialchars(cleanUtf8($row['jenis_surat'])); ?>">
                        </td>

                        <!-- NAMA PEMOHON (EDITABLE) -->
                        <td>
                            <input type="text" class="input-editable"
                                value="<?php echo htmlspecialchars(cleanUtf8($row['nama_pemohon'] ?: '')); ?>" placeholder="-">
                        </td>

                        <!-- TANGGAL SURAT (EDITABLE) -->
                        <td>
                            <input type="text" class="input-editable text-center"
                                value="<?php echo htmlspecialchars($tanggal); ?>">
                        </td>

                        <!-- NOMOR SURAT (EDITABLE) -->
                        <td>
                            <input type="text" class="input-editable"
                                value="<?php echo htmlspecialchars(cleanUtf8(normalizeNomorSuratValue($row['nomor_surat'] ?? '') ?: '')); ?>"
                                placeholder="-">
                        </td>

                        <!-- TUJUAN (EDITABLE) -->
                        <td>
                            <input type="text" class="input-editable"
                                value="<?php echo htmlspecialchars(cleanUtf8($row['tujuan'] ?: '')); ?>" placeholder="-">
                        </td>

                        <!-- KETERANGAN (EDITABLE) -->
                        <td>
                            <input type="text" class="input-editable" value="" placeholder="Ketik keterangan...">
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px;">
                        Tidak ada data surat keluar pada bulan <?php echo $namaBulanTerpilih . ' ' . $filterTahun; ?>.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="footer-ttd">
        <table>
            <tr>
                <td style="width: 50%;">
                    <p style="margin-bottom: 50px;">
                        Mengetahui,<br>
                        <strong>Kepala Desa Berugenjang</strong>
                    </p>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        KISWO, S.E
                    </p>
                </td>
                <td style="width: 50%;">
                    <p style="margin-bottom: 50px;">
                        <input type="text" class="input-tanggal-ttd"
                            value="Berugenjang, ...................................."><br>
                        <strong>Sekretaris Desa Berugenjang</strong>
                    </p>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        PUJIONO
                    </p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
