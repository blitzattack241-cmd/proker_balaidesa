<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Akses
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "Akses ditolak!";
    exit;
}

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil ID Surat
$id = $_GET['id'] ?? 0;
$id = (int)$id;

// Cari tahu nama tabel yang tersedia (Dinamis antara tb_surat_kematian atau surat_kematian)
$tableTarget = 'tb_surat_kematian';
$checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kematian'");
if ($checkTable && mysqli_num_rows($checkTable) > 0) {
    $tableTarget = 'surat_kematian';
}

// Ambil data lengkap
$query = mysqli_query($koneksi, "SELECT * FROM `$tableTarget` WHERE id_surat = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data surat kematian tidak ditemukan!");
}

// Modul QR Verifikasi (ACC) - agar setiap surat yang dicetak punya QR sah
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_kematian', $id, $data['nomor_surat'] ?? '');

/**
 * Fungsi untuk me-render kotak karakter khas formulir Capil
 * Ditambahkan atribut contenteditable="true" agar bisa diklik dan diedit langsung di layar browser
 */
function renderBoxes($text, $length) {
    $text = strtoupper((string)$text);
    $html = '<span class="box-container">';
    for ($i = 0; $i < $length; $i++) {
        $char = isset($text[$i]) ? $text[$i] : '';
        $html .= '<span class="char-box" contenteditable="true">' . htmlspecialchars($char) . '</span>';
    }
    $html .= '</span>';
    return $html;
}

// Fungsi pembantu parsing tanggal ke pecahan kotak ddmmyyyy (8 kotak)
function renderDateBoxes($dateString) {
    if(!$dateString) return renderBoxes('', 8);
    $time = strtotime($dateString);
    $d = date('d', $time);
    $m = date('m', $time);
    $y = date('Y', $time);
    return renderBoxes($d . $m . $y, 8);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak F-2.29 Resmi - <?= htmlspecialchars($data['nomor_surat']); ?></title>
    <style>
    /* Desain Struktur Cetak Kertas Standar Dukcapil (F4 / Folio) */
    @page {
        size: folio;
        margin: 0.3cm 0.5cm;
    }

    body {
        font-family: 'Arial', sans-serif;
        font-size: 10.5px;
        color: #000;
        line-height: 1.15;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    /* Navigasi Browser Alert & Tools (Tidak Ikut Tercetak) */
    .no-print-bar {
        background: #e9ecef;
        border-bottom: 2px solid #ced4da;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: system-ui, -apple-system, sans-serif;
    }

    .btn-print {
        padding: 8px 18px;
        background: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-print:hover {
        background: #0b5ed7;
    }

    /* Layout Lembar F-2.29 */
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 10px;
    }

    .header-section {
        width: 100%;
        border-collapse: collapse;
        font-weight: bold;
    }

    .header-section td {
        padding: 1px 0;
    }

    .code-label-top {
        border: 2px solid #000;
        padding: 2px 8px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
    }

    .main-title-block {
        text-align: center;
        margin: 8px 0;
    }

    .main-title-block h1 {
        margin: 0;
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    .main-title-block p {
        margin: 2px 0 0 0;
        font-size: 11px;
        font-weight: bold;
    }

    /* Pembatas Baris Utama & Grid */
    .grid-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
    }

    .grid-table td {
        padding: 2px 3px;
        vertical-align: middle;
    }

    /* Blok Judul Section Hitam Persis Lembar Asli */
    .block-black-header {
        background-color: #000;
        color: #fff;
        font-weight: bold;
        font-size: 10.5px;
        padding: 3px 6px;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* Kontainer Border Sekeliling Data Master */
    .block-border-content {
        border: 2px solid #000;
        border-top: none;
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3px;
    }

    .block-border-content td {
        padding: 3px 4px;
        vertical-align: middle;
        font-size: 10px;
    }

    /* Desain Kotak Karakter F-2.29 */
    .box-container {
        display: inline-block;
        vertical-align: middle;
    }

    .char-box {
        display: inline-block;
        width: 12px;
        height: 14px;
        line-height: 14px;
        border: 1px solid #000;
        border-right: none;
        text-align: center;
        font-family: 'Courier New', monospace;
        font-size: 11px;
        font-weight: bold;
        background: #fff;
        vertical-align: middle;
        outline: none;
    }

    .char-box:last-child {
        border-right: 1px solid #000;
    }

    /* Style focus ketika user mengklik kotak untuk mengedit teks */
    .char-box:focus {
        background-color: #fff3cd;
        border-color: #ffc107;
        color: #000;
    }

    .inline-editable-text {
        display: inline-block;
        min-width: 80px;
        border-bottom: 1px dashed #999;
        outline: none;
        font-weight: bold;
    }

    .inline-editable-text:focus {
        background-color: #fff3cd;
    }

    .choice-text-item {
        margin-right: 10px;
        display: inline-block;
        font-size: 9.5px;
    }

    /* Area Tanda Tangan */
    .signature-container-table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }

    .signature-container-table td {
        text-align: center;
        width: 33%;
        font-size: 10.5px;
        vertical-align: top;
    }

    .space-sign-blank {
        height: 50px;
    }

    @media print {
        .no-print-bar {
            display: none !important;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .char-box {
            border: 1px solid #000;
            border-right: none;
        }

        .char-box:last-child {
            border-right: 1px solid #000;
        }

        .char-box:focus {
            background-color: transparent;
        }
    }
    </style>
</head>

<body>

    <!-- BAR ALAT DI BROWSER (TIDAK IKUT TERCETAK DI KERTAS) -->
    <div class="no-print-bar">
        <div style="color: #333; font-size: 13px;">
            <strong style="color: #dc3545;"><i class="fas fa-edit"></i> Fitur Live-Edit Aktif (F-2.29):</strong>
            Anda bisa langsung merubah isian data/angka di dalam kotak secara langsung sebelum dicetak ke printer.
        </div>
        <button class="btn-print" onclick="window.print();">
            <i class="fas fa-print"></i> Cetak Sekarang (F-2.29)
        </button>
    </div>

    <!-- AREA UTAMA FORMULIR F-2.29 -->
    <div class="form-container">

        <!-- HEADER LEMBAR DOKUMEN -->
        <table class="header-section">
            <tr>
                <td style="width: 75%;">
                    <table>
                        <tr>
                            <td style="width:160px;">Pemerintah Desa/Kelurahan</td>
                            <td>: BERUGENJANG</td>
                        </tr>
                        <tr>
                            <td <td>Kecamatan</td>
                            <td>: UNDAAN</td>
                        </tr>
                        <tr>
                            <td>Kabupaten/Kota</td>
                            <td>: KUDUS</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%; text-align: right; vertical-align: top;">
                    <div class="code-label-top">Kode. F-2.29</div>
                </td>
            </tr>
        </table>

        <!-- JUDUL SURAT -->
        <div class="main-title-block">
            <h1>SURAT KETERANGAN KEMATIAN</h1>
            <p>No. <span contenteditable="true"
                    class="inline-editable-text"><?= htmlspecialchars($data['nomor_surat']); ?></span></p>
        </div>

        <div style="height: 10px;"></div>

        <!-- 1. BLOK DATA JENAZAH -->
        <div class="block-black-header">JENAZAH</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_jenazah'], 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_jenazah'], 40); ?></td>
            </tr>
            <tr>
                <td>3. Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <?php 
                        $jk_code = (strcasecmp($data['jenis_kelamin'], 'Laki-laki') == 0 || $data['jenis_kelamin'] == 'L') ? '1' : '2';
                    ?>
                    <?= renderBoxes($jk_code, 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. Laki-laki</span>
                    <span class="choice-text-item">2. Perempuan</span>
                </td>
            </tr>
            <tr>
                <td>4. Tanggal Lahir / Umur</td>
                <td>:</td>
                <td>
                    Tgl Lahir: <?= renderDateBoxes($data['tanggal_lahir'] ?? ''); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    Umur: <?= renderBoxes($data['umur'] ?? '', 3); ?> Tahun
                </td>
            </tr>
            <tr>
                <td>5. Tempat Lahir</td>
                <td>:</td>
                <td><?= renderBoxes($data['tempat_lahir'] ?? '', 30); ?></td>
            </tr>
            <tr>
                <td>6. Agama</td>
                <td>:</td>
                <td>
                    <?php
                        $ag_input = strtolower($data['agama'] ?? '');
                        $ag_code = '1';
                        if(strpos($ag_input, 'kristen') !== false) $ag_code = '2';
                        elseif(strpos($ag_input, 'katolik') !== false) $ag_code = '3';
                        elseif(strpos($ag_input, 'hindu') !== false) $ag_code = '4';
                        elseif(strpos($ag_input, 'buddha') !== false) $ag_code = '5';
                        elseif(strpos($ag_input, 'khonghucu') !== false) $ag_code = '6';
                    ?>
                    <?= renderBoxes($ag_code, 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. Islam</span>
                    <span class="choice-text-item">2. Kristen</span>
                    <span class="choice-text-item">3. Katolik</span>
                    <span class="choice-text-item">4. Hindu</span>
                    <span class="choice-text-item">5. Buddha</span>
                    <span class="choice-text-item">6. Khonghucu</span>
                </td>
            </tr>
            <tr>
                <td>7. Pekerjaan</td>
                <td>:</td>
                <td><?= renderBoxes($data['pekerjaan'] ?? '', 30); ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-top:4px;">8. Alamat Terakhir</td>
                <td style="vertical-align: top; padding-top:4px;">:</td>
                <td>
                    Dukuh/Jl: <span contenteditable="true"
                        style="font-weight:bold; outline:none; border-bottom:1px dashed #777;"><?= htmlspecialchars($data['alamat_jenazah'] ?? ''); ?></span><br
                        style="margin-bottom:2px;">
                    Desa: <?= renderBoxes($data['desa_jenazah'] ?? '', 18); ?> &nbsp; Kec:
                    <?= renderBoxes($data['kecamatan_jenazah'] ?? '', 18); ?><br style="margin-bottom:2px;">
                    Kab/Kota: <?= renderBoxes($data['kabupaten_jenazah'] ?? '', 18); ?>
                </td>
            </tr>
        </table>

        <!-- 2. BLOK DATA KEJADIAN KEMATIAN -->
        <div class="block-black-header">PERISTIWA KEMATIAN</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. Hari Kematian</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['hari_kematian'] ?? '', 10); ?></td>
            </tr>
            <tr>
                <td>2. Tanggal Kematian</td>
                <td>:</td>
                <td><?= renderDateBoxes($data['tanggal_kematian'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>3. Jam / Pukul</td>
                <td>:</td>
                <td>
                    <?php 
                        $jam = !empty($data['jam_kematian']) ? date('H:i', strtotime($data['jam_kematian'])) : '';
                    ?>
                    <?= renderBoxes($jam, 5); ?> <span class="small">WIB</span>
                </td>
            </tr>
            <tr>
                <td>4. Tempat Kematian</td>
                <td>:</td>
                <td>
                    <?php
                        $tp_input = strtolower($data['tempat_kematian'] ?? '');
                        $tp_code = '1'; // Default Rumah
                        if(strpos($tp_input, 'rs') !== false || strpos($tp_input, 'sakit') !== false) $tp_code = '2';
                        elseif(strpos($tp_input, 'jalan') !== false) $tp_code = '3';
                    ?>
                    <?= renderBoxes($tp_code, 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. Rumah</span>
                    <span class="choice-text-item">2. RS / Fasilitas Kesehatan</span>
                    <span class="choice-text-item">3. Jalan / Lainnya</span>
                </td>
            </tr>
            <tr>
                <td>5. Penyebab Kematian</td>
                <td>:</td>
                <td>
                    <span contenteditable="true" class="inline-editable-text"
                        style="min-width: 250px; border-bottom: 1px dashed #000;">
                        <?= htmlspecialchars($data['penyebab_kematian'] ?? 'Sakit Biasa / Tua'); ?>
                    </span>
                </td>
            </tr>
        </table>

        <!-- 3. BLOK DATA PELAPOR -->
        <div class="block-black-header">PELAPOR</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK Pelapor</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_pelapor'] ?? '', 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_pelapor'] ?? '', 40); ?></td>
            </tr>
        </table>

        <!-- 4. BLOK DATA SAKSI I -->
        <div class="block-black-header">SAKSI I</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK Saksi I</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_saksi1'] ?? '', 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_saksi1'] ?? '', 40); ?></td>
            </tr>
        </table>

        <!-- 5. BLOK DATA SAKSI II -->
        <div class="block-black-header">SAKSI II</div>
        <table class="block-border-content" style="margin-bottom: 20px;">
            <tr>
                <td style="width: 170px;">1. NIK Saksi II</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_saksi2'] ?? '', 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_saksi2'] ?? '', 40); ?></td>
            </tr>
        </table>

        <!-- DAERAH TANDA TANGAN DOKUMEN -->
        <table class="signature-container-table">
            <tr>
                <td>
                    <p style="margin:0 0 2px 0;">Pelapor</p>
                    <div class="space-sign-blank"></div>
                    <p style="text-decoration: underline; font-weight: bold; margin:0;">
                        ( <span contenteditable="true"
                            style="outline:none;"><?= htmlspecialchars($data['nama_pelapor'] ?? ''); ?></span> )
                    </p>
                </td>
                <td>
                    <!-- Pembatas ruang kosong tengah -->
                </td>
                <td>
                    <p style="margin:0 0 2px 0;">Berugenjang, <span contenteditable="true"
                            style="outline:none;"><?= !empty($data['tanggal_surat']) ? date('d-m-Y', strtotime($data['tanggal_surat'])) : date('d-m-Y'); ?></span>
                    </p>
                    <p style="margin:0;">Petinggi / Kepala Desa Berugenjang</p>
                    <div class="space-sign-blank"></div>
                    <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase; margin:0;"
                        contenteditable="true" style="outline:none;">
                        VIWIT MARIYANTO
                    </p>
                    <?= tampilkanQR('surat_kematian', $id, $qr_token); ?>
                </td>
            </tr>
        </table>

    </div>

    <script>
    // Penanganan box input karakter otomatis lompat ke sebelahnya
    document.querySelectorAll('.char-box').forEach(box => {
        box.addEventListener('input', function() {
            if (this.innerText.length >= 1) {
                this.innerText = this.innerText.toUpperCase().substring(0, 1);
                let next = this.nextElementSibling;
                if (next && next.classList.contains('char-box')) {
                    next.focus();
                }
            }
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === "Backspace" && this.innerText.length === 0) {
                let prev = this.previousElementSibling;
                if (prev && prev.classList.contains('char-box')) {
                    prev.focus();
                }
            }
        });
    });
    </script>
</body>

</html>