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
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil ID Surat
$id = $_GET['id'] ?? 0;
$id = (int) $id;

// Cari tahu nama tabel yang tersedia
$tableTarget = 'tb_surat_kelahiran';
$checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kelahiran'");
if ($checkTable && mysqli_num_rows($checkTable) > 0) {
    $tableTarget = 'surat_kelahiran';
}

// Ambil data lengkap
$query = mysqli_query($koneksi, "SELECT * FROM `$tableTarget` WHERE id_surat = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data surat tidak ditemukan!");
}

// Modul QR Verifikasi (ACC) - agar setiap surat yang dicetak punya QR sah
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_kelahiran', $id, $data['nomor_surat'] ?? '');

/**
 * Fungsi untuk me-render kotak karakter khas formulir Capil F-2.01
 * Ditambahkan atribut contenteditable="true" agar bisa diklik dan diedit langsung di layar browser
 */
function renderBoxes($text, $length)
{
    $text = strtoupper((string) $text);
    $html = '<span class="box-container">';
    for ($i = 0; $i < $length; $i++) {
        $char = isset($text[$i]) ? $text[$i] : '';
        $html .= '<span class="char-box" contenteditable="true">' . htmlspecialchars($char) . '</span>';
    }
    $html .= '</span>';
    return $html;
}

// Fungsi pembantu parsing tanggal ke pecahan kotak ddmmyyyy (8 kotak)
function renderDateBoxes($dateString)
{
    if (!$dateString)
        return renderBoxes('', 8);
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
    <title>Cetak F-2.01 Resmi - <?= htmlspecialchars($data['nomor_surat']); ?></title>
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

        /* Layout Lembar F-2.01 */
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

        /* Desain Kotak Karakter F-2.01 */
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

            /* Hilangkan background kuning focus saat di-print */
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
            <strong style="color: #dc3545;"><i class="fas fa-edit"></i> Fitur Live-Edit Aktif:</strong>
            Anda bisa langsung mengklik teks/angka di dalam kotak formulir di bawah ini untuk mengubah data secara
            manual sebelum dicetak.
        </div>
        <button class="btn-print" onclick="window.print();">
            <i class="fas fa-print"></i> Cetak Sekarang (F-2.01)
        </button>
    </div>

    <!-- AREA UTAMA FORMULIR F-2.01 -->
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
                            <td>Kecamatan</td>
                            <td>: UNDAAN</td>
                        </tr>
                        <tr>
                            <td>Kabupaten/Kota</td>
                            <td>: KUDUS</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%; text-align: right; vertical-align: top;">
                    <div class="code-label-top">Kode. F-2.01</div>
                </td>
            </tr>
        </table>

        <!-- JUDUL SURAT -->
        <div class="main-title-block">
            <h1>SURAT KETERANGAN KELAHIRAN</h1>
            <p>No. <span contenteditable="true"
                    class="inline-editable-text"><?= htmlspecialchars($data['nomor_surat']); ?></span></p>
        </div>

        <!-- IDENTITAS KEPALA KELUARGA -->
        <table class="grid-table">
            <tr>
                <td style="width: 175px; font-weight: bold;">Nama Kepala Keluarga</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nama_kepala_keluarga'], 40); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Nomor Kartu Keluarga</td>
                <td>:</td>
                <td><?= renderBoxes($data['nomor_kk'], 16); ?></td>
            </tr>
        </table>

        <!-- 1. BLOK DATA BAYI -->
        <div class="block-black-header">BAYI / ANAK</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. Nama Lengkap</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nama_bayi'], 40); ?></td>
            </tr>
            <tr>
                <td>2. Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <?= renderBoxes(($data['jenis_kelamin_bayi'] == 'Laki-laki' ? '1' : '2'), 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. Laki-laki</span>
                    <span class="choice-text-item">2. Perempuan</span>
                </td>
            </tr>
            <tr>
                <td>3. Tempat Dilahirkan</td>
                <td>:</td>
                <td>
                    <?php
                    $tk = 4;
                    $t_dlr = strtolower($data['tempat_dilahirkan']);
                    if (strpos($t_dlr, 'rs') !== false || strpos($t_dlr, 'rumah sakit') !== false)
                        $tk = 1;
                    elseif (strpos($t_dlr, 'puskesmas') !== false)
                        $tk = 2;
                    elseif (strpos($t_dlr, 'polindes') !== false || strpos($t_dlr, 'bidan') !== false)
                        $tk = 3;
                    elseif (strpos($t_dlr, 'rumah') !== false)
                        $tk = 4;
                    ?>
                    <?= renderBoxes($tk, 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. RS/RB</span>
                    <span class="choice-text-item">2. Puskesmas</span>
                    <span class="choice-text-item">3. Polindes/Bidan</span>
                    <span class="choice-text-item">4. Rumah</span>
                    <span class="choice-text-item">5. Lainnya</span>
                </td>
            </tr>
            <tr>
                <td>4. Tempat Kelahiran</td>
                <td>:</td>
                <td><?= renderBoxes($data['tempat_kelahiran_kab'], 30); ?></td>
            </tr>
            <tr>
                <td>5. Hari dan Tanggal Lahir</td>
                <td>:</td>
                <td>
                    Hari: <?= renderBoxes($data['hari_lahir_bayi'], 8); ?>
                    Tgl/Bln/Thn: <?= renderDateBoxes($data['tanggal_lahir_bayi']); ?>
                </td>
            </tr>
            <tr>
                <td>6. Pukul / Jam Lahir</td>
                <td>:</td>
                <td><?= renderBoxes(date('H:i', strtotime($data['pukul_lahir_bayi'])), 5); ?> <span
                        class="small">WIB</span></td>
            </tr>
            <tr>
                <td>7. Jenis Kelahiran</td>
                <td>:</td>
                <td>
                    <?php
                    $jk = 1;
                    if (strpos(strtolower($data['jenis_kelahiran']), '2') !== false || strpos(strtolower($data['jenis_kelahiran']), 'dua') !== false)
                        $jk = 2;
                    elseif (strpos(strtolower($data['jenis_kelahiran']), '3') !== false)
                        $jk = 3;
                    ?>
                    <?= renderBoxes($jk, 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. Tunggal</span>
                    <span class="choice-text-item">2. Kembar 2</span>
                    <span class="choice-text-item">3. Kembar 3</span>
                    <span class="choice-text-item">4. Lainnya</span>
                </td>
            </tr>
            <tr>
                <td>8. Kelahiran Ke</td>
                <td>:</td>
                <td><?= renderBoxes($data['kelahiran_ke'], 2); ?> <span style="font-size:9px; color:#555;">(Urutan anak
                        yang lahir)</span></td>
            </tr>
            <tr>
                <td>9. Penolong Kelahiran</td>
                <td>:</td>
                <td>
                    <?php
                    $pl = 4;
                    $pen = strtolower($data['penolong_kelahiran']);
                    if (strpos($pen, 'dokter') !== false)
                        $pl = 1;
                    elseif (strpos($pen, 'bidan') !== false || strpos($pen, 'perawat') !== false)
                        $pl = 2;
                    elseif (strpos($pen, 'dukun') !== false)
                        $pl = 3;
                    ?>
                    <?= renderBoxes($pl, 1); ?>
                    <span class="choice-text-item" style="margin-left: 8px;">1. Dokter</span>
                    <span class="choice-text-item">2. Bidan/Perawat</span>
                    <span class="choice-text-item">3. Dukun</span>
                    <span class="choice-text-item">4. Lainnya</span>
                </td>
            </tr>
            <tr>
                <td>10. Berat & Panjang Bayi</td>
                <td>:</td>
                <td>
                    Berat: <?= renderBoxes($data['berat_bayi_gram'], 4); ?> Gram &nbsp;&nbsp;&nbsp;&nbsp;
                    Panjang: <?= renderBoxes($data['panjang_bayi_cm'], 2); ?> Cm
                </td>
            </tr>
        </table>

        <!-- 2. BLOK DATA IBU -->
        <div class="block-black-header">IBU</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK Ibu</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_ibu'], 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_ibu'], 40); ?></td>
            </tr>
            <tr>
                <td>3. Tanggal Lahir / Umur</td>
                <td>:</td>
                <td>
                    Tgl Lahir: <?= renderDateBoxes($data['tanggal_lahir_ibu']); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    Umur: <?= renderBoxes($data['umur_ibu'], 2); ?> Tahun
                </td>
            </tr>
            <tr>
                <td>4. Pekerjaan</td>
                <td>:</td>
                <td><?= renderBoxes($data['pekerjaan_ibu'], 25); ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-top:4px;">5. Alamat Tinggal</td>
                <td style="vertical-align: top; padding-top:4px;">:</td>
                <td>
                    Dukuh/Jl: <span contenteditable="true"
                        style="font-weight:bold; outline:none; border-bottom:1px dashed #777;"><?= htmlspecialchars($data['alamat_ibu']); ?></span><br
                        style="margin-bottom:2px;">
                    Desa: <?= renderBoxes($data['desa_ibu'], 18); ?> &nbsp; Kec:
                    <?= renderBoxes($data['kecamatan_ibu'], 18); ?><br style="margin-bottom:2px;">
                    Kab/Prov: <?= renderBoxes($data['kabupaten_ibu'], 18); ?> &nbsp; Warganegara:
                    <?= renderBoxes('1', 1); ?> <small>(1.WNI 2.WNA)</small>
                </td>
            </tr>
        </table>

        <!-- 3. BLOK DATA AYAH -->
        <div class="block-black-header">AYAH</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK Ayah</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_ayah'], 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_ayah'], 40); ?></td>
            </tr>
            <tr>
                <td>3. Tanggal Lahir / Umur</td>
                <td>:</td>
                <td>
                    Tgl Lahir: <?= renderDateBoxes($data['tanggal_lahir_ayah']); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    Umur: <?= renderBoxes($data['umur_ayah'], 2); ?> Tahun
                </td>
            </tr>
            <tr>
                <td>4. Pekerjaan / Alamat</td>
                <td>:</td>
                <td>
                    Pek: <?= renderBoxes($data['pekerjaan_ayah'], 25); ?><br>
                    <span style="font-size:9px; color:#444444;">Alamat: </span><span contenteditable="true"
                        style="font-size:9px; outline:none; border-bottom:1px dashed #aaa;"><?= htmlspecialchars($data['alamat_ayah']); ?></span>
                </td>
            </tr>
        </table>

        <!-- 4. BLOK DATA PELAPOR -->
        <div class="block-black-header">PELAPOR</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK Pelapor</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_pelapor'], 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_pelapor'], 40); ?></td>
            </tr>
        </table>

        <!-- 5. BLOK DATA SAKSI I -->
        <div class="block-black-header">SAKSI I</div>
        <table class="block-border-content">
            <tr>
                <td style="width: 170px;">1. NIK Saksi I</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_saksi1'], 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_saksi1'], 40); ?></td>
            </tr>
        </table>

        <!-- 6. BLOK DATA SAKSI II -->
        <div class="block-black-header">SAKSI II</div>
        <table class="block-border-content" style="margin-bottom: 20px;">
            <tr>
                <td style="width: 170px;">1. NIK Saksi II</td>
                <td style="width: 10px;">:</td>
                <td><?= renderBoxes($data['nik_saksi2'], 16); ?></td>
            </tr>
            <tr>
                <td>2. Nama Lengkap</td>
                <td>:</td>
                <td><?= renderBoxes($data['nama_saksi2'], 40); ?></td>
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
                            style="outline:none;"><?= htmlspecialchars($data['nama_pelapor']); ?></span> )
                    </p>
                </td>
                <td>
                    <!-- Kolom kosong pembatas tengah -->
                </td>
                <td>
                    <p style="margin:0 0 2px 0;">Berugenjang, <span contenteditable="true"
                            style="outline:none;"><?= date('d-m-Y', strtotime($data['tanggal_surat'])); ?></span></p>
                    <p style="margin:0;">Petinggi / Kepala Desa Berugenjang</p>
                    <div class="space-sign-blank"></div>
                    <div class="qr-sign-block">
                        <?= tampilkanQR('surat_kelahiran', $id, $qr_token); ?>
                        <p class="ttd-nama"
                            style="text-decoration: underline; font-weight: bold; text-transform: uppercase; margin:0;"
                            contenteditable="true">
                            VIWIT MARIYANTO
                        </p>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <script>
        // Otomatis fokus ke keyboard saat menekan box karakter berikutnya (opsional kenyamanan input)
        document.querySelectorAll('.char-box').forEach(box => {
            box.addEventListener('input', function () {
                if (this.innerText.length >= 1) {
                    this.innerText = this.innerText.toUpperCase().substring(0, 1);
                    let next = this.nextElementSibling;
                    if (next && next.classList.contains('char-box')) {
                        next.focus();
                    }
                }
            });

            // Mengizinkan hapus mundur backspace
            box.addEventListener('keydown', function (e) {
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