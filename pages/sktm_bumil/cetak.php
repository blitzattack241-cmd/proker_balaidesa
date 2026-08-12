<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak!');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

require_once __DIR__ . '/../../koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('ID Surat tidak ditemukan!');
            window.close();
          </script>";
    exit;
}

$id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

// Query relasi data SKTM Bumil dan Pejabat
$query = mysqli_query($koneksi, "
    SELECT s.*, p.nama_pejabat, p.jabatan 
    FROM tb_sktm_bumil s
    LEFT JOIN tb_pejabat p ON s.id_pejabat = p.id_pejabat
    WHERE s.id_sktm = '$id_sktm'
");

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
$qr_token = dapatkanTokenVerifikasi($koneksi, 'sktm_bumil', $id_sktm, $data['nomor_surat'] ?? '');

// Fungsi format tanggal Indonesia
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
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Balai Desa - Pratinjau Cetak SKTM Bumil</title>
    <style>
        /* CSS RESET & BASE STYLES */
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #3e3e3e;
            font-family: "Times New Roman", Times, serif;
            color: #000;
        }

        /* 1. TOP BAR HITAM */
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .preview-title {
            font-weight: bold;
        }

        .btn-print {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 6px 15px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 9pt;
            transition: background-color 0.2s;
        }

        .btn-print:hover {
            background-color: #0b5ed7;
        }

        /* 2. CONTAINER PREVIEW */
        .print-container {
            margin-top: 70px;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* Desain Kertas A4 */
        .page {
            background-color: #ffffff;
            width: 210mm;
            height: 296mm;
            padding: 12mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        /* 3. FORMAT DOKUMEN */
        .kop-surat {
            border-bottom: 4px solid #000;
            padding-bottom: 3px;
            margin-bottom: 2px;
            text-align: center;
            position: relative;
        }

        .kop-logo {
            position: absolute;
            left: 15px;
            top: 5px;
            width: 80px;
            height: auto;
        }

        .kop-teks {
         margin: 4px 0 0 0;
    font-size: 9.5pt;
    line-height: 1.3;
    font-style: normal; /* Memastikan teks berdiri tegak */
        }

        .kop-teks h4 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        .kop-teks h3 {
            margin: 0;
            font-size: 15pt;
            text-transform: uppercase;
            font-weight: bold;
        }

        .kop-teks p {
           margin: 3px 0 0 0;
    font-size: 11pt;
    font-style: normal; /* Dibuat normal agar teks berdiri tegak */
    line-height: 1.3;
        }

        .kode-desa-row {
            width: 100%;
            font-size: 10pt;
            margin-bottom: 12px;
            margin-top: 2px;
            font-weight: bold;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 12px;
        }

        .judul-surat h5 {
            margin: 0;
            font-size: 12pt;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
        }

        .judul-surat p {
            margin: 2px 0 0 0;
            font-size: 10pt;
        }

        .paragraf-pengantar {
            text-align: justify;
            margin-bottom: 8px;
            font-size: 10.5pt;
            line-height: 1.3;
        }

        .tabel-data {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            font-size: 10.5pt;
        }

        .tabel-data td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        /* Gaya Sub-Tabel untuk Surat Bukti Diri */
        .sub-tabel-bukti {
            width: 100%;
            border-collapse: collapse;
            font-size: inherit;
            font-family: inherit;
        }

        .sub-tabel-bukti td {
            padding: 0px 0px 2px 0px !important;
            border: none !important;
        }

        /* Bagian Tanda Tangan */
        .container-ttd {
            width: 100%;
            margin-top: 15px;
            font-size: 10.5pt;
        }

        .row-ttd-atas {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin-bottom: 12px;
        }

        .col-ttd {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .spasi-ttd {
            height: 45px;
        }

        .row-ttd-bawah {
            width: 100%;
            text-align: center;
            margin-top: 5px;
        }

        /* Desain Halaman Lampiran Foto */
        .lampiran-judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 15px;
            text-transform: uppercase;
            font-size: 11pt;
        }

        .grid-foto {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .box-foto {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            background: #fafafa;
        }

        .box-foto img {
            width: 100%;
            height: 95px;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .box-foto p {
            margin: 3px 0 0 0;
            font-size: 8pt;
            font-weight: bold;
        }

        .foto-full {
            grid-column: span 2;
        }

        .foto-full img {
            height: 115px;
        }

        /* 4. CSS KHUSUS PRINT FISIK */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

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
                gap: 0 !important;
            }

            .page {
                width: 210mm;
                height: 297mm;
                padding: 12mm 20mm;
                box-shadow: none !important;
                page-break-after: always;
                page-break-inside: avoid;
            }

            .page:last-child {
                page-break-after: avoid;
            }
        }
    </style>
</head>

<body>

    <!-- 1. TOP NAV BAR -->
    <div class="preview-header">
        <div class="preview-title">Sistem Balai Desa - Pratinjau Cetak SKTM Bumil</div>
        <button class="btn-print" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="print-container">

        <!-- HALAMAN 1: SURAT KETERANGAN -->
        <div class="page">
            <!-- Kop Surat Resmi -->
            <div class="kop-surat">
                <img class="kop-logo" src="/uplouds/Logo_Kudus.png"  alt="Logo Kab"
                    onerror="this.style.display='none'">
                <div class="kop-teks">
                    <h3>Pemerintah Desa Berugenjang</h3>
                    <h4>Kecamatan Undaan</h4>
                    <h4>Kabupaten Kudus</h4>
                      <p>
                        Jalan Kyai Panjang Babalan - Wonosoco Km 01, Kudus, Kode Pos<br>
                        59372 Provinsi Jawa Tengah<br>
                        e-mail: desaberugenjangundaan@gmail.com
                    </p>
                </div>
            </div>

            <!-- Kode Desa -->
            <div class="kode-desa-row">
                Kode Desa : 31.07.16
            </div>

            <!-- Judul Surat -->
            <div class="judul-surat">
                <h5>Surat Keterangan Tidak Mampu</h5>
                <p>Nomor: <?= htmlspecialchars($data['nomor_surat']); ?></p>
            </div>

            <p class="paragraf-pengantar">
                Yang bertanda tangan di bawah ini Kepala Desa Berugenjang Kecamatan Undaan Kabupaten Kudus<br>
                Dengan ini menerangkan dengan sebenarnya bahwa :
            </p>

            <!-- Tabel Identitas (Penomoran & Struktur Mengikuti Template Word) -->
            <table class="tabel-data">
                <tr>
                    <td style="width: 5%;">1.</td>
                    <td style="width: 25%;">Nama</td>
                    <td style="width: 3%;">:</td>
                    <td style="font-weight: bold;"><?= htmlspecialchars($data['nama_warga']); ?></td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Tempat & tgl Lahir</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['tempat_lahir']); ?>, <?= tgl_indo($data['tanggal_lahir']); ?></td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['jenis_kelamin']); ?></td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['pekerjaan']); ?></td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Agama</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['agama']); ?></td>
                </tr>
                <!-- Menyesuaikan penomoran asli Word yang lompat langsung ke angka 7 -->
                <tr>
                    <td>7.</td>
                    <td>Warganegaraan</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['kewarganegaraan']); ?></td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Tempat tinggal</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['alamat_tinggal']); ?></td>
                </tr>
                <!-- POIN 9: SURAT BUKTI DIRI (PENGGABUNGAN NO KK & NO KTP) -->
                <tr>
                    <td>9.</td>
                    <td>Surat Bukti Diri</td>
                    <td>:</td>
                    <td>
                        <table class="sub-tabel-bukti">
                            <tr>
                                <td style="width: 75px;">No KK</td>
                                <td><?= htmlspecialchars($data['no_kk']); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 75px;">No KTP</td>
                                <td><?= htmlspecialchars($data['no_ktp']); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>Keperluan</td>
                    <td>:</td>
                    <td style="text-align: justify; font-weight: bold; text-decoration: underline;">
                        <?= htmlspecialchars($data['keperluan']); ?>
                    </td>
                </tr>
                <tr>
                    <td>11.</td>
                    <td>Berlaku mulai</td>
                    <td>:</td>
                    <td><?= tgl_indo($data['berlaku_mulai']); ?> sampai Selesai.</td>
                </tr>
                <tr>
                    <td>12.</td>
                    <td>Keperluan lain - lain</td>
                    <td>:</td>
                    <td style="text-align: justify;">
                        Orang tersebut di atas benar-benar warga Desa Berugenjang Kec. Undaan Kab. Kudus dan Warga
                        Tersebut benar-benar dari Keluarga Miskin/Tidak Mampu.
                    </td>
                </tr>
            </table>

            <p class="paragraf-pengantar" style="margin-bottom: 15px;">
                Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan seperlunya.
            </p>

            <!-- Bagian Format TTD -->
            <div class="container-ttd">
                <div class="row-ttd-atas">
                    <div class="col-ttd">
                        Tanda Tangan<br>
                        Pemohon/Pemegang
                        <div class="spasi-ttd"></div>
                        <strong><u><?= htmlspecialchars($data['nama_warga']); ?></u></strong>
                    </div>
                    <div class="col-ttd">
                        Kudus, <?= tgl_indo($data['tanggal_surat']); ?><br>
                        Kepala Desa Berugenjang
                        <div class="spasi-ttd"></div>
                        <?= tampilkanQR('sktm_bumil', $id_sktm, $qr_token); ?>
                        <strong><u><?= htmlspecialchars($data['nama_pejabat']); ?></u></strong>
                    </div>
                </div>

                <div class="row-ttd-bawah">
                    Mengetahui :<br>
                    Camat Undaan
                    <div class="spasi-ttd" style="height: 40px;"></div>
                    ___________________________
                </div>
            </div>
        </div>

        <!-- HALAMAN 2: LAMPIRAN FOTO RUMAH -->
        <div class="page">
            <div class="lampiran-judul">
                Lampiran Dokumen Kelayakan SKTM Bumil<br>
                <span style="font-size: 10pt; text-transform: none; font-weight: normal;">Arsip Foto Kondisi Rumah - An.
                    <?= htmlspecialchars($data['nama_warga']); ?></span>
            </div>

            <div class="grid-foto">
                <div class="box-foto">
                    <?php if (!empty($data['foto_depan']) && file_exists("../../assets/img/sktm_bumil/" . $data['foto_depan'])): ?>
                        <img src="../../assets/img/sktm_bumil/<?= $data['foto_depan']; ?>" alt="Tampak Depan">
                    <?php else: ?>
                        <div style="height: 95px; line-height: 95px; background: #eee; color: #888; font-size: 9pt;">Tidak
                            Ada Foto</div>
                    <?php endif; ?>
                    <p>1. TAMPAK DEPAN RUMAH</p>
                </div>

                <div class="box-foto">
                    <?php if (!empty($data['foto_ruang_tamu']) && file_exists("../../assets/img/sktm_bumil/" . $data['foto_ruang_tamu'])): ?>
                        <img src="../../assets/img/sktm_bumil/<?= $data['foto_ruang_tamu']; ?>" alt="Ruang Tamu">
                    <?php else: ?>
                        <div style="height: 95px; line-height: 95px; background: #eee; color: #888; font-size: 9pt;">Tidak
                            Ada Foto</div>
                    <?php endif; ?>
                    <p>2. RUANG TAMU</p>
                </div>

                <div class="box-foto">
                    <?php if (!empty($data['foto_kamar']) && file_exists("../../assets/img/sktm_bumil/" . $data['foto_kamar'])): ?>
                        <img src="../../assets/img/sktm_bumil/<?= $data['foto_kamar']; ?>" alt="Kamar Tidur">
                    <?php else: ?>
                        <div style="height: 95px; line-height: 95px; background: #eee; color: #888; font-size: 9pt;">Tidak
                            Ada Foto</div>
                    <?php endif; ?>
                    <p>3. KAMAR TIDUR</p>
                </div>

                <div class="box-foto">
                    <?php if (!empty($data['foto_dapur']) && file_exists("../../assets/img/sktm_bumil/" . $data['foto_dapur'])): ?>
                        <img src="../../assets/img/sktm_bumil/<?= $data['foto_dapur']; ?>" alt="Dapur">
                    <?php else: ?>
                        <div style="height: 95px; line-height: 95px; background: #eee; color: #888; font-size: 9pt;">Tidak
                            Ada Foto</div>
                    <?php endif; ?>
                    <p>4. BAGIAN DAPUR</p>
                </div>

                <div class="box-foto foto-full">
                    <?php if (!empty($data['foto_toilet']) && file_exists("../../assets/img/sktm_bumil/" . $data['foto_toilet'])): ?>
                        <img src="../../assets/img/sktm_bumil/<?= $data['foto_toilet']; ?>" alt="Kamar Mandi / Toilet">
                    <?php else: ?>
                        <div style="height: 115px; line-height: 115px; background: #eee; color: #888; font-size: 9pt;">Tidak
                            Ada Foto</div>
                    <?php endif; ?>
                    <p>5. KAMAR MANDI / TOILET</p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>