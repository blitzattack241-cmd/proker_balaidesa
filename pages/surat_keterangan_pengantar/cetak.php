<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 1. PROTEKSI HALAMAN ADMIN (SECURITY LOCK)
// ==========================================
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak!');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// Koneksi Database
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// Validasi Parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('ID Surat tidak ditemukan!');
            window.close();
          </script>";
    exit;
}

$id_surat = mysqli_real_escape_string($koneksi, $_GET['id']);

// ==========================================
// 2. QUERY MENYESUAIKAN STRUKTUR TABEL ANDA
// ==========================================
$query = mysqli_query($koneksi, "SELECT * FROM `tb_surat_pengantar` WHERE `id_surat` = '$id_surat'");

if (!$query) {
    echo "<div class='alert alert-danger m-4'>Query Error: " . mysqli_error($koneksi) . "</div>";
    exit;
}

if (mysqli_num_rows($query) === 0) {
    echo "<script>
            alert('Data surat tidak ditemukan!');
            window.close();
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($query);

// Modul QR Verifikasi (ACC) - agar setiap surat yang dicetak punya QR sah
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_keterangan_pengantar', $id_surat, $data['nomor_surat'] ?? '');

// Fungsi format tanggal Indonesia untuk Surat (Contoh: 24 Juni 2026)
function tgl_indo($tanggal)
{
    if ($tanggal == '0000-00-00' || empty($tanggal))
        return '-';
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
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}

// Fungsi format tanggal periode (Contoh: 24 - 06 - 2026)
function tgl_periode($tanggal)
{
    if ($tanggal == '0000-00-00' || empty($tanggal))
        return '-';
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' - ' . $pecahkan[1] . ' - ' . $pecahkan[0];
}

$jabatan_penandatanganan = trim($data['jabatan_penandatanganan'] ?? '');
if ($jabatan_penandatanganan === '' || strtolower($jabatan_penandatanganan) === 'kepala desa') {
    $jabatan_penandatanganan = 'Kepala Desa Berugenjang';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/print-preview-responsive.css">
    <title>Cetak Surat Keterangan Pengantar</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #3e3e3e;
            font-family: "Times New Roman", Times, serif;
            color: #000;
            font-size: 11pt;
        }

        /* Top Bar Preview */
        .preview-header {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            box-sizing: border-box;
            z-index: 1000;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .btn-print {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 6px 15px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        .print-container {
            margin-top: 60px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Kertas A4 Mengikuti Margin Dokumen Asli */
        .page {
            background-color: #ffffff;
            width: 210mm;
            height: 297mm;
            padding: 10mm 25mm 15mm 25mm !important;
            box-sizing: border-box;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        /* Kop Surat Resmi dengan Logo */
      /* Styling Kop Surat */
.kop-surat {
    border-bottom: 4px double #000; /* Garis ganda khas kop surat resmi */
    padding-bottom: 5px;
    margin-bottom: 10px;
    width: 100%;
}

.kop-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 90px;
}

.logo-kudus {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 85px;
    height: auto;
}

.kop-teks {
    text-align: center;
    width: 100%;
    padding-left: 70px;  /* Memberi ruang agar teks tidak tertimpa logo */
    padding-right: 70px; /* Menjaga teks tetap simetris di tengah */
}

.kop-teks h2 {
    margin: 0;
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
}

.kop-teks h3 {
    margin: 0;
    font-size: 13pt;
    font-weight: bold;
    text-transform: uppercase;
}

.kop-teks h4 {
    margin: 0;
    font-size: 15pt;
    font-weight: bold;
    text-transform: uppercase;
}

.kop-teks p {
    margin: 2px 0 0 0;
    font-size: 11pt;
    font-weight: normal;
    line-height: 1.2;
}

        .no-klasifikasi {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        /* Judul Dokumen Tengah */
        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-surat h4 {
            margin: 0;
            font-size: 11pt;
            text-decoration: underline;
            font-weight: bold;
        }

        .judul-surat p {
            margin: 2px 0 0 0;
            font-size: 10pt;
        }

        .pembuka {
            margin-bottom: 10px;
            font-size: 10pt;
        }

        /* Tabel Identitas Berurutan 1-12 */
        .tabel-isi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .tabel-isi td {
            padding: 2px 0;
            vertical-align: top;
        }

        .penutup {
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 10pt;
        }

        /* Layout Block Tanda Tangan */
        .block-ttd {
            width: 100%;
            font-size: 10pt;
            margin-top: 5px;
        }

        .row-ttd {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .col-ttd {
            display: table-cell;
            text-align: center;
            vertical-align: top;
        }

        .space-ttd {
            height: 40px;
        }

        /* Styling QR Code dinaikkan */
        .qr-wrapper {
            margin-top: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-wrapper img {
            max-width: 85px;
            height: auto;
        }

        @media print {
            .preview-header {
                display: none !important;
            }

            html,
            body {
                background-color: #ffffff;
            }

            .print-container {
                margin: 0 !important;
                padding: 0 !important;
            }

            .page {
                box-shadow: none !important;
                width: 210mm;
                height: 297mm;
                padding: 15mm 25mm 15mm 25mm !important;
            }
        }
    </style>
</head>

<body>

    <div class="preview-header">
        <div>Pratinjau Cetak Surat Keterangan Pengantar Resmi</div>
        <button class="btn-print" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="print-container">
        <div class="page">

            <!-- Kop Surat Resmi -->
            <div class="kop-surat">
                <div class="kop-header">
                    <img src="/uplouds/Logo_Kudus.png" alt="" class="logo-kudus" onerror="this.onerror=null; this.style.display='none';">
                    <div class="kop-teks">
                        <h4>PEMERINTAH KABUPATEN KUDUS</h4>
                        <h3>KECAMATAN UNDAAN</h3>
                        <h3>DESA BERUGENJANG</h3>
                       <p>
    Jalan Kyai Panjang Babalan - Wonosoco Km 01, Kudus, Kode Pos <br>
   59372 Provinsi Jawa Tengah<br>
    e-mail: desaberugenjangundaan@gmail.com
</p>

                    </div>
                </div>
            </div>

            <!-- Nomor Klasifikasi Kiri -->
            <div class="no-klasifikasi">
                Kode Desa : <?= htmlspecialchars($data['kode_surat'] ?? '31.07.16'); ?>
            </div>

            <!-- Judul Tengah -->
            <div class="judul-surat">
                <h4>SURAT KETERANGAN / PENGANTAR</h4>
                <p>NOMOR: <?= htmlspecialchars($data['nomor_surat']); ?></p>
            </div>

            <div class="pembuka">
                Yang bertanda tangan dibawah ini, menerangkan bahwa :
            </div>

            <!-- Tabel Informasi Poin 1 - 12 -->
            <table class="tabel-isi">
                <tr>
                    <td style="width: 5%; text-align: center;">1</td>
                    <td style="width: 25%;">Nama</td>
                    <td style="width: 3%; text-align: center;">:</td>
                    <td style="font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($data['nama_penduduk']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td>Jenis Kelamin</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($data['jenis_kelamin']); ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td>Tempat & tanggal lahir</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($data['tempat_lahir']); ?>, <?= tgl_indo($data['tanggal_lahir']); ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;">4</td>
                    <td>Kewanegaraan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($data['kewenangnegaraan'] ?? $data['kewarganegaraan'] ?? 'Indonesia'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">5</td>
                    <td>Agama</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($data['agama']); ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;">6</td>
                    <td>Status Perkawinan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($data['status_perkawinan']); ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;">7</td>
                    <td>Pekerjaan</td>
                    <td style="text-align: center;">:</td>
                    <td><?= htmlspecialchars($data['pekerjaan']); ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;">8</td>
                    <td>Tempat tinggal</td>
                    <td style="text-align: center;">:</td>
                    <td style="line-height: 1.2;"><?= htmlspecialchars($data['alamat_tinggal']); ?></td>
                </tr>
                <tr>
                    <td style="text-align: center;">9</td>
                    <td>Surat bukti diri</td>
                    <td style="text-align: center;">:</td>
                    <td>
                        KTP NO : <?= htmlspecialchars($data['nik']); ?><br>
                        KK NO : <?= htmlspecialchars($data['nomor_kk']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">10</td>
                    <td>Keperluan</td>
                    <td style="text-align: center;">:</td>
                    <td style="font-weight: bold; text-align: justify; padding-right: 15px; line-height: 1.2;">
                        <?= htmlspecialchars($data['keperluan']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">11</td>
                    <td>Berlaku mulai</td>
                    <td style="text-align: center;">:</td>
                    <td>Tanggal. <?= tgl_periode($data['berlaku_mulai']); ?> s/d
                        <?= htmlspecialchars($data['berlaku_sampai'] ?? 'Selesai'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">12</td>
                    <td>Keterangan lain-lain</td>
                    <td style="text-align: center;">:</td>
                    <td style="text-align: justify; padding-right: 15px; line-height: 1.2;">
                        <?= htmlspecialchars($data['keterangan_lain'] ?? 'Menerangkan Bahwa Orang tersebut diatas, benar-benar penduduk Desa Berugenjang dan bahwa yang bersangkutan sebagai penanggung jawab KOPDES desa Berugenjang.'); ?>
                    </td>
                </tr>
            </table>

            <div class="penutup">
                Demikian Untuk menjadi maklum bagi yang berkepentingan.
            </div>

            <!-- Penandatanganan -->
            <div class="block-ttd">
                <!-- Baris Pemohon -->
                <div class="row-ttd">
                    <div class="col-ttd" style="width: 45%;"></div>
                    <div class="col-ttd" style="width: 10%;"></div>
                    <div class="col-ttd" style="width: 45%;">
                        Berugenjang, <?= tgl_indo($data['tanggal_surat']); ?><br>
                        Pemohon
                        <div class="space-ttd"></div>
                        <span style="text-transform: uppercase; font-weight: bold;"><?= htmlspecialchars($data['nama_pemohon'] ?? $data['nama_penduduk']); ?></span>
                    </div>
                </div>

                <div style="height: 10px;"></div>

                <!-- Baris Kepala Desa -->
                <div class="row-ttd">
                    <div class="col-ttd" style="width: 27.5%;"></div>
                    <div class="col-ttd" style="width: 45%; text-align: center;">
                        Mengetahui,<br>
                        Kepala Desa Berugenjang
                        <div style="height: 10px;"></div>
                        <div class="qr-wrapper">
                            <?= tampilkanQR('surat_keterangan_pengantar', $id_surat, $qr_token); ?>
                        </div>
                        <span style="text-transform: uppercase; font-weight: bold; text-decoration: underline;">
                            <?= htmlspecialchars($data['nama_penandatanganan'] ?? 'KISWO, S.E'); ?>
                        </span>
                    </div>
                    <div class="col-ttd" style="width: 27.5%;"></div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
