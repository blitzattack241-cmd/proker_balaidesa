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

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('ID Surat tidak ditemukan!');
            window.close();
          </script>";
    exit;
}

$id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

// Query data utama SKTM Stunting dan Pejabat
$query = mysqli_query($koneksi, "
    SELECT s.*, p.nama_pejabat, p.jabatan 
    FROM tb_sktm_stunting s
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
$qr_token = dapatkanTokenVerifikasi($koneksi, 'sktm_stunting', $id_sktm, $data['nomor_surat'] ?? '');

// Fungsi format tanggal Indonesia
function tgl_indo($tanggal)
{
    if (empty($tanggal) || $tanggal == '0000-00-00') return '-';
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
    <title>Sistem Balai Desa - Pratinjau Cetak SKTM Stunting</title>
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

    /* 1. TOP BAR PREVIEW */
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
        padding: 15mm 20mm;
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
        width: 60px;
        height: auto;
    }

    .kop-teks {
        text-align: center;
        margin-left: 75px;
        margin-right: 75px;
    }

    .kop-teks h4 {
        margin: 0;
        font-size: 13pt;
        text-transform: uppercase;
        font-weight: normal;
    }

    .kop-teks h3 {
        margin: 0;
        font-size: 15pt;
        text-transform: uppercase;
        font-weight: normal;
    }

    .kop-teks h4:first-child {
        font-weight: bold;
    }

    .kop-teks p {
        margin: 3px 0 0 0;
        font-size: 9pt;
        font-style: italic;
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
        margin-bottom: 6px;
        font-size: 10.5pt;
        line-height: 1.3;
    }

    .tabel-data {
        width: 100%;
        margin-bottom: 8px;
        border-collapse: collapse;
        font-size: 10.5pt;
    }

    .tabel-data td {
        padding: 1px 0;
        vertical-align: top;
    }

    /* Sub-tabel No KTP/KK */
    .sub-tabel-bukti {
        width: 100%;
        border-collapse: collapse;
        font-size: inherit;
        font-family: inherit;
    }

    .sub-tabel-bukti td {
        padding: 0px 0px 1px 0px !important;
        border: none !important;
    }

    /* Bagian Tanda Tangan */
    .container-ttd {
        width: 100%;
        margin-top: 40px;
        font-size: 10.5pt;
    }

    .row-ttd-atas {
        width: 100%;
        display: table;
        table-layout: fixed;
        margin-bottom: 10px;
    }

    .col-ttd {
        display: table-cell;
        width: 50%;
        text-align: center;
        vertical-align: top;
    }

    .spasi-ttd {
        height: 65px;
    }

    .row-ttd-bawah {
        width: 100%;
        text-align: center;
        margin-top: 30px;
    }

    /* 4. CSS KHUSUS PRINT FISIK (SATU HALAMAN) */
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
            height: 100%;
            overflow: hidden;
        }

        .print-container {
            margin: 0 !important;
            padding: 0 !important;
            gap: 0 !important;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding: 15mm 20mm;
            box-shadow: none !important;
            page-break-after: avoid;
            page-break-inside: avoid;
        }
    }
    </style>
</head>

<body>

    <!-- 1. TOP NAV BAR -->
    <div class="preview-header no-print">
        <div class="preview-title">Sistem Balai Desa - Pratinjau Cetak SKTM Stunting</div>
        <button class="btn-print" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="print-container">

        <!-- HALAMAN TUNGGAL: SURAT KETERANGAN STUNTING -->
        <div class="page">
            <!-- Kop Surat Resmi -->
            <div class="kop-surat">
                <img class="kop-logo" src="../../assets/img/logo_kabupaten.png" alt="Logo Kab"
                    onerror="this.style.display='none'">
                <div class="kop-teks">
                    <h4>Pemerintah Desa Berugenjang</h4>
                    <h4>Kecamatan Undaan</h4>
                    <h3>Kabupaten Kudus</h3>
                    <p>Jl. Kyai Panjang Babalan - Wonosoco Km. 1 Kode pos 59372</p>
                </div>
            </div>

            <!-- Kode Desa -->
            <div class="kode-desa-row">
                Kode Desa : 31.07.16
            </div>

            <!-- Judul Surat -->
            <div class="judul-surat">
                <h5>Surat Keterangan Tidak Mampu</h5>
                <p>Nomor: 145 / <?= htmlspecialchars($data['nomor_surat'] ?? ''); ?></p>
            </div>

            <p class="paragraf-pengantar">
                Yang bertanda tangan di bawah ini Kepala Desa Berugenjang Kecamatan Undaan Kabupaten Kudus, menerangkan
                dengan sebenarnya bahwa orang tua / wali dari :
            </p>

            <!-- Tabel Identitas Orang Tua / Wali -->
            <table class="tabel-data">
                <tr>
                    <td style="width: 5%;">1.</td>
                    <td style="width: 25%;">Nama Orang Tua / Wali</td>
                    <td style="width: 3%;">:</td>
                    <td style="font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($data['nama_warga']); ?>
                    </td>
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
                    <td>Agama</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['agama']); ?></td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['kewarganegaraan'] ?? 'WNI'); ?></td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['pekerjaan']); ?></td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Alamat Tempat Tinggal</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['alamat_tinggal']); ?></td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Surat Bukti Diri</td>
                    <td>:</td>
                    <td>
                        <table class="sub-tabel-bukti">
                            <tr>
                                <td style="width: 75px;">No. KTP</td>
                                <td>: <?= htmlspecialchars($data['no_ktp']); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 75px;">No. KK</td>
                                <td>: <?= htmlspecialchars($data['no_kk']); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p class="paragraf-pengantar">
                Adalah benar-benar orang tua / wali dari balita :
            </p>

            <!-- Data Anak -->
            <table class="tabel-data" style="margin-left: 20px; width: 95%;">
                <tr>
                    <td style="width: 30%;">Nama Anak</td>
                    <td style="width: 3%;">:</td>
                    <td style="font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($data['nama_anak']); ?>
                    </td>
                </tr>
            </table>

            <p class="paragraf-pengantar" style="margin-top: 6px;">
                Bahwa keluarga tersebut di atas benar-benar penduduk Desa Berugenjang yang tergolong keluarga
                <strong>Kurang Mampu (Pra Sejahtera)</strong> dan balita yang bersangkutan terindikasi mengalami
                <strong>Stunting / Berisiko Stunting</strong>.
            </p>

            <p class="paragraf-pengantar"
                style="text-indent: 0; padding-left: 15px; font-style: italic; margin-top: 6px;">
                &ldquo; Surat keterangan ini diberikan untuk
                <strong><?= htmlspecialchars($data['keperluan']); ?></strong>. &rdquo;
            </p>

            <p class="paragraf-pengantar" style="margin-bottom: 10px;">
                Demikian Surat Keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
            </p>

            <!-- Bagian Format TTD -->
            <div class="container-ttd">
                <div class="row-ttd-atas">
                    <div class="col-ttd">
                        Tanda Tangan<br>
                        Pemohon / Orang Tua
                        <div class="spasi-ttd"></div>
                        <strong><u><?= htmlspecialchars($data['nama_warga']); ?></u></strong>
                    </div>
                    <div class="col-ttd">
                        Kudus, <?= tgl_indo($data['tanggal_surat']); ?><br>
                        <?= htmlspecialchars($data['jabatan'] ?? 'Kepala Desa'); ?> Desa Berugenjang
                        <div class="spasi-ttd"></div>
                        <?= tampilkanQR('sktm_stunting', $id_sktm, $qr_token); ?>
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

    </div>

</body>

</html>