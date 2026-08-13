<?php
// 1. Koneksi ke Database
require_once __DIR__ . '/../../koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.close();</script>";
    exit;
}

$id_domisili = mysqli_real_escape_string($koneksi, $_GET['id']);

// 2. Ambil Data Surat Domisili beserta Pejabat Penandatangan
$query = mysqli_query($koneksi, "
    SELECT s.*, p.nama_pejabat, p.jabatan 
    FROM tb_surat_domisili s 
    LEFT JOIN tb_pejabat p ON s.id_pejabat = p.id_pejabat 
    WHERE s.id_domisili = '$id_domisili'
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.close();</script>";
    exit;
}

// Modul QR Verifikasi (ACC)
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_domisili', $id_domisili, $data['nomor_surat'] ?? '');

// Fungsi pembantu untuk konversi tanggal ke format Indonesia
function tgl_indo($tanggal)
{
    $bulan = array(
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
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}

// Format Tanggal Lahir (DD – MM – YYYY)
$tgl_lahir_format = date('d – m – Y', strtotime($data['tanggal_lahir']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/print-preview-responsive.css">
    <title>Preview Surat Domisili - <?= htmlspecialchars($data['nama_warga']); ?></title>
    <style>
        /* Desain Background Preview di Browser */
        html,
        body {
            background-color: #525659;
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
        }

        /* Panel Navigasi Atas */
        .no-print-header {
            background-color: #323639;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 9999;
        }

        .no-print-header .title {
            color: #fff;
            font-size: 11pt;
            font-weight: bold;
        }

        .btn-cetak {
            background-color: #0d6efd;
            border: none;
            padding: 8px 20px;
            font-size: 10pt;
            font-weight: bold;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cetak:hover {
            background-color: #0b5ed7;
        }

        /* Wadah Utama Dokumen (Kertas A4) */
        .page-wrapper {
            width: 210mm;
            height: 297mm;
            padding: 10mm 20mm 15mm 20mm;
            margin: 20px auto;
            box-sizing: border-box;
            position: relative;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
            font-family: "Times New Roman", Times, serif;
        }

        /* Kop Surat Resmi Sesuai Gambar Pratinjau */
        .kop-surat {
            position: relative;
            border-bottom: 4px double #000;
            padding-bottom: 4px;
            margin-bottom: 8px;
            margin-top: 0;
            width: 100%;
        }

        .kop-header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 80px;
        }

        .kop-logo {
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            width: 85px;
            height: auto;
        }

        .kop-teks {
            text-align: center;
            width: 100%;
            padding-left: 75px;
            padding-right: 75px;
        }

        .kop-teks h2 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
            line-height: 1.15;
        }

        .kop-teks h3 {
            font-size: 13pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
            line-height: 1.15;
        }

        .kop-teks h4 {
            font-size: 15pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
            line-height: 1.15;
        }

        .kop-teks p {
            font-size: 11pt;
            margin: 3px 0 0 0;
            line-height: 1.2;
            font-weight: normal;
        }

        /* No Kode Desa */
        .no-kode-desa {
            font-size: 10.5pt;
            font-weight: bold;
            text-align: left;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        /* Judul Dokumen */
        .judul-box {
            text-align: center;
            margin-bottom: 12px;
        }

        .judul-box h2 {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .judul-box p {
            margin: 2px 0 0 0;
            font-size: 11pt;
        }

        /* Paragraf Pembuka */
        .pembuka {
            margin-top: 8px;
            margin-bottom: 6px;
        }

        /* Tabel Isi Form Identitas */
        .table-isi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .table-isi td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        /* Area Tanda Tangan */
        .ttd-area {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .ttd-kolom {
            width: 45%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-wrapper {
            margin-top: -5px;
            margin-bottom: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ttd-space {
            height: 70px;
        }

        .ttd-nama {
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-top: 2px;
        }

        /* ATURAN PRINT */
        @media print {
            html,
            body {
                background: none;
                background-color: #fff;
            }

            .no-print-header {
                display: none !important;
            }

            .page-wrapper {
                width: 100%;
                height: 100%;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }

            @page {
                size: A4;
                margin: 10mm 18mm 10mm 18mm;
            }
        }
    </style>
</head>

<body>

    <!-- Panel Navigasi Atas (Hanya tampil di browser) -->
    <div class="no-print-header">
        <div class="title">Sistem Balai Desa - Pratinjau Cetak Surat Domisili</div>
        <button class="btn-cetak" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="page-wrapper">

        <!-- Kop Surat Resmi Berdasarkan Format Gambar -->
        <div class="kop-surat">
            <div class="kop-header">
                <img src="../../uplouds/Logo_Kudus.png" alt="Logo Kudus" class="kop-logo"
                    onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/e0/Coat_of_arms_of_Kudus_Regency.svg'">
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

        <!-- Kode Desa Kiri -->
        <div class="no-kode-desa">
            Kode Desa : 31.07.16/2026
        </div>

        <!-- Judul Dokumen Tengah -->
        <div class="judul-box">
            <h2>SURAT KETERANGAN / PENGANTAR DOMISILI</h2>
            <p>NOMOR: <?= htmlspecialchars($data['nomor_surat']); ?></p>
        </div>

        <!-- Pembuka -->
        <div class="pembuka">
            Yang bertanda tangan dibawah ini, menerangkan bahwa :
        </div>

        <!-- Detail Data Kepala Desa -->
        <table class="table-isi" style="margin-bottom: 10px;">
            <tr>
                <td width="25%">Nama Pejabat</td>
                <td width="3%">:</td>
                <td><strong><?= htmlspecialchars($data['nama_pejabat']); ?></strong></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['jabatan']); ?></td>
            </tr>
        </table>

        <!-- Kalimat Penyambung -->
        <div class="pembuka" style="margin-bottom: 8px;">
            Menerangkan dengan sesungguhnya bahwa :
        </div>

        <!-- Detail Data Warga (Pemohon) -->
        <table class="table-isi">
            <tr>
                <td width="25%">Nama</td>
                <td width="3%">:</td>
                <td><strong><?= htmlspecialchars(strtoupper($data['nama_warga'])); ?></strong></td>
            </tr>
            <tr>
                <td>Tempat/Tgl Lahir</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['tempat_lahir']); ?>, <?= $tgl_lahir_format; ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['jenis_kelamin']); ?></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['agama']); ?></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['nik']); ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['alamat_jalan']); ?> RT <?= htmlspecialchars($data['rt']); ?> / RW <?= htmlspecialchars($data['rw']); ?>, Desa Berugenjang, Kec. Undaan, Kab. Kudus</td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['keperluan']); ?></td>
            </tr>
            <tr>
                <td>Berlaku Mulai</td>
                <td>:</td>
                <td><?= tgl_indo($data['berlaku_mulai']); ?> s/d Selesai</td>
            </tr>
            <tr>
                <td>Keterangan Lain-Lain</td>
                <td>:</td>
                <td style="text-align: justify;">
                    <?= !empty($data['keterangan_lain']) ? htmlspecialchars($data['keterangan_lain']) : 'Menerangkan Bahwa Orang tersebut diatas, benar-benar penduduk Desa Berugenjang'; ?>
                </td>
            </tr>
        </table>

        <!-- Penutup -->
        <div class="pembuka" style="margin-top: 10px; text-align: justify;">
            Demikian surat keterangan Domisili ini dikeluarkan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya.
        </div>

        <!-- Bagian Tanda Tangan & QR Code (Posisi Disesuaikan: Kiri = Kepala Desa, Kanan = Pemegang) -->
        <div class="ttd-area">
            <!-- Kiri: Kepala Desa (dengan QR Code) -->
            <div class="ttd-kolom">
                <p style="margin-bottom: 2px;">&nbsp;</p>
                <p style="margin-top: 0; margin-bottom: 5px;"><?= htmlspecialchars($data['jabatan']); ?></p>
                
                <div class="qr-wrapper">
                    <?= tampilkanQR('surat_domisili', $id_domisili, $qr_token); ?>
                </div>

                <p class="ttd-nama"><?= htmlspecialchars($data['nama_pejabat']); ?></p>
            </div>

            <!-- Kanan: Pemegang Surat / Warga -->
            <div class="ttd-kolom">
                <p style="margin-bottom: 2px;">Kudus, <?= tgl_indo($data['tanggal_surat'] ?? date('Y-m-d')); ?></p>
                <p style="margin-top: 0; margin-bottom: 5px;">Tandatangan Pemegang</p>
                
                <div class="ttd-space"></div>
                
                <p class="ttd-nama"><?= htmlspecialchars(strtoupper($data['nama_warga'])); ?></p>
            </div>
        </div>

    </div>

</body>

</html>
