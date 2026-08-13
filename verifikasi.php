<?php
/**
 * verifikasi.php
 * Halaman yang muncul saat QR tanda tangan di-scan.
 * Hanya perangkat yang terdaftar sebagai perangkat Balai Desa (atau sedang
 * login sebagai admin) yang bisa melihat status dokumen.
 */
session_start();
require_once __DIR__ . '/koneksi.php';

$jenis = $_GET['jenis'] ?? '';
$id_surat = (int) ($_GET['id'] ?? 0);
$token = $_GET['token'] ?? '';

// -----------------------------------------------------------------
// 1. Cek apakah perangkat ini "dipercaya" (pernah login & disimpan)
// -----------------------------------------------------------------
$device_token = $_COOKIE['device_token'] ?? '';
$device_valid = false;
$token_valid = false;

if ($device_token !== '') {
    $dt = mysqli_real_escape_string($koneksi, $device_token);
    $cekDevice = mysqli_query($koneksi, "SELECT id FROM tb_device_terpercaya WHERE device_token = '$dt' LIMIT 1");
    if ($cekDevice && mysqli_num_rows($cekDevice) > 0) {
        $device_valid = true;
        mysqli_query($koneksi, "UPDATE tb_device_terpercaya SET last_used = NOW() WHERE device_token = '$dt'");
    }
}

// Alternatif: kalau sedang aktif login sesi admin di perangkat ini, izinkan juga
if (!$device_valid && !empty($_SESSION['login'])) {
    $device_valid = true;
}

// -----------------------------------------------------------------
// 2. Cek keabsahan token dokumen. Jika token valid, izinkan akses
//    meskipun perangkat belum terdaftar sebagai device terpercaya.
// -----------------------------------------------------------------
$dokumen = null;
if ($jenis !== '' && $id_surat > 0 && $token !== '') {
    $jenis_esc = mysqli_real_escape_string($koneksi, $jenis);
    $token_esc = mysqli_real_escape_string($koneksi, $token);
    $q = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_verifikasi_dokumen WHERE jenis_surat = '$jenis_esc' AND id_surat = $id_surat AND token = '$token_esc' LIMIT 1"
    );
    $dokumen = $q ? mysqli_fetch_assoc($q) : null;

    if ($dokumen) {
        $token_valid = true;
        // Kalau perangkat sudah dipercaya, update last_used.
        if ($device_valid && $device_token !== '') {
            mysqli_query($koneksi, "UPDATE tb_device_terpercaya SET last_used = NOW() WHERE device_token = '" . mysqli_real_escape_string($koneksi, $device_token) . "'");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen - SIMDES</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .verif-card {
            background: #fff;
            max-width: 380px;
            width: 90%;
            padding: 32px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .icon-check,
        .icon-x {
            width: 64px;
            height: 64px;
            margin: 0 auto 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #fff;
        }

        .icon-check {
            background: #16a34a;
        }

        .icon-x {
            background: #dc2626;
        }

        h2 {
            margin: 0 0 8px;
            color: #0f172a;
        }

        p.desc {
            color: #64748b;
            font-size: 0.92rem;
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            text-align: left;
            font-size: 0.9rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        table td {
            padding: 4px 0;
            color: #334155;
        }

        table td:first-child {
            width: 40%;
            color: #64748b;
        }

        .note {
            margin-top: 14px;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        @media (max-width: 420px) {
            body {
                align-items: flex-start;
                padding: 1rem;
            }

            .verif-card {
                width: 100%;
                padding: 1.5rem 1rem;
                border-radius: 14px;
            }

            .verif-card h2 {
                font-size: 1.35rem;
            }

            table td:first-child {
                width: 42%;
            }
        }
    </style>
</head>

<body>
    <div class="verif-card">
        <?php if (!$token_valid && !$device_valid && empty($_SESSION['login'])): ?>
            <div class="icon-x">&#10005;</div>
            <h2>Akses Tidak Diizinkan</h2>
            <p class="desc">Halaman verifikasi ini hanya dapat diakses dari perangkat resmi Balai Desa Berugenjang.</p>
        <?php elseif (!$dokumen): ?>
            <div class="icon-x">&#10005;</div>
            <h2>Dokumen Tidak Ditemukan</h2>
            <p class="desc">Kode QR tidak valid atau dokumen tidak terdaftar dalam sistem.</p>
        <?php else: ?>
            <div class="icon-check">&#10003;</div>
            <h2>DISETUJUI / ACC</h2>
            <table>
                <tr>
                    <td>Jenis Surat</td>
                    <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $dokumen['jenis_surat']))); ?></td>
                </tr>
                <tr>
                    <td>Nomor Surat</td>
                    <td><?= htmlspecialchars($dokumen['nomor_surat'] ?: '-'); ?></td>
                </tr>
                <tr>
                    <td>Tanggal Terbit</td>
                    <td>
                        <?= !empty($dokumen['dibuat_pada'])
                            ? date('d-m-Y H:i', strtotime($dokumen['dibuat_pada']))
                            : '-'; ?>
                    </td>
                </tr>
            </table>
            <p class="note">Dokumen ini sah dan telah disetujui oleh Pemerintah Desa Berugenjang.</p>
        <?php endif; ?>
    </div>
</body>

</html>
