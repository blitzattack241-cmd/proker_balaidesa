<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menggunakan jalur absolut untuk koneksi database
$root_path = $_SERVER['DOCUMENT_ROOT'] . "/proker_balaidesa/koneksi.php";
if (file_exists($root_path)) {
    include $root_path;
} else {
    include dirname(__DIR__, 2) . "/koneksi.php";
}

// Proteksi: Hanya admin yang boleh mengakses cetak
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!isset($_SESSION['role']) || !$isAdmin) {
    echo "<script>alert('Akses Ilegal!'); window.close();</script>";
    exit;
}

// Ambil ID Surat dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Surat tidak ditemukan.");
}

$id_surat = (int) $_GET['id'];

// Query mengambil data surat berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM tb_surat_dukcapil WHERE id_surat = $id_surat");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data surat tidak ditemukan di database.");
}

// Modul QR Verifikasi (ACC) - agar setiap surat yang dicetak punya QR sah
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_pengantar_dukcapil', $id_surat, $data['nomor_surat'] ?? '');

// Fungsi konversi tanggal ke format Indonesia
function tgl_indo($tanggal)
{
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar - <?= htmlspecialchars($data['nomor_surat']); ?></title>
    <style>
        /* Pengaturan Dasar Halaman */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            color: #000;
            background-color: #525659;
            margin: 0;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Pembatas Ukuran Kertas (Standar F4 / Folio) */
        .kertas {
            background-color: #fff;
            width: 215mm;
            min-height: 330mm;
            padding: 25mm 20mm 20mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            position: relative;
            margin-bottom: 15px;
        }

        .kop-surat h2 {
            font-size: 15pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        .kop-surat h3 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 2px 0;
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        .kop-surat h4 {
            font-size: 15pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        .kop-surat h2:first-child {
            font-weight: bold;
        }

        .kop-surat p {
            font-size: 12pt;
            margin: 5px 0 0 0;
            font-weight: normal;
        }

        /* Garis Tebal Kop Surat */
        .garis-kop {
            border: none;
            border-top: 4px solid #000;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* Tanggal & Alamat Tujuan */
        .meta-surat {
            width: 100%;
            margin-bottom: 25px;
        }

        .meta-surat td {
            vertical-align: top;
        }

        /* Judul Surat Pengantar */
        .judul-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul-surat {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .nomor-surat {
            margin: 3px 0 0 0;
            font-size: 11pt;
        }

        /* Tabel Utama Pengiriman */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 35px;
        }

        .tabel-data th,
        .tabel-data td {
            border: 1px solid #000;
            padding: 10px 12px;
            font-size: 11pt;
        }

        .tabel-data th {
            text-align: center;
            font-weight: normal;
        }

        .tabel-data td {
            vertical-align: top;
            height: 300px;
        }

        /* PERBAIKAN: Mengatur teks tabel agar rata kiri rapi tanpa dipaksa melebar */
        .text-left-clean {
            text-align: left !important;
            line-height: 1.5;
            white-space: pre-wrap;
            /* Menjaga enter/baris baru tetap berfungsi */
        }

        /* Desain area teks yang bisa diedit */
        [contenteditable="true"] {
            outline: none;
            transition: background 0.2s;
        }

        /* Saat kursor diarahkan atau diklik di layar monitor, muncul indikator bisa diedit */
        [contenteditable="true"]:hover {
            background-color: #f1f3f5;
            cursor: edit;
        }

        [contenteditable="true"]:focus {
            background-color: #e9ecef;
            border-bottom: 1px dashed #28a745;
        }

        /* Bagian Tanda Tangan */
        .tabel-ttd {
            width: 100%;
            margin-top: 30px;
        }

        .tabel-ttd td {
            text-align: center;
            vertical-align: top;
        }

        .space-ttd {
            height: 75px;
        }

        .nama-kades {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* Pengaturan Cetak (Hanya Berpengaruh saat Print Fisik) */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
                display: block;
            }

            .no-print {
                display: none;
            }

            .kertas {
                width: 100%;
                min-height: auto;
                padding: 0;
                box-shadow: none;
            }

            [contenteditable="true"]:hover,
            [contenteditable="true"]:focus {
                background-color: transparent !important;
            }
        }

        /* Navigasi Control Atas */
        .nav-control {
            text-align: center;
            padding: 15px;
            background: #2e3133;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-cetak {
            padding: 8px 18px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10pt;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-cetak:hover {
            background-color: #218838;
        }

        .badge-info-edit {
            background-color: #17a2b8;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 9.5pt;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>

    <!-- Panel Kontrol Cetak & Edukasi Edit -->
    <!-- Panel Kontrol Cetak & Edukasi Edit -->
    <div class="no-print">
        <div class="nav-control">
            <span class="badge-info-edit">💡 Tips: Anda bisa langsung mengklik dan mengedit tulisan di kertas untuk
                merubah isi sebelum dicetak!</span>
            <button class="btn-cetak" onclick="window.print();">🖨️ Cetak Sekarang</button>
            <button class="btn-cetak" style="background-color: #6c757d;" onclick="window.close();">❌ Tutup</button>
        </div>
    </div>

    <div class="kertas">
        <!-- KOP SURAT ASLI -->
        <div class="kop-surat">
            <h2>Pemerintah Desa Berugenjang</h2>
            <h3>Kecamatan Undaan</h3>
            <h4>Kabupaten Kudus</h4>
            <p>Jln. Kyai Panjang Babalan – Wonosoco , KM. 1 Kudus 59372</p>
            <hr class="garis-kop">
        </div>

        <!-- Bagian Alamat & Tanggal Kanan-Tengah -->
        <!-- Bagian Alamat & Tanggal Kanan-Tengah (Presisi Menggunakan Sub-Tabel) -->
        <table class="meta-surat">
            <tr>
                <td style="width: 45%;"></td>
                <td style="width: 55%; text-align: left; padding-left: 60px;">
                    Berugenjang, <?= tgl_indo($data['tanggal_surat']); ?>

                    <!-- Sub-tabel untuk merapikan penerimaan surat -->
                    <table
                        style="width: 100%; border: none; margin-top: 15px; border-collapse: collapse; line-height: 1.5;">
                        <tr style="border: none;">
                            <td style="width: 10%; border: none; padding: 0; vertical-align: top;">Yth.</td>
                            <td style="width: 90%; border: none; padding: 0; vertical-align: top; font-weight: bold;">
                                Kepala Dinas Dukcapil Kabupaten Kudus
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none; padding: 0; vertical-align: top;">Di-</td>
                            <td style="border: none; padding: 0; vertical-align: top;"></td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none; padding: 0; vertical-align: top;"></td>
                            <td style="border: none; padding: 0; vertical-align: top; text-decoration: underline;">
                                Kudus
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

        <!-- Judul & Nomor Surat Pengantar -->
        <div class="judul-container">
            <h1 class="judul-surat">Surat Pengantar</h1>
            <p class="nomor-surat">Nomor : <span
                    contenteditable="true"><?= htmlspecialchars($data['nomor_surat']); ?></span></p>
        </div>

        <!-- Tabel Rincian Berkas -->
        <table class="tabel-data">
            <thead>
                <tr>
                    <th style="width: 7%;">NO</th>
                    <th style="width: 48%;">Jenis Yang dikirim</th>
                    <th style="width: 15%;">Banyaknya</th>
                    <th style="width: 30%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1.</td>
                    <!-- Menambahkan contenteditable="true" agar isi tabel dapat diketik manual saat halaman terbuka -->
                    <td class="text-left-clean" contenteditable="true"><?= htmlspecialchars($data['jenis_dikirim']); ?>
                    </td>
                    <td style="text-align: center;" contenteditable="true"><?= htmlspecialchars($data['banyaknya']); ?>
                    </td>
                    <td class="text-left-clean" contenteditable="true"><?= htmlspecialchars($data['keterangan']); ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Tanda Tangan Kades Tunggal di Kanan Bawah -->
        <table class="tabel-ttd">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    <p style="margin: 0;">Kepala Desa Berugenjang</p>
                    <div class="space-ttd"></div>
                    <p class="nama-kades">KISWO, S.E</p>
                    <?= tampilkanQR('surat_pengantar_dukcapil', $id_surat, $qr_token); ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Script Autoprint -->
    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 800);
        };
    </script>
</body>

</html>