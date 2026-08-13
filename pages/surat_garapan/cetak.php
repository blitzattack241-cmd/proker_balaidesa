<?php
// 1. Koneksi ke Database
require_once __DIR__ . '/../../koneksi.php';
require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.close();</script>";
    exit;
}

$id_garapan = mysqli_real_escape_string($koneksi, $_GET['id']);

// 2. Ambil Data Utama
$query_utama = mysqli_query($koneksi, "SELECT * FROM tb_surat_garapan WHERE id_garapan = '$id_garapan'");
$data = mysqli_fetch_assoc($query_utama);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.close();</script>";
    exit;
}

// Modul QR Verifikasi (ACC) - agar setiap surat yang dicetak punya QR sah
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_garapan', $id_garapan, $data['nomor_surat'] ?? '');

// 3. Ambil Data Detail Sawah
$query_detail = mysqli_query($koneksi, "SELECT * FROM tb_surat_garapan_detail WHERE id_garapan = '$id_garapan'");
$rincian_sawah = [];
while ($row = mysqli_fetch_assoc($query_detail)) {
    $rincian_sawah[] = $row;
}

$nomor_surat_cetak = !empty($data['nomor_surat']) ? $data['nomor_surat'] : '';
if (empty($nomor_surat_cetak) || strpos($nomor_surat_cetak, '400.10.2.2/') === false) {
    $nomor_surat_cetak = formatNomorSuratGlobal($id_garapan);
}

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

// Format Tanggal Lahir (DD - MM - YYYY)
$tgl_lahir_format = date('d – m – Y', strtotime($data['tanggal_lahir']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/print-preview-responsive.css">
    <title>Preview Surat Garapan - <?= htmlspecialchars($data['nama_penggarap']); ?></title>
    <style>
        /* Desain Background Preview di Browser */
        html,
        body {
            background-color: #525659;
            /* Warna abu-abu gelap seperti PDF viewer */
            margin: 0;
            padding: 0;
            font-family: "Arial", sans-serif;
        }

        /* Panel Navigasi Atas (Hanya Muncul di Layar Monitor) */
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
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 10pt;
            font-weight: bold;
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
            /* A4 width */
            height: 297mm;
            /* A4 height */
            padding: 10mm 15mm;
            margin: 20px auto;
            /* Memberi jarak atas bawah di preview */
            box-sizing: border-box;
            position: relative;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            /* Efek bayangan kertas */
            font-size: 10.5pt;
            line-height: 1.3;
            color: #000;
            font-family: "Times New Roman", Times, serif;
        }

        /* Header Dokumen Sesuai Aslinya */
        .header-dokumen {
            text-align: center;
            margin-bottom: 8px;
        }

        .header-dokumen h1 {
            font-size: 15pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .line-bold {
            border-top: 3px solid #000;
            margin-top: 2px;
            margin-bottom: 3px;
        }

        .sub-header-left {
            font-size: 10pt;
            font-weight: bold;
            text-align: left;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        /* Judul Tengah */
        .judul-box {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-box h2 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .judul-box p {
            margin: 2px 0 0 0;
            font-size: 11pt;
        }

        /* Pembuka */
        .pembuka {
            margin-bottom: 10px;
        }

        /* Form Isian Berstruktur */
        .table-isi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table-isi td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Tabel Sawah */
        .table-sawah {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .table-sawah th,
        .table-sawah td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 9pt;
        }

        .table-sawah th {
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        /* Penutup */
        .penutup {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        /* Area Tanda Tangan */
        .ttd-area {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .ttd-kolom {
            width: 40%;
            text-align: center;
        }

        .ttd-space {
            height: 50px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        /* ATURAN KHUSUS UNTUK CETAK KERTAS */
        @media print {

            html,
            body {
                background: none;
                background-color: #fff;
            }

            /* SEMBUNYIKAN PANEL TOMBOL CETAK SAAT PRINT */
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
                margin: 12mm 15mm 10mm 15mm;
            }
        }
    </style>
</head>

<body>

    <!-- Panel Navigasi Atas (Hanya tampil di browser) -->
    <div class="no-print-header">
        <div class="title">Sistem Balai Desa - Pratinjau Cetak Surat Garapan</div>
        <button class="btn-cetak" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="page-wrapper">
        <!-- Header Kanan Atas -->
        <div class="header-dokumen">
            <h1>Surat Keterangan</h1>
            <h1>Garapan Sawah</h1>
        </div>

        <!-- Garis Tebal -->
        <div class="line-bold"></div>

        <!-- Kode Desa Kiri -->
        <div class="sub-header-left">
            No Kode / Desa/Kelurahan<br>
            31.07.16
        </div>

        <!-- Judul Dokumen Tengah -->
        <div class="judul-box">
            <h2>Surat – Keterangan</h2>
            <p>Nomor : <?= htmlspecialchars($data['nomor_surat'] ?? ''); ?></p>
        </div>

        <!-- Pembuka -->
        <div class="pembuka">
            Yang bertanda tangan dibawah ini menerangkan bahwa :
        </div>

        <!-- Informasi Identitas Berdasarkan Format Asli -->
        <table class="table-isi">
            <!-- 1. Nama & Pasangan -->
            <tr>
                <td width="3%">1.</td>
                <td width="27%">Nama</td>
                <td width="3%">:</td>
                <td width="32%"><?= htmlspecialchars(strtoupper($data['nama_penggarap'])); ?></td>
                <td width="15%">Bin / Binti</td>
                <td width="3%">:</td>
                <td width="17%">
                    <?= !empty($data['bin_binti_penggarap']) ? htmlspecialchars($data['bin_binti_penggarap']) : '–'; ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Suami / Istri</td>
                <td>:</td>
                <td><?= !empty($data['nama_pasangan']) ? htmlspecialchars($data['nama_pasangan']) : '–'; ?></td>
                <td>Bin/Binti</td>
                <td>:</td>
                <td><?= !empty($data['bin_binti_pasangan']) ? htmlspecialchars($data['bin_binti_pasangan']) : '–'; ?>
                </td>
            </tr>

            <!-- 2. Tempat/Tanggal Lahir -->
            <tr>
                <td>2.</td>
                <td>Tempat / Tanggal Lahir</td>
                <td>:</td>
                <td colspan="4"><?= htmlspecialchars($data['tempat_lahir']); ?>, <?= $tgl_lahir_format; ?></td>
            </tr>

            <!-- 3. Agama -->
            <tr>
                <td>3.</td>
                <td>Agama</td>
                <td>:</td>
                <td colspan="4"><?= htmlspecialchars($data['agama']); ?></td>
            </tr>

            <!-- 4. Pekerjaan -->
            <tr>
                <td>4.</td>
                <td>Pekerjaan</td>
                <td>:</td>
                <td colspan="4"><?= htmlspecialchars($data['pekerjaan']); ?></td>
            </tr>

            <!-- 5. Tempat Tinggal -->
            <tr>
                <td>5.</td>
                <td>Tempat Tinggal</td>
                <td>:</td>
                <td colspan="4"><?= htmlspecialchars($data['alamat_tinggal']); ?></td>
            </tr>

            <!-- 6. Keperluan -->
            <tr>
                <td>6.</td>
                <td>Keperluan</td>
                <td>:</td>
                <td colspan="4" style="text-align: justify; line-height: 1.2;">
                    <?= htmlspecialchars($data['keperluan']); ?>
                </td>
            </tr>

            <!-- 7. Keterangan Lainnya -->
            <tr>
                <td>7.</td>
                <td>Keterangan lainnya</td>
                <td>:</td>
                <td colspan="4">Orang tersebut betul-betul mempunyai sawah garapan sbb:</td>
            </tr>
        </table>

        <!-- Tabel Sawah Tetap 10 Baris -->
        <table class="table-sawah">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="35%">SAWAH ATAS NAMA</th>
                    <th width="25%">TERLETAK DI DESA</th>
                    <th width="15%">BLOK</th>
                    <th width="10%">PERSIL</th>
                    <th width="10%">LUAS M2</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_luas = 0;
                // Generate 10 baris tetap
                for ($i = 0; $i < 10; $i++) {
                    if (isset($rincian_sawah[$i])) {
                        $row = $rincian_sawah[$i];
                        $total_luas += $row['luas_m2'];
                        echo "<tr>
                            <td class='text-center'>" . ($i + 1) . "</td>
                            <td>" . htmlspecialchars($row['sawah_atas_nama']) . "</td>
                            <td>" . htmlspecialchars($row['terletak_di_desa']) . "</td>
                            <td>" . htmlspecialchars($row['blok']) . "</td>
                            <td class='text-center'>" . htmlspecialchars($row['persil']) . "</td>
                            <td class='text-right'>" . number_format($row['luas_m2'], 0, ',', '.') . "</td>
                          </tr>";
                    } else {
                        echo "<tr>
                            <td class='text-center' style='color: transparent;'>" . ($i + 1) . "</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>";
                    }
                }
                ?>
                <!-- Baris Jumlah -->
                <tr style="font-weight: bold;">
                    <td colspan="5" style="text-align: center; letter-spacing: 1px;">JUMLAH</td>
                    <td class="text-right"><?= number_format($total_luas, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Penutup -->
        <div class="penutup">
            Demikian harap menjadikan maklum bagi yang bersangkutan.
        </div>

        <!-- Tanda Tangan -->
        <div class="ttd-area">
            <div class="ttd-kolom">
                <p style="margin-bottom: 0;">Penggarap</p>
                <div class="ttd-space"></div>
                <p class="ttd-nama"><?= htmlspecialchars(strtoupper($data['nama_penggarap'])); ?></p>
            </div>

            <div class="ttd-kolom">
                <p style="margin-bottom: 0;">Berugenjang, <?= tgl_indo($data['tanggal_surat']); ?></p>
                <p style="margin-top: 0; margin-bottom: 0;">Kepala Desa Berugenjang</p>
                <div class="ttd-space"></div>
                <?= tampilkanQR('surat_garapan', $id_garapan, $qr_token); ?>
                <p class="ttd-nama">KISWO, S.E.</p>
            </div>
        </div>
    </div>

</body>

</html>
