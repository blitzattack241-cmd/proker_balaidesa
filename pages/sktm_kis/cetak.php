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

// Query data utama SKTM KIS dan Pejabat
$query = mysqli_query(
    $koneksi,
    "
    SELECT s.*, p.nama_pejabat, p.jabatan 
    FROM tb_sktm_kis s
    LEFT JOIN tb_pejabat p ON s.id_pejabat = p.id_pejabat
    WHERE s.id_sktm = '$id_sktm'"
);

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
$qr_token = dapatkanTokenVerifikasi($koneksi, 'sktm_kis', $id_sktm, $data['nomor_surat'] ?? '');

// Format tanggal angka untuk Tempat/Tgl Lahir (Contoh: 21 - 09 - 1962)
function format_tgl_angka($tanggal)
{
    if (empty($tanggal) || $tanggal == '0000-00-00')
        return '-';
    $pecah = explode('-', $tanggal);
    return $pecah[2] . ' - ' . $pecah[1] . ' - ' . $pecah[0];
}

// Format tanggal resmi Indonesia untuk TTD (Contoh: 21 Mei 2026)
function tgl_indo($tanggal)
{
    if (empty($tanggal) || $tanggal == '0000-00-00')
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
    $pecah = explode('-', $tanggal);
    return $pecah[2] . ' ' . $bulan[(int) $pecah[1]] . ' ' . $pecah[0];
}

// --- SOLUSI FIX GAMBAR BLANK ---
// Kita cek path alternatif. Jika file cetak berada di subfolder dalam (misal: modules/sktm/cetak.php),
// maka relative path ke folder upload harus mundur beberapa tingkat.
$base_upload_dir = "../../uploads/sktm_kis/";
if (!is_dir($base_upload_dir)) {
    $base_upload_dir = "uploads/sktm_kis/"; // fallback jika sejajar
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Balai Desa - Pratinjau Cetak SKTM KIS</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #3e3e3e;
            font-family: "Times New Roman", Times, serif;
            color: #000;
        }

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

        .btn-print {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 6px 15px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            font-size: 9pt;
        }

        .print-container {
            margin-top: 70px;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
        }

        .page {
            background-color: #ffffff;
            width: 210mm;
            height: 296mm;
            padding: 15mm 20mm;
            box-sizing: border-box;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .kop-surat {
            border-bottom: 4px double #000;
            padding-bottom: 3px;
            margin-bottom: 2px;
            text-align: center;
        }

        .kop-teks h4 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        .kop-teks h3 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            font-weight: normal;
        }

        .kop-teks h4:first-child {
            font-weight: bold;
        }

        .kop-teks p {
            margin: 2px 0 0 0;
            font-size: 10pt;
        }

        .kode-desa-row {
            width: 100%;
            font-size: 10.5pt;
            margin-bottom: 15px;
            text-decoration: underline;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-surat h5 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
        }

        .judul-surat p {
            margin: 2px 0 0 0;
            font-size: 10.5pt;
        }

        .paragraf-pengantar {
            text-align: justify;
            margin-bottom: 12px;
            font-size: 11pt;
            line-height: 1.4;
            text-indent: 0px;
        }

        .tabel-data {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            font-size: 11pt;
        }

        .tabel-data td {
            padding: 2.5px 0;
            vertical-align: top;
        }

        .sub-tabel-bukti {
            width: 100%;
            border-collapse: collapse;
        }

        .sub-tabel-bukti td {
            padding: 0 0 2px 0 !important;
        }

        /* Styling List Anggota Sesuai Dokumen Asli */
        .tabel-anggota-sktm {
            width: 100%;
            margin-top: 4px;
            border-collapse: collapse;
        }

        .tabel-anggota-sktm td {
            padding: 1px 0 !important;
            font-size: 10.5pt;
        }

        .container-ttd {
            width: 100%;
            margin-top: 25px;
            font-size: 11pt;
        }

        .row-ttd-atas {
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        .col-ttd {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .spasi-ttd {
            height: 55px;
        }

        .row-ttd-bawah {
            width: 100%;
            text-align: center;
            margin-top: 25px;
        }

        /* Area Lampiran Foto */
        .lampiran-judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            text-transform: uppercase;
            font-size: 12pt;
        }

        .grid-foto {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .box-foto {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            background: #fafafa;
        }

        .box-foto img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .box-foto p {
            margin: 5px 0 0 0;
            font-size: 9pt;
            font-weight: bold;
        }

        .foto-full {
            grid-column: span 2;
        }

        .foto-full img {
            height: 180px;
        }

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
                padding: 15mm 20mm;
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

    <div class="preview-header">
        <div class="preview-title">Sistem Balai Desa - Pratinjau Cetak SKTM KIS</div>
        <button class="btn-print" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="print-container">

        <!-- HALAMAN 1: SURAT KETERANGAN KIS -->
        <div class="page">
            <div class="kop-surat">
                <div class="kop-teks">
                    <h4>Pemerintah Desa Berugenjang</h4>
                    <h4>Kecamatan Undaan</h4>
                    <h3>Kabupaten Kudus</h3>
                    <p>Jl. Kyai Panjang Km 1 Babalan-Wonosoco kode Pos 59372</p>
                </div>
            </div>

            <div class="kode-desa-row">
                <u>No.</u> : 31.07.16
            </div>

            <div class="judul-surat">
                <h5>Surat Keterangan Tidak Mampu</h5>
                <p>Nomor : <?= htmlspecialchars($data['nomor_surat'] ?? ''); ?></p>
            </div>

            <p class="paragraf-pengantar">
                Yang bertanda tangan di bawah ini Kepala Desa Berugenjang Kecamatan Undaan Kabupaten Kudus Dengan ini
                menerangkan dengan sebenarnya bahwa :
            </p>

            <table class="tabel-data">
                <tr>
                    <td style="width: 4%;">1.</td>
                    <td style="width: 25%;">Nama</td>
                    <td style="width: 3%;">:</td>
                    <td style="font-weight: bold; text-transform: uppercase;">
                        <?= htmlspecialchars($data['nama_warga']); ?>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Tempat / Tgl. Lahir</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['tempat_lahir']); ?>,
                        <?= format_tgl_angka($data['tanggal_lahir']); ?>
                    </td>
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
                <tr>
                    <td>7.</td>
                    <td>Warganegaraan</td>
                    <td>:</td>
                    <td><?= !empty($data['kewarganegaraan']) ? htmlspecialchars($data['kewarganegaraan']) : 'Indonesia'; ?>
                    </td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Tempat tinggal</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['alamat_tinggal']); ?></td>
                </tr>
                <tr>
                    <td>9.</td>
                    <td>Surat Bukti Diri</td>
                    <td>:</td>
                    <td>
                        <table class="sub-tabel-bukti">
                            <tr>
                                <td style="width: 65px;">No KK</td>
                                <td style="width: 15px;">:</td>
                                <td><?= htmlspecialchars($data['no_kk']); ?></td>
                            </tr>
                            <tr>
                                <td>No KTP</td>
                                <td>:</td>
                                <td><?= htmlspecialchars($data['no_ktp']); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>Keperluan</td>
                    <td>:</td>
                    <td>
                        <span style="text-align: justify; display: block;"><?= htmlspecialchars($data['keperluan']); ?>
                            atas nama :</span>

                        <!-- List Anggota Sesuai Format Kolom Target -->
                        <table class="tabel-anggota-sktm">
                            <?php
                            if (!empty($data['anggota_keluarga'])):
                                // Memisah baris isi textarea anggota keluarga
                                $lines = explode("\n", str_replace("\r", "", $data['anggota_keluarga']));
                                $no = 1;
                                foreach ($lines as $line):
                                    if (trim($line) === '')
                                        continue;

                                    // Deteksi jika user input menggunakan pemisah kurung atau custom separator
                                    // Dipecah agar nama di kiri dan NIK presisi di kanan
                                    $part_name = $line;
                                    $part_nik = '';
                                    if (preg_match('/\((.*?)\)/', $line, $matches)) {
                                        $part_nik = $matches[1];
                                        $part_name = trim(str_replace($matches[0], '', $line));
                                    }
                                    ?>
                                    <tr>
                                        <td style="width: 5%; text-align: right; padding-right: 5px;"><?= $no++; ?>.</td>
                                        <td style="width: 45%; text-transform: uppercase;"><?= htmlspecialchars($part_name); ?>
                                        </td>
                                        <td style="width: 3%;">:</td>
                                        <td><?= htmlspecialchars($part_nik); ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td style="width: 5%; text-align: right; padding-right: 5px;">1.</td>
                                    <td style="width: 45%; text-transform: uppercase;">
                                        <?= htmlspecialchars($data['nama_warga']); ?>
                                    </td>
                                    <td style="width: 3%;">:</td>
                                    <td><?= htmlspecialchars($data['no_ktp']); ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>11.</td>
                    <td>Berlaku mulai</td>
                    <td>:</td>
                    <td><?= tgl_indo($data['berlaku_mulai'] ?? $data['tanggal_surat']); ?> sampai Selesai.</td>
                </tr>
                <tr>
                    <td>12.</td>
                    <td>Keperluan lain – lain</td>
                    <td>:</td>
                    <td>
                        Orang tersebut di atas benar – benar warga Desa Berugenjang Kec. Undaan Kab. Kudus dan Warga
                        Tersebut benar-benar Miskin/Tidak Mampu.
                    </td>
                </tr>
            </table>

            <p class="paragraf-pengantar" style="margin-top: 15px;">
                Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan seperlunya .
            </p>

            <div class="container-ttd">
                <div class="row-ttd-atas">
                    <div class="col-ttd">
                        Tanda Tangan<br>
                        Pemohon/Pemegang
                        <div class="spasi-ttd"></div>
                        <span
                            style="text-transform: uppercase; font-weight: bold;"><?= htmlspecialchars($data['nama_warga']); ?></span>
                    </div>
                    <div class="col-ttd">
                        Kudus, <?= tgl_indo($data['tanggal_surat']); ?><br>
                        Kepala Desa Berugenjang
                        <div class="spasi-ttd"></div>
                        <?= tampilkanQR('sktm_kis', $id_sktm, $qr_token); ?>
                        <!-- Diubah manual / dinamis sesuai Pejabat penandatangan -->
                        <strong style="text-decoration: underline;">
                            <?= htmlspecialchars($data['nama_pejabat'] ?? 'K I S W O, S.E'); ?></strong>
                    </div>
                </div>

                <div class="row-ttd-bawah">
                    <span style="text-decoration: underline;">Mengetahui :</span><br>
                    Camat Undaan
                    <div class="spasi-ttd" style="height: 50px;"></div>
                    ......................................................
                </div>
            </div>
        </div>

        <!-- HALAMAN 2: LAMPIRAN FOTO RUMAH -->
        <div class="page">
            <div class="lampiran-judul">
                Lampiran Dokumen Kelayakan SKTM KIS<br>
                <span style="font-size: 11pt; text-transform: none; font-weight: normal;">Arsip Foto Kondisi Rumah - An.
                    <?= htmlspecialchars($data['nama_warga']); ?></span>
            </div>

            <div class="grid-foto">
                <!-- 1. Tampak Depan -->
                <div class="box-foto">
                    <?php
                    $img_depan = $base_upload_dir . $data['foto_depan'];
                    if (!empty($data['foto_depan']) && file_exists($img_depan)): ?>
                        <img src="<?= $img_depan; ?>" alt="Tampak Depan">
                    <?php else: ?>
                        <div style="height: 160px; line-height: 160px; background: #eee; color: #777; font-size: 10pt;">Foto
                            Tidak Ditemukan / Belum Diupload</div>
                    <?php endif; ?>
                    <p>1. TAMPAK DEPAN RUMAH</p>
                </div>

                <!-- 2. Ruang Tamu -->
                <div class="box-foto">
                    <?php
                    $img_tamu = $base_upload_dir . $data['foto_ruang_tamu'];
                    if (!empty($data['foto_ruang_tamu']) && file_exists($img_tamu)): ?>
                        <img src="<?= $img_tamu; ?>" alt="Ruang Tamu">
                    <?php else: ?>
                        <div style="height: 160px; line-height: 160px; background: #eee; color: #777; font-size: 10pt;">Foto
                            Tidak Ditemukan / Belum Diupload</div>
                    <?php endif; ?>
                    <p>2. RUANG TAMU</p>
                </div>

                <!-- 3. Kamar Tidur -->
                <div class="box-foto">
                    <?php
                    $img_tidur = $base_upload_dir . $data['foto_kamar_tidur'];
                    if (!empty($data['foto_kamar_tidur']) && file_exists($img_tidur)): ?>
                        <img src="<?= $img_tidur; ?>" alt="Kamar Tidur">
                    <?php else: ?>
                        <div style="height: 160px; line-height: 160px; background: #eee; color: #777; font-size: 10pt;">Foto
                            Tidak Ditemukan / Belum Diupload</div>
                    <?php endif; ?>
                    <p>3. KAMAR TIDUR</p>
                </div>

                <!-- 4. Dapur -->
                <div class="box-foto">
                    <?php
                    $img_dapur = $base_upload_dir . $data['foto_dapur'];
                    if (!empty($data['foto_dapur']) && file_exists($img_dapur)): ?>
                        <img src="<?= $img_dapur; ?>" alt="Dapur">
                    <?php else: ?>
                        <div style="height: 160px; line-height: 160px; background: #eee; color: #777; font-size: 10pt;">Foto
                            Tidak Ditemukan / Belum Diupload</div>
                    <?php endif; ?>
                    <p>4. BAGIAN DAPUR</p>
                </div>

                <!-- 5. Kamar Mandi -->
                <div class="box-foto foto-full">
                    <?php
                    $img_mandi = $base_upload_dir . $data['foto_kamar_mandi'];
                    if (!empty($data['foto_kamar_mandi']) && file_exists($img_mandi)): ?>
                        <img src="<?= $img_mandi; ?>" alt="Kamar Mandi">
                    <?php else: ?>
                        <div style="height: 180px; line-height: 180px; background: #eee; color: #777; font-size: 10pt;">Foto
                            Tidak Ditemukan / Belum Diupload</div>
                    <?php endif; ?>
                    <p>5. KAMAR MANDI / TOILET</p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>