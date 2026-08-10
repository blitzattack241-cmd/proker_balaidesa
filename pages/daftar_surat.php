<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4 rounded-3 shadow-sm'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
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

function cleanUtf8(?string $string): string
{
    if (empty($string))
        return '';
    if (mb_detect_encoding($string, 'UTF-8', true) && strpos($string, 'â') !== false) {
        return utf8_encode(utf8_decode($string));
    }
    return $string;
}

// Filter Bulan dan Tahun
$filterBulan = isset($_GET['bulan']) && $_GET['bulan'] !== '' ? (int) $_GET['bulan'] : null;
$filterTahun = isset($_GET['tahun']) && $_GET['tahun'] !== '' ? (int) $_GET['tahun'] : null;

$sourceTables = [
    [
        'label' => 'Surat Garapan Sawah',
        'candidates' => ['tb_surat_garapan', 'surat_garapan'],
        'name' => ['nama_penggarap', 'nama_pemohon', 'nama_warga'],
        // PERBAIKAN: Hapus 'pekerjaan' dari kandidat tujuan
        'tujuan' => ['keperluan', 'perihal', 'keterangan'],
    ],
    [
        'label' => 'Surat Ahli Waris',
        'candidates' => ['tb_surat_waris', 'surat_waris'],
        'name' => ['nama_almarhum', 'nama_pasangan', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan', 'perihal'],
    ],
    [
        'label' => 'Surat Undangan',
        'candidates' => ['tb_surat_undangan', 'surat_undangan'],
        // PERBAIKAN: Gunakan pengirim/pengundang (misal nama_pejabat / pengundang)
        'name' => ['nama_pengundang', 'nama_pemohon', 'pengundang', 'nama_pejabat'],
        // PERBAIKAN: Gunakan kolom tujuan/penerima surat undangan
        'tujuan' => ['tujuan_surat', 'kepada', 'tujuan', 'penerima', 'acara', 'perihal'],
    ],
    [
        'label' => 'Surat Kelahiran',
        'candidates' => ['tb_surat_kelahiran', 'surat_kelahiran'],
        'name' => ['nama_bayi', 'nama_pelapor', 'nama_kepala_keluarga'],
        // Hapus 'nomor_kk' dari kandidat agar tidak mengambil angka KK
        'tujuan' => ['keperluan', 'keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'Surat Kematian',
        'candidates' => ['tb_surat_kematian', 'surat_kematian'],
        'name' => ['nama_jenazah', 'nama_pelapor'],
        // PERBAIKAN: Prioritaskan keperluan / keterangan / instansi tujuan
        'tujuan' => ['keperluan', 'keterangan', 'tujuan'],
    ],
    [
        'label' => 'Surat Keterangan / Pengantar',
        'candidates' => ['tb_surat_pengantar', 'surat_pengantar'],
        'name' => ['nama_penduduk', 'nama_pemohon', 'nama_warga'],
        'tujuan' => ['keperluan', 'keterangan_lain', 'keterangan'],
    ],
    [
        'label' => 'Surat Domisili',
        'candidates' => ['tb_surat_domisili', 'surat_domisili'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan_lain'],
    ],
    [
        'label' => 'Surat Pengantar Dukcapil',
        'candidates' => ['tb_surat_pengantar_dukcapil', 'tb_surat_dukcapil', 'surat_pengantar_dukcapil', 'surat_dukcapil'],
        'name' => ['nama_pemohon', 'created_by'],
        'tujuan' => ['jenis_dikirim', 'banyaknya'],
    ],
    [
        'label' => 'SKTM Ibu Hamil',
        'candidates' => ['tb_sktm_bumil', 'sktm_bumil'],
        'name' => ['nama_pemohon', 'nama_warga'],
        'tujuan' => ['keperluan', 'keterangan_lain'],
    ],
    [
        'label' => 'SKTM Pembebasan Rawat',
        'candidates' => ['tb_sktm_rawat', 'sktm_rawat', 'tb_sktm_pasien', 'sktm_pasien'],
        'name' => ['nama_pemohon', 'nama_warga'],
        'tujuan' => ['rumah_sakit_tujuan', 'keperluan'],
    ],
    [
        'label' => 'SKTM KIS',
        'candidates' => ['tb_sktm_kis', 'sktm_kis'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan'],
    ],
    [
        'label' => 'SKTM KIP',
        'candidates' => ['tb_sktm_kip', 'sktm_kip'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan'],
    ],
    [
        'label' => 'SKTM Stunting',
        'candidates' => ['tb_sktm_stunting', 'sktm_stunting'],
        'name' => ['nama_warga', 'nama_pemohon'],
        'tujuan' => ['keperluan', 'keterangan'],
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

    $query = "SELECT
        '" . $jenis . "' AS jenis_surat,
        " . $nomor . " AS nomor_surat,
        " . $tanggal . " AS tanggal_surat,
        " . $nama . " AS nama_pemohon,
        " . $tujuan . " AS tujuan
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

// Filter Data Berdasarkan Bulan & Tahun
$filteredRows = [];
foreach ($suratRows as $row) {
    $tgl = $row['tanggal_surat'];
    $ts = strtotime($tgl);
    if ($ts !== false && $tgl != '') {
        $m = date('m', $ts);
        $y = date('Y', $ts);

        $matchBulan = $filterBulan === null || $m === sprintf('%02d', $filterBulan);
        $matchTahun = $filterTahun === null || $y === (string) $filterTahun;

        if ($matchBulan && $matchTahun) {
            $filteredRows[] = $row;
        }
    }
}
$totalSuratSemua = count($suratRows);
$totalSuratFilter = count($filteredRows);

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
$namaBulanTerpilih = $filterBulan !== null ? ($namaBulanList[sprintf('%02d', $filterBulan)] ?? '') : '';
?>

<style>
    .green-banner {
        background: linear-gradient(135deg, #0b8a4f 0%, #086b3d 100%);
        border-radius: 16px;
        padding: 1.8rem 2rem;
        color: #ffffff;
        margin-bottom: 1.5rem;
    }

    .banner-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .banner-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.875rem;
        margin: 0;
    }

    .main-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .table-agenda {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }

    .table-agenda thead th {
        background-color: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 10px;
        border: 1px solid #cbd5e1;
        text-align: center;
    }

    .table-agenda tbody td {
        padding: 10px;
        border: 1px solid #cbd5e1;
        font-size: 0.85rem;
        color: #334155;
        vertical-align: top;
        word-wrap: break-word;
    }

    /* Style tambahan untuk kolom Keterangan yang bisa diedit langsung */
    .editable-ket {
        background-color: #fffde7;
        outline: none;
        cursor: text;
        transition: background-color 0.2s;
    }

    .editable-ket:focus {
        background-color: #ffffff;
        box-shadow: inset 0 0 0 2px #0b8a4f;
        border-radius: 4px;
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header Banner -->
    <div class="green-banner d-flex justify-content-between align-items-center">
        <div>
            <h1 class="banner-title">Buku Agenda Surat Keluar</h1>
            <p class="banner-subtitle">Pengelolaan dan cetak rekapitulasi surat keluar Desa Berugenjang.</p>
        </div>
        <div>
            <!-- Link Langsung Buka Cetak di Tab Baru -->
            <a href="pages/cetak.php?bulan=<?php echo $filterBulan; ?>&tahun=<?php echo $filterTahun; ?>"
                target="_blank" class="btn btn-light rounded-3 fw-semibold px-3 py-2 text-dark shadow-sm me-2">
                <i class="fas fa-print me-1"></i> Cetak Rekap Bulanan
            </a>
            <a href="index.php?page=dashboard" class="btn btn-outline-light rounded-3 fw-semibold px-3 py-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Bulan & Tahun -->
    <div class="main-card mb-4">
        <form method="GET" action="index.php" class="row g-3 align-items-center">
            <input type="hidden" name="page" value="<?php echo $_GET['page'] ?? 'surat-keluar'; ?>">

            <div class="col-md-4">
                <label class="form-label fw-bold text-secondary fs-7 mb-1">PILIH BULAN</label>
                <select name="bulan" class="form-select">
                    <option value="" <?php echo ($filterBulan === null) ? 'selected' : ''; ?>>Semua Bulan</option>
                    <?php foreach ($namaBulanList as $key => $val): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($filterBulan !== null && sprintf('%02d', $filterBulan) === $key) ? 'selected' : ''; ?>>
                            <?php echo $val; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold text-secondary fs-7 mb-1">PILIH TAHUN</label>
                <select name="tahun" class="form-select">
                    <option value="" <?php echo ($filterTahun === null) ? 'selected' : ''; ?>>Semua Tahun</option>
                    <?php
                    $thnSekarang = date('Y');
                    for ($t = $thnSekarang; $t >= $thnSekarang - 5; $t--):
                        ?>
                        <option value="<?php echo $t; ?>" <?php echo ($filterTahun !== null && $filterTahun == $t) ? 'selected' : ''; ?>>
                            <?php echo $t; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-5 d-flex align-items-end gap-2" style="height: 58px;">
                <button type="submit" class="btn btn-success px-4 fw-semibold">
                    <i class="fas fa-filter me-1"></i> Tampilkan
                </button>
                <span class="badge bg-primary fs-6 py-2 px-3">
                    <?php if ($filterBulan !== null || $filterTahun !== null): ?>
                        Menampilkan: <?php echo $totalSuratFilter; ?> dari <?php echo $totalSuratSemua; ?> Surat
                    <?php else: ?>
                        Total: <?php echo $totalSuratSemua; ?> Surat
                    <?php endif; ?>
                </span>
            </div>
        </form>
    </div>

    <!-- TABEL UTAMA SISI DASHBOARD -->
    <div class="main-card">
        <div class="table-responsive">
            <table class="table table-agenda align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 4%;">NO</th>
                        <th style="width: 18%;">ISI SINGKAT</th>
                        <th style="width: 16%;">NAMA PEMOHON</th>
                        <th style="width: 11%;">TANGGAL SURAT</th>
                        <th style="width: 17%;">NOMOR SURAT</th>
                        <th style="width: 17%;">TUJUAN</th>
                        <th style="width: 17%;">KETERANGAN</th>
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
                                <td class="text-center fw-bold"><?php echo $no++; ?></td>
                                <td>
                                    <strong
                                        class="text-dark"><?php echo htmlspecialchars(cleanUtf8($row['jenis_surat'])); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars(cleanUtf8($row['nama_pemohon'] ?: '-')); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($tanggal); ?></td>
                                <td><?php echo htmlspecialchars(cleanUtf8(normalizeNomorSuratValue($row['nomor_surat'] ?? '') ?: '-')); ?>
                                </td>
                                <td><?php echo htmlspecialchars(cleanUtf8($row['tujuan'] ?: '-')); ?></td>
                                <!-- Kolom Keterangan dikosongkan secara default & bisa diketik/edited secara langsung -->
                                <td contenteditable="true" class="editable-ket"
                                    title="Klik di sini untuk mengisi keterangan manual jika diperlukan"></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Tidak ada data surat keluar pada bulan
                                <strong><?php echo $namaBulanTerpilih . ' ' . $filterTahun; ?></strong>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>