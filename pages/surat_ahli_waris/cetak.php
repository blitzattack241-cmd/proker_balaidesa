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

$id_waris = mysqli_real_escape_string($koneksi, $_GET['id']);

// 1. Query data utama Surat Waris dan Pejabat
$query = mysqli_query($koneksi, "
    SELECT sw.*, p.nama_pejabat, p.jabatan 
    FROM tb_surat_waris sw
    LEFT JOIN tb_pejabat p ON sw.id_pejabat = p.id_pejabat
    WHERE sw.id_waris = '$id_waris'
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
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_ahli_waris', $id_waris, $data['nomor_surat'] ?? '');

// 2. Query detail data anak kandung
$query_anak = mysqli_query($koneksi, "
    SELECT * FROM tb_waris_detail_anak 
    WHERE id_waris = '$id_waris' ORDER BY id_detail_anak ASC
");

// 3. Query detail data saksi-saksi (Maksimal 2)
$query_saksi = mysqli_query($koneksi, "
    SELECT * FROM tb_waris_detail_saksi 
    WHERE id_waris = '$id_waris' ORDER BY id_detail_saksi ASC LIMIT 2
");

$saksi_list = [];
while ($row_saksi = mysqli_fetch_assoc($query_saksi)) {
    $saksi_list[] = $row_saksi;
}

// Fungsi format tanggal Indonesia
function tgl_indo($tanggal) {
    if(empty($tanggal) || $tanggal == '0000-00-00') return '-';
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    }
    return $temp;
}

$jumlah_anak = mysqli_num_rows($query_anak);
$jumlah_anak_huruf = trim(penyebut($jumlah_anak));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak 1 Halaman - Surat Keterangan Ahli Waris</title>
    <style>
    html,
    body {
        margin: 0;
        padding: 0;
        background-color: #3e3e3e;
        font-family: "Times New Roman", Times, serif;
        color: #000;
        font-size: 10.5pt;
        /* Dioptimalkan agar tidak terlalu besar */
    }

    /* TOP NAV BAR PREVIEW */
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

    /* Penanda mode edit inline */
    [contenteditable="true"] {
        outline: none;
    }

    [contenteditable="true"]:hover {
        background-color: #fffde7;
    }

    [contenteditable="true"]:focus {
        background-color: #fff9c4;
    }

    .print-container {
        margin-top: 55px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* SET KERTAS 1 HALAMAN PAS */
    .page {
        background-color: #ffffff;
        width: 210mm;
        height: 297mm;
        /* Kunci tinggi A4 */
        padding: 15mm 20mm 10mm 20mm;
        /* Sempitkan padding agar space luas */
        box-sizing: border-box;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        position: relative;
        overflow: hidden;
        /* Mencegah konten tembus ke bawah */
    }

    .judul-dokumen {
        text-align: center;
        margin-bottom: 15px;
    }

    .judul-dokumen h4 {
        margin: 0;
        font-size: 12pt;
        text-decoration: underline;
        text-transform: uppercase;
        font-weight: bold;
    }

    .paragraf-salam {
        text-align: justify;
        line-height: 1.35;
        margin: 8px 0;
    }

    .tabel-saksi-atas {
        width: 100%;
        margin-left: 15px;
        margin-bottom: 8px;
        border-collapse: collapse;
    }

    .tabel-saksi-atas td {
        padding: 1px 0;
        vertical-align: top;
    }

    .tabel-anak-inline {
        width: 100%;
        margin-left: 15px;
        margin-bottom: 8px;
        border-collapse: collapse;
    }

    .tabel-anak-inline td {
        padding: 2px 0;
        vertical-align: top;
    }

    .tabel-ttd-waris {
        width: 100%;
        margin-top: 5px;
        border-collapse: collapse;
    }

    .tabel-ttd-waris td {
        padding: 2px 0;
        vertical-align: middle;
    }

    .kotak-meterai {
        border: 1px dashed #999;
        width: 60px;
        height: 38px;
        line-height: 38px;
        text-align: center;
        font-size: 7.5pt;
        color: #666;
        display: inline-block;
    }

    .tabel-pejabat-bawah {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
        font-size: 10pt;
    }

    .tabel-pejabat-bawah td {
        padding: 1px 0;
        vertical-align: top;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        .preview-header {
            display: none !important;
        }

        [contenteditable="true"]:hover,
        [contenteditable="true"]:focus {
            background-color: transparent !important;
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
            width: 210mm;
            height: 297mm;
            padding: 15mm 20mm 10mm 20mm;
            box-shadow: none !important;
        }
    }
    </style>
</head>

<body>

    <div class="preview-header">
        <div style="font-weight:bold;">Format Cetak: <span style="color:#28a745;">1 Halaman Pas (A4)</span></div>
        <button class="btn-print" onclick="window.print()">Cetak Surat</button>
    </div>

    <div class="print-container">
        <div class="page">

            <!-- JUDUL UTAMA -->
            <div class="judul-dokumen">
                <h4><u>SURAT KETERANGAN AHLI WARIS</u></h4>
            </div>

            <!-- PARAGRAF PEMBUKA -->
            <p class="paragraf-salam">
                Kami yang bertandatangan di bawah ini, para ahli waris dari almarhum : <strong
                    contenteditable="true"><?= htmlspecialchars($data['nama_almarhum']); ?></strong> dengan dihadiri
                oleh saksi-saksi:
            </p>

            <!-- TAMPILAN SAKSI DI ATAS -->
            <table class="tabel-saksi-atas">
                <?php if (isset($saksi_list[0])): ?>
                <tr>
                    <td width="4%">1.</td>
                    <td width="15%">Nama</td>
                    <td width="3%">:</td>
                    <td><strong contenteditable="true"><?= htmlspecialchars($saksi_list[0]['nama_saksi']); ?></strong>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td contenteditable="true"><?= htmlspecialchars($saksi_list[0]['pekerjaan']); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td contenteditable="true"><?= htmlspecialchars($saksi_list[0]['alamat_saksi']); ?></td>
                </tr>
                <?php endif; ?>

                <?php if (isset($saksi_list[1])): ?>
                <tr>
                    <td>2.</td>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong contenteditable="true"><?= htmlspecialchars($saksi_list[1]['nama_saksi']); ?></strong>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td contenteditable="true"><?= htmlspecialchars($saksi_list[1]['pekerjaan']); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td contenteditable="true"><?= htmlspecialchars($saksi_list[1]['alamat_saksi']); ?></td>
                </tr>
                <?php endif; ?>
            </table>

            <!-- PARAGRAF KEDUA MENDIANG -->
            <p class="paragraf-salam" style="text-indent: 0px;">
                Menerangkan dengan sesungguhnya, dan sanggup diangkat sumpah, bahwa Almarhum/Almarhumah <strong
                    contenteditable="true"><?= htmlspecialchars($data['nama_almarhum']); ?></strong> yang bertempat
                tinggal terakhir di <span
                    contenteditable="true"><?= htmlspecialchars($data['alamat_terakhir']); ?></span> pada tanggal <span
                    contenteditable="true"><?= tgl_indo($data['tanggal_meninggal']); ?></span> telah Meninggal Dunia di
                <span contenteditable="true"><?= htmlspecialchars($data['tempat_meninggal']); ?></span>. Dari perkawinan
                Almarhum/Almarhumah <strong
                    contenteditable="true"><?= htmlspecialchars($data['nama_almarhum']); ?></strong> dengan istri/suami
                **) <strong
                    contenteditable="true"><?= !empty($data['nama_pasangan']) ? htmlspecialchars($data['nama_pasangan']) : '....................'; ?></strong>
                (alm) dilahirkan <span contenteditable="true"><?= $jumlah_anak; ?> (<?= $jumlah_anak_huruf; ?>)</span>
                orang anak yaitu :
            </p>

            <!-- DAFTAR ANAK KANDUNG LAYOUT MENYAMPING -->
            <table class="tabel-anak-inline">
                <?php 
                $i = 1;
                while($anak = mysqli_fetch_assoc($query_anak)) {
                    ?>
                <tr>
                    <td width="4%"><?= $i++; ?>.</td>
                    <td width="30%"><strong contenteditable="true"><?= htmlspecialchars($anak['nama_anak']); ?></strong>
                    </td>
                    <td width="33%">Pekerjaan : <span
                            contenteditable="true"><?= htmlspecialchars($anak['pekerjaan']); ?></span></td>
                    <td width="33%">Berumah di : <span
                            contenteditable="true"><?= htmlspecialchars($anak['alamat_tinggal']); ?></span></td>
                </tr>
                <?php 
                } 
                ?>
            </table>

            <!-- TEMPLATE GUGATAN / KETERANGAN KOSONG TENGAH -->
            <p class="paragraf-salam" style="text-indent: 0px; margin-bottom: 2px;">
                Bahwa anak tersebut diatas masih hidup <span contenteditable="true"><?= $jumlah_anak; ?>
                    (<?= $jumlah_anak_huruf; ?>)</span> orang.
            </p>
            <p class="paragraf-salam" style="text-indent: 0px; margin-top: 0; line-height: 1.35;"
                contenteditable="true">
                Bahwa anak tersebut angka ............ telah meninggal dunia di
                ...................................................... pada tanggal
                .......................................... dan semasa hidupnya kawin dengan
                ............................................. dari perkawinan tersebut telah dilahirkan
                ......................................... orang anak yaitu :<br>
                1. ................................................... Pekerjaan : ...................................
                Berumah di : ...................................<br>
                demikian kami istri/suami dan ke <?= $jumlah_anak; ?> (<?= $jumlah_anak_huruf; ?>) orang anak yang masih
                hidup beserta .......................................................... cucu yang berasal dari anak
                ke..................... yang telah meninggal dunia adalah satu-satunya ahli waris
                <strong><?= htmlspecialchars($data['nama_almarhum']); ?></strong>.
            </p>

            <!-- TANGGAL SURAT -->
            <div style="text-align: right; margin-right: 40px; margin-top: 10px;" contenteditable="true">
                Kudus, <?= tgl_indo($data['tanggal_surat']); ?>
            </div>

            <!-- AREA TANDA TANGAN AHLI WARIS -->
            <div style="text-align: center; font-weight: bold; margin-top: 3px; margin-bottom: 3px;">
                Para ahli waris tersebut :
            </div>

            <table class="tabel-ttd-waris">
                <?php 
                mysqli_data_seek($query_anak, 0);
                $j = 1;
                while($anak_ttd = mysqli_fetch_assoc($query_anak)) {
                    ?>
                <tr>
                    <td width="5%"></td>
                    <td width="5%"><?= $j; ?>.</td>
                    <td width="25%">Anak Ke <?= $j; ?></td>
                    <td width="3%">:</td>
                    <td width="27%"><strong
                            contenteditable="true"><?= htmlspecialchars($anak_ttd['nama_anak']); ?></strong></td>
                    <td width="5%">:</td>
                    <td width="30%" contenteditable="true">
                        <?php if($j === $jumlah_anak): ?>
                        <div class="kotak-meterai" contenteditable="false">Meterai</div>
                        .............................................
                        <?php else: ?>
                        .........................................................................
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    $j++;
                } 
                ?>
            </table>

            <!-- AREA SAKSI WARIS -->
            <div style="margin-top: 8px; font-weight: bold; text-decoration: underline;">
                Saksi Waris :
            </div>
            <table class="tabel-ttd-waris" style="margin-left: 15px; margin-top: 2px;">
                <?php if (isset($saksi_list[0])): ?>
                <tr>
                    <td width="4%">1.</td>
                    <td width="25%"><strong
                            contenteditable="true"><?= htmlspecialchars($saksi_list[0]['nama_saksi']); ?></strong></td>
                    <td contenteditable="true">(
                        .................................................................................... )</td>
                </tr>
                <?php endif; ?>
                <?php if (isset($saksi_list[1])): ?>
                <tr>
                    <td>2.</td>
                    <td><strong contenteditable="true"><?= htmlspecialchars($saksi_list[1]['nama_saksi']); ?></strong>
                    </td>
                    <td contenteditable="true">(
                        .................................................................................... )</td>
                </tr>
                <?php endif; ?>
            </table>

            <!-- FOOTER LEGALITAS: KELURAHAN & KECAMATAN -->
            <table class="tabel-pejabat-bawah">
                <tr>
                    <td width="45%" contenteditable="true">
                        Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ........................................<br>
                        Tanggal &nbsp;&nbsp;&nbsp;: ........................................<br>
                        Dikuatkan oleh kami,<br>
                        <strong>Camat Undaan</strong>
                        <div style="height: 48px;"></div> <!-- Spasi tanda tangan dipersempit sedikit -->
                        .......................................................
                    </td>
                    <td width="10%"></td>
                    <td width="45%" contenteditable="true">
                        Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 470 / <?= htmlspecialchars($data['nomor_surat']); ?><br>
                        Tanggal &nbsp;&nbsp;&nbsp;: <?= tgl_indo($data['tanggal_surat']); ?><br>
                        Disaksikan dan dibenarkan oleh kami,<br>
                        <strong><?= htmlspecialchars($data['jabatan']); ?> Desa Berugenjang</strong>
                        <div style="height: 48px;"></div>
                        <strong><u><?= htmlspecialchars($data['nama_pejabat']); ?></u></strong>
                        <?= tampilkanQR('surat_ahli_waris', $id_waris, $qr_token); ?>
                    </td>
                </tr>
            </table>

        </div>
    </div>

</body>

</html>