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

// Ambil ID Undangan dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Undangan tidak ditemukan pada URL. Pastikan link berbentuk: cetak.php?id=NOMOR");
}

$id_undangan = (int) $_GET['id'];

// Query mengambil data dari tb_surat_undangan
$sql_surat = "SELECT * FROM `tb_surat_undangan` WHERE `id_undangan` = $id_undangan";
$query = mysqli_query($koneksi, $sql_surat);

// --- FITUR DEBUGGING OTOMATIS JIKA DATA TIDAK DITEMUKAN ---
if (!$query || mysqli_num_rows($query) == 0) {
    echo "<div style='font-family:sans-serif; padding:20px; border:2px solid #dc3545; background:#f8d7da; color:#721c24; margin:20px; border-radius:5px;'>";
    echo "<h3>🚨 Data Surat Undangan Tidak Ditemukan!</h3>";
    echo "<p>Mari cek detail di bawah ini untuk menemukan penyebabnya:</p>";
    echo "<ul>";
    echo "<li>ID yang dikirim via URL: <b>" . (isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'Kosong') . "</b></li>";
    echo "<li>Query yang dijalankan: <code style='background:#fff; padding:3px 6px; border:1px solid #ccc;'>$sql_surat</code></li>";

    if (mysqli_error($koneksi)) {
        echo "<li style='color:red; margin-top:10px;'><b>Pesan Error MySQL:</b> " . mysqli_error($koneksi) . "<br><small><i>(Periksa apakah nama tabel Anda 'tb_surat_undangan' dan primary key-nya 'id_surat')</i></small></li>";
    } else {
        echo "<li style='margin-top:10px;'><b>Status Koneksi:</b> Terhubung. Namun data dengan ID <b>$id_surat</b> memang tidak ada di tabel <b>tb_surat_undangan</b>.</li>";
    }
    echo "</ul>";
    echo "<button onclick='window.close()' style='padding:8px 15px; background:#6c757d; color:#fff; border:none; border-radius:4px; cursor:pointer;'>Tutup Halaman</button>";
    echo "</div>";
    exit;
}

$data = mysqli_fetch_assoc($query);

// Modul QR Verifikasi (ACC) - agar setiap surat yang dicetak punya QR sah
require_once __DIR__ . '/../../includes/qr_helper.php';
$qr_token = dapatkanTokenVerifikasi($koneksi, 'surat_undangan', $id_undangan, $data['nomor_surat'] ?? '');

// Ambil data penerima dari tb_undangan_tujuan
$nama_penerima = '';
$tempat_penerima = '';
$query_tujuan = mysqli_query($koneksi, "SELECT * FROM `tb_undangan_tujuan` WHERE `id_undangan` = $id_undangan ORDER BY `id_tujuan` ASC");
if ($query_tujuan) {
    $tujuan = mysqli_fetch_assoc($query_tujuan);
    if (!empty($tujuan['nama_tujuan'])) {
        $nama_penerima = $tujuan['nama_tujuan'];
    }
    if (!empty($tujuan['alamat_tujuan'])) {
        $tempat_penerima = $tujuan['alamat_tujuan'];
    }
}

// Mengambil data Kepala Desa secara dinamis dari tb_pejabat
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM `tb_pejabat` WHERE `jabatan` LIKE '%Kepala Desa%' LIMIT 1");
$pejabat = mysqli_fetch_assoc($query_pejabat);

// Pengaturan Tanda Tangan (Gunakan data DB pejabat, jika kosong otomatis pakai default Pak Kiswo)
$nama_penandatangan = !empty($pejabat['nama_pejabat']) ? $pejabat['nama_pejabat'] : 'KISWO, S.E';
$jabatan_penandatangan = !empty($pejabat['jabatan']) ? $pejabat['jabatan'] : 'Kepala Desa';
$nip_penandatangan = !empty($pejabat['nip']) ? $pejabat['nip'] : '-';

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
    <title>Surat Undangan - <?= htmlspecialchars($data['nomor_surat'] ?? ''); ?></title>
    <style>
        /* Pengaturan Dasar Halaman sesuai standar dokumen desa */
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

        /* Pembatas Ukuran Kertas F4/Folio */
        .kertas {
            background-color: #fff;
            width: 215mm;
            min-height: 330mm;
            padding: 25mm 20mm 20mm 25mm;
            box-sizing: border-box;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            position: relative;
            margin-bottom: 10px;
        }

        .kop-surat h2 {
            font-size: 16pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
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

        .kop-surat p {
            font-size: 11pt;
            font-style: italic;
            margin: 5px 0 0 0;
            font-weight: normal;
        }

        .garis-kop {
            border: none;
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin-top: 8px;
            margin-bottom: 25px;
        }

        /* Tata Letak Info Baris Atas */
        .tabel-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .tabel-meta td {
            vertical-align: top;
            line-height: 1.5;
            padding: 2px 0;
        }

        /* Isi Konten Surat */
        .paragraf {
            text-align: justify;
            text-indent: 40px;
            line-height: 1.6;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        /* Tabel Rincian Acara/Waktu (Bagian Tengah) */
        .tabel-rincian {
            width: 100%;
            margin-left: 40px;
            margin-top: 15px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .tabel-rincian td {
            vertical-align: top;
            padding: 4px 0;
            line-height: 1.5;
        }

        /* Bagian Tanda Tangan */
        .tabel-ttd {
            width: 100%;
            margin-top: 40px;
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

        /* Fitur Live Edit */
        [contenteditable="true"] {
            outline: none;
            transition: background 0.2s;
        }

        [contenteditable="true"]:hover {
            background-color: #f1f3f5;
            cursor: edit;
        }

        [contenteditable="true"]:focus {
            background-color: #e9ecef;
        }

        /* Pengaturan Mode Cetak Fisik */
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

        /* Bar Navigasi */
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

    <!-- Panel Tombol Aksi -->
    <div class="no-print">
        <div class="nav-control">
            <span class="badge-info-edit">💡 Tips: Tulisan di kertas dapat diedit secara langsung jika ada penyesuaian
                teks mendadak!</span>
            <button class="btn-cetak" onclick="window.print();">🖨️ Cetak Surat</button>
            <button class="btn-cetak" style="background-color: #6c757d;" onclick="window.close();">❌ Tutup</button>
        </div>
    </div>

    <div class="kertas">
        <!-- KOP SURAT SESUAI GAMBAR -->
        <div class="kop-surat">
            <h2>Pemerintah Desa Berugenjang</h2>
            <h3>Kecamatan Undaan</h3>
            <h4>Kabupaten Kudus</h4>
            <p>Jl. Kyai Panjang Km 1 Babalan-Wonosoco Kode Pos 59372</p>
            <div class="garis-kop"></div>
        </div>

        <!-- TABEL STRUKTUR ATAS (KIRI: KETERANGAN SURAT, KANAN: TUJUAN) -->
        <table class="tabel-meta">
            <tr>
                <!-- Sisi Kiri: Atribut Surat -->
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 25%;">Nomor</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 70%;" contenteditable="true">
                                <?= htmlspecialchars($data['nomor_surat'] ?? ''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sifat</td>
                            <td>:</td>
                            <td contenteditable="true"><?= htmlspecialchars($data['sifat'] ?? 'Penting'); ?></td>
                        </tr>
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td contenteditable="true"><?= htmlspecialchars($data['lampiran'] ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td>Perhal</td>
                            <td>:</td>
                            <td style="font-weight: bold; text-decoration: underline;" contenteditable="true">
                                <?= htmlspecialchars($data['perhal'] ?? 'UNDANGAN'); ?>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- Sisi Kanan: Tanggal Surat & Penerima -->
                <td style="width: 50%; padding-left: 40px;">
                    Berugenjang, <?= tgl_indo($data['tanggal_surat'] ?? date('Y-m-d')); ?><br>
                    Kepada Yth. Bapak/Ibu/Sdr/i<br>
                    <div style="font-weight: bold; margin-top: 5px; text-transform: uppercase;" contenteditable="true">
                        <?= htmlspecialchars($nama_penerima ?: 'NAMA PENERIMA'); ?>
                    </div>
                    <div style="margin-top: 15px;">Di -</div>
                    <div style="text-indent: 30px; text-decoration: underline;" contenteditable="true">
                        <?= htmlspecialchars($tempat_penerima ?: 'Tempat'); ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- KONTEN UTAMA SURAT -->
        <div style="margin-top: 10px;">
            <p style="margin: 0; line-height: 1.5;">Dengan hormat,</p>
            <p class="paragraf">
                Mengharap dengan hormat atas kehadirannya Bapak/Ibu/Sdr besok pada :
            </p>
        </div>

        <!-- TABEL DETAIL RINCIAN ACARA -->
        <table class="tabel-rincian">
            <tr>
                <td style="width: 18%;">Hari</td>
                <td style="width: 3%;">:</td>
                <td style="width: 79%;" contenteditable="true"><?= htmlspecialchars($data['hari'] ?? ''); ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td contenteditable="true">
                    <?= !empty($data['tanggal_acara']) ? tgl_indo($data['tanggal_acara']) : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Jam</td>
                <td>:</td>
                <td contenteditable="true"><?= htmlspecialchars($data['jam'] ?? '19.00 Wib s/d selesai'); ?></td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td contenteditable="true">
                    <?= htmlspecialchars($data['tempat_acara'] ?? 'Halaman Balai Desa Berugenjang'); ?>
                </td>
            </tr>
            <tr>
                <td>Acara</td>
                <td>:</td>
                <td style="font-weight: bold;" contenteditable="true"><?= htmlspecialchars($data['acara'] ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td style="font-weight: bold;" contenteditable="true">
                    <?= htmlspecialchars($data['keterangan'] ?? 'Mohon Hadir Tepat Waktu'); ?>
                </td>
            </tr>
        </table>

        <!-- PENUTUP SURAT -->
        <p class="paragraf" style="margin-top: 25px;">
            Demikian surat undangan ini kami sampaikan atas perhatian dan kehadiranya di sampaikan terima kasih.
        </p>

        <!-- BAGIAN TANDA TANGAN KEPALA DESA -->
        <table class="tabel-ttd">
            <tr>
                <td style="width: 55%;"></td>
                <td style="width: 45%;">
                    <p style="margin: 0;"><?= htmlspecialchars($jabatan_penandatangan); ?> Berugenjang</p>
                    <div class="space-ttd"></div>
                    <p class="nama-kades"><?= htmlspecialchars($nama_penandatangan); ?></p>
                    <?php if (!empty($nip_penandatangan) && $nip_penandatangan !== '-'): ?>
                        <p style="margin: 0; font-size: 10pt;">NIP. <?= htmlspecialchars($nip_penandatangan); ?></p>
                    <?php endif; ?>
                    <?= tampilkanQR('surat_undangan', $id_undangan, $qr_token); ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Script Pemicu Print Otomatis -->
    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 700);
        };
    </script>
</body>

</html>