<?php
session_start();

// 1. Koneksi ke database
require_once 'koneksi.php';

// Proteksi Halaman: Jika belum login, paksa kembali ke login.php
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

/**
 * Menghitung jumlah baris dari tabel pertama yang ditemukan pada daftar kandidat.
 * Dipakai dashboard supaya statistik tetap aman walau nama tabel sedikit berbeda
 * dan tidak membuat halaman error jika tabel belum ada.
 */
function simdes_count_table(mysqli $koneksi, array $candidates): int
{
    foreach ($candidates as $table) {
        $check = mysqli_query($koneksi, "SHOW TABLES LIKE '" . mysqli_real_escape_string($koneksi, $table) . "'");
        if ($check && mysqli_num_rows($check) > 0) {
            $result = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM `$table`");
            if ($result) {
                return (int) (mysqli_fetch_assoc($result)['jumlah'] ?? 0);
            }
        }
    }
    return 0;
}

// Ambil data session untuk mempermudah penulisan variabel
$user_nama = $_SESSION['nama'] ?? '';
$user_role = $_SESSION['role'] ?? '';
$user_foto = 'uploads/logo.png';

if (!empty($_SESSION['user_id'])) {
    $query_foto = mysqli_query($koneksi, "SELECT profile_picture FROM tb_user WHERE user_id = " . (int) $_SESSION['user_id']);
    if ($query_foto && mysqli_num_rows($query_foto) > 0) {
        $row_foto = mysqli_fetch_assoc($query_foto);
        $foto_db = $row_foto['profile_picture'] ?? '';

        if (!empty($foto_db)) {
            $candidate_paths = [];
            $candidate_paths[] = $foto_db;
            $candidate_paths[] = 'uploads/profil/' . $foto_db;

            foreach ($candidate_paths as $candidate) {
                if (!empty($candidate) && file_exists($candidate)) {
                    $user_foto = $candidate;
                    break;
                }
            }
        }
    }
}

if (!empty($_SESSION['profile_picture']) && file_exists($_SESSION['profile_picture'])) {
    $user_foto = $_SESSION['profile_picture'];
}

// Ambil parameter halaman dari URL (?page=...)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

$appSearchItems = [
    ['label' => 'Dashboard', 'page' => 'dashboard', 'href' => 'index.php?page=dashboard', 'description' => 'Ringkasan statistik dan akses cepat layanan desa', 'keywords' => 'dashboard home ringkasan statistik layanan desa'],
    ['label' => 'Profil', 'page' => 'profil', 'href' => 'index.php?page=profil', 'description' => 'Halaman profil pengguna dan akun Anda', 'keywords' => 'profil akun pengguna setting'],
    ['label' => 'Daftar Semua Surat', 'page' => 'daftar-surat', 'href' => 'index.php?page=daftar-surat', 'description' => 'Daftar lengkap layanan surat yang tersedia', 'keywords' => 'daftar semua surat layanan'],
    ['label' => 'Surat Garapan Sawah', 'page' => 'surat-garapan-sawah', 'href' => 'index.php?page=surat-garapan-sawah', 'description' => 'Kelola surat garapan sawah', 'keywords' => 'surat garapan sawah lahan pertanian'],
    ['label' => 'Surat Ahli Waris', 'page' => 'surat-keterangan-ahli-waris', 'href' => 'index.php?page=surat-keterangan-ahli-waris', 'description' => 'Data surat keterangan ahli waris', 'keywords' => 'surat ahli waris warisan pewaris'],
    ['label' => 'Surat Undangan', 'page' => 'surat-undangan', 'href' => 'index.php?page=surat-undangan', 'description' => 'Kelola surat undangan resmi', 'keywords' => 'surat undangan acara resmi'],
    ['label' => 'Surat Kelahiran', 'page' => 'surat-kelahiran', 'href' => 'index.php?page=surat-kelahiran', 'description' => 'Data surat keterangan kelahiran', 'keywords' => 'surat kelahiran lahir bayi'],
    ['label' => 'Surat Kematian', 'page' => 'surat-kematian', 'href' => 'index.php?page=surat-kematian', 'description' => 'Data surat keterangan kematian', 'keywords' => 'surat kematian meninggal'],
    ['label' => 'Surat Keterangan / Pengantar', 'page' => 'surat-keterangan-pengantar', 'href' => 'index.php?page=surat-keterangan-pengantar', 'description' => 'Kelola surat keterangan dan pengantar', 'keywords' => 'surat pengantar keterangan'],
    ['label' => 'Surat Domisili', 'page' => 'surat-domisili', 'href' => 'index.php?page=surat-domisili', 'description' => 'Data surat keterangan domisili', 'keywords' => 'surat domisili tinggal alamat'],
    ['label' => 'Surat Pengantar Dukcapil', 'page' => 'surat-pengantar-dukcapil', 'href' => 'index.php?page=surat-pengantar-dukcapil', 'description' => 'Pengantar administrasi Dukcapil', 'keywords' => 'surat dukcapil pengantar administrasi'],
    ['label' => 'SKTM Bumil', 'page' => 'sktm-bumil-tampil', 'href' => 'index.php?page=sktm-bumil-tampil', 'description' => 'Data surat keterangan tidak mampu untuk bumil', 'keywords' => 'sktm bumil ibu hamil'],
    ['label' => 'SKTM Rawat', 'page' => 'pembebasan-rawat-inab-dan-jalan', 'href' => 'index.php?page=pembebasan-rawat-inab-dan-jalan', 'description' => 'Data surat keterangan tidak mampu rawat inap dan jalan', 'keywords' => 'sktm rawat inap jalan'],
    ['label' => 'SKTM KIS', 'page' => 'sktm-kis', 'href' => 'index.php?page=sktm-kis', 'description' => 'Data surat keterangan tidak mampu KIS', 'keywords' => 'sktm kis kesehatan'],
    ['label' => 'SKTM KIP', 'page' => 'sktm-kip', 'href' => 'index.php?page=sktm-kip', 'description' => 'Data surat keterangan tidak mampu KIP', 'keywords' => 'sktm kip sekolah pendidikan'],
    ['label' => 'Stunting', 'page' => 'stunting', 'href' => 'index.php?page=stunting', 'description' => 'Data penanganan stunting', 'keywords' => 'stunting anak gizi'],
    ['label' => 'Penduduk', 'page' => 'penduduk', 'href' => 'index.php?page=penduduk', 'description' => 'Manajemen data penduduk desa', 'keywords' => 'penduduk warga desa'],
    ['label' => 'Profil Desa', 'page' => 'profil-desa', 'href' => 'index.php?page=profil-desa', 'description' => 'Informasi profil desa dan wilayah', 'keywords' => 'profil desa wilayah'],
];

$suratPages = ['buat-surat', 'surat-garapan', 'surat-garapan-sawah', 'surat-garapan-tambah', 'surat-garapan-edit', 'surat-keterangan-ahli-waris', 'surat_waris', 'surat-waris', 'tambah_surat_waris', 'surat-waris-tambah', 'edit_surat_waris', 'surat-waris-edit', 'surat-undangan', 'surat-undangan-tambah', 'surat-undangan-edit', 'surat-kelahiran', 'surat_kelahiran', 'surat-kelahiran-tambah', 'surat_kelahiran-tambah', 'surat-kematian', 'surat-kematian-tambah', 'surat-kematian-edit'];
$isSuratOpen = in_array($page, $suratPages, true);

$keteranganPages = ['surat-keterangan-pengantar', 'surat-pengantar', 'surat-pengantar-tambah', 'surat-pengantar-edit', 'surat-domisili', 'surat-domisili-tambah', 'surat-domisili-edit', 'surat-keterangan-tidak-mampu', 'surat-pengantar-dukcapil', 'surat-pengantar-dukcapil-tambah', 'surat-pengantar-dukcapil-edit'];
$suratTidakMampuPages = ['surat-keterangan-tidak-mampu', 'bumil', 'sktm-bumil', 'sktm-bumil-tampil', 'sktm-bumil-tambah', 'sktm-bumil-edit', 'sktm-bumil-hapus', 'sktm-bumil-proses-tambah', 'pembebasan-rawat-inab-dan-jalan', 'sktm_rawat', 'sktm-rawat', 'sktm-rawat-tampil', 'sktm-rawat-tambah', 'sktm-rawat-edit', 'sktm-rawat-hapus', 'pengajuan-kis', 'sktm-kip', 'sktm-kip-tampil', 'sktm-kip-tambah', 'sktm-kip-edit', 'sktm-kip-hapus', 'sktm-kis', 'sktm-kis-tampil', 'sktm-kis-tambah', 'sktm-kis-edit', 'stunting', 'stunting-tampil', 'stunting-tambah', 'stunting-edit', 'stunting-hapus', 'sktm-stunting', 'sktm-stunting-tampil', 'sktm-stunting-tambah', 'sktm-stunting-edit', 'sktm-stunting-hapus'];
$isKeteranganOpen = in_array($page, $keteranganPages, true);
$isSuratTidakMampuOpen = in_array($page, $suratTidakMampuPages, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SIMDES - Sistem Informasi Manajemen Desa</title>
    <!-- Favicon -->
    <link href="uplouds/logo.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Custom CSS untuk Tema & Modifikasi Dropdown Profil -->
    <style>
        .sb-topnav.navbar {
            background-color: #007f3e !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sb-sidenav-dark {
            background: linear-gradient(180deg, #007f3e 0%, #016b37 100%) !important;
            box-shadow: inset -1px 0 0 rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand-box {
            display: flex;
            align-items: center;
            padding: 18px 16px 16px;
            margin: 0 10px 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.14);
        }

        .sidebar-logo-web {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            color: #007f3e;
            font-size: 20px;
            margin-right: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar-brand-text h6 {
            margin: 0;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .sidebar-brand-text small {
            color: rgba(255, 255, 255, 0.72);
            font-size: 12px;
        }

        .sb-sidenav-dark .sb-sidenav-menu {
            padding-top: 6px;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 16px;
            padding: 11px 16px !important;
            margin: 4px 12px;
            border-radius: 10px;
            transition: all 0.25s ease;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start;
            position: relative;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.14);
            transform: translateX(3px);
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link .menu-label-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link .sb-nav-link-icon {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 15px;
            width: 18px;
            text-align: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active-green {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.1)) !important;
            color: #ffffff !important;
            font-weight: 600;
            border-left: 4px solid #ffffff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        }

        .active-dot {
            width: 6px;
            height: 6px;
            background-color: white;
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.16);
            flex-shrink: 0;
            margin-left: auto;
            position: static;
        }

        .sb-sidenav-dark .sb-sidenav-footer {
            background-color: transparent !important;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(255, 255, 255, 0.72) !important;
            padding: 14px 18px;
            font-size: 12px;
            line-height: 1.5;
        }

        #layoutSidenav_nav .sb-sidenav {
            overflow-y: auto;
            overflow-x: hidden;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #layoutSidenav_nav .sb-sidenav::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        .profile-dropdown-btn {
            background: transparent !important;
            border: none !important;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            color: white !important;
        }

        .profile-dropdown-btn:focus,
        .profile-dropdown-btn:active {
            box-shadow: none !important;
        }

        .profile-img-nav {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            margin-right: 10px;
        }

        .profile-info-nav {
            text-align: left;
            margin-right: 8px;
            line-height: 1.2;
        }

        .profile-info-nav .profile-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            display: block;
        }

        .profile-info-nav .profile-role {
            font-size: 0.8rem;
            color: #60a5fa;
            /* Biru muda cerah */
            font-weight: 600;
            display: block;
        }

        .dropdown-menu-profile {
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            border-radius: 12px !important;
            padding: 8px 0 !important;
            min-width: 180px !important;
        }

        .dropdown-menu-profile .dropdown-item {
            padding: 10px 20px !important;
            font-size: 0.95rem !important;
            font-weight: 500;
            display: flex;
            align-items: center;
            color: #334155;
        }

        .dropdown-menu-profile .dropdown-item i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .dropdown-menu-profile .dropdown-item {
            gap: 8px;
        }

        .theme-dropdown .dropdown-toggle {
            width: 46px;
            height: 46px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px !important;
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: #fff !important;
            transition: all 0.25s ease;
            backdrop-filter: blur(4px);
            font-size: 1.1rem;
        }

        .theme-dropdown .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.22) !important;
            border-color: rgba(255, 255, 255, 0.35) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .theme-dropdown .dropdown-toggle::after {
            display: none;
        }

        .theme-dropdown .dropdown-menu {
            min-width: 140px;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
            padding: 8px;
            backdrop-filter: blur(8px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.96) 100%);
        }

        .theme-dropdown .dropdown-item {
            padding: 10px 12px;
            border-radius: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #64748b;
        }

        .theme-dropdown .dropdown-item:hover {
            background: rgba(0, 127, 62, 0.10);
            color: #007f3e;
            transform: translateY(-2px);
        }

        .theme-dropdown .dropdown-item i {
            font-size: 1.3rem;
            width: auto;
            margin: 0;
            display: inline-block;
        }

        .theme-dropdown .dropdown-item.active,
        .theme-dropdown .dropdown-item:active {
            background: rgba(0, 127, 62, 0.15);
            color: #007f3e;
        }

        .search-navbar-group {
            position: relative;
            min-width: min(320px, 100%);
            max-width: 420px;
            width: 100%;
        }

        .search-navbar-group .form-control {
            border-radius: 16px;
            padding-left: 14px;
            padding-right: 14px;
            border: 1px solid rgba(0, 0, 0, 0.04);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
            background: var(--card-color, #ffffff);
            color: var(--text-color, #222);
        }

        .search-navbar-group .btn {
            border-radius: 16px;
            min-width: 46px;
            margin-left: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
            border: 1px solid rgba(0, 0, 0, 0.04);
            background: #239a58;
            color: #fff;
        }

        .search-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            z-index: 2500;
            display: none;
            min-width: 100%;
            width: 100%;
            padding: 0;
            border-radius: 18px 0 0 18px;
            background: var(--card-color, #ffffff);
            color: var(--text-color, #222);
            border: 1px solid rgba(0, 0, 0, 0.04);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .search-dropdown.show {
            display: block;
        }

        .search-dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: var(--card-color, #ffffff);
            color: var(--text-color, #222);
            font-size: 0.95rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        .search-dropdown-body {
            padding: 14px 16px 16px;
            font-size: 0.92rem;
        }

        .search-dropdown-close {
            border: 0;
            background: transparent;
            color: inherit;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
        }

        .search-dropdown-list {
            display: grid;
            gap: 12px;
            margin-top: 12px;
        }

        .search-dropdown-item {
            display: block;
            padding: 14px 16px;
            border-radius: 18px;
            background: var(--card-color, #ffffff);
            color: var(--text-color, #222);
            text-decoration: none;
            border: 1px solid rgba(0, 0, 0, 0.04);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
            transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .search-dropdown-item:hover {
            background: rgba(0, 127, 62, 0.05);
            border-color: rgba(0, 127, 62, 0.12);
            text-decoration: none;
            color: #007f3e;
            transform: translateY(-2px);
        }

        .search-dropdown-item strong {
            display: block;
            margin-bottom: 2px;
        }

        .search-dropdown-item small {
            color: #64748b;
            display: block;
        }

        body.dark-mode .search-dropdown {
            background: rgba(15, 23, 42, 0.97);
            color: #e2e8f0;
            border-color: rgba(255, 255, 255, 0.16);
        }

        body.dark-mode .search-dropdown-item {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.1);
            color: #f8fafc;
        }

        body.dark-mode .search-dropdown-item:hover {
            background: rgba(35, 154, 88, 0.16);
            color: #ffffff;
        }

        body.dark-mode .search-dropdown-item small {
            color: #cbd5e1;
        }

        .icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .card-modern-soft {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-modern-soft:hover {
            transform: translateY(-3px);
            box-shadow: 0 22px 50px rgba(15, 23, 42, 0.12);
        }

        .theme-dropdown .dropdown-menu {
            min-width: 120px;
        }

        .theme-dropdown .dropdown-item {
            padding: 10px 0;
        }

        .theme-dropdown .dropdown-item i {
            width: auto;
            margin: 0;
            font-size: 1.2rem;
        }

        .dropdown-menu-profile .item-profile i {
            color: #2563eb;
        }

        .dropdown-menu-profile .item-logout {
            color: #dc2626 !important;
            border-top: 1px solid #f1f5f9;
        }

        /* LIGHT & DARK MODE VARIABLES */
        :root {
            --bg-color: #f5f6fa;
            --card-color: #ffffff;
            --text-color: #222;
            --sidebar: #007f3e;
            --navbar: #007f3e;
        }

        body.dark-mode {
            --bg-color: #121212;
            --card-color: #1f1f1f;
            --text-color: #ffffff;
            --sidebar: #1a1a1a;
            --navbar: #222;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
            transition: .3s;
        }

        #layoutSidenav_content {
            background: var(--bg-color);
        }

        .card {
            background: var(--card-color);
            color: var(--text-color);
        }

        .sb-topnav {
            background: var(--navbar) !important;
        }

        .sb-sidenav-dark {
            background: var(--sidebar) !important;
        }

        footer {
            background: var(--card-color) !important;
            color: var(--text-color);
        }

        .table {
            color: var(--text-color);
        }

        .form-control {
            background: var(--card-color);
            color: var(--text-color);
        }

        /* ===== DASHBOARD MODERN ===== */
        .dash-hero {
            background: linear-gradient(120deg, #007f3e 0%, #049a4b 55%, #16b866 100%);
            border-radius: 22px;
            padding: 32px 34px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0, 127, 62, 0.25);
        }

        .dash-hero::after {
            content: "";
            position: absolute;
            right: -60px;
            top: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .dash-hero::before {
            content: "";
            position: absolute;
            right: 60px;
            bottom: -90px;
            width: 160px;
            height: 160px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .dash-hero h2 {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .dash-hero p {
            opacity: 0.9;
            margin-bottom: 0;
            max-width: 520px;
        }

        .dash-hero .dash-date-badge {
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 0.85rem;
            backdrop-filter: blur(4px);
        }

        .stat-card-modern {
            border: 0;
            border-radius: 18px;
            background: var(--card-color);
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            height: 100%;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        }

        .stat-card-modern .stat-icon-box {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #fff;
        }

        .stat-card-modern .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--text-color);
        }

        .stat-card-modern .stat-label {
            font-size: 0.82rem;
            color: #8a94a6;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .dash-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 34px 0 16px;
        }

        .dash-section-title .bar {
            width: 5px;
            height: 22px;
            border-radius: 4px;
            background: #007f3e;
        }

        .dash-section-title h6 {
            margin: 0;
            font-weight: 700;
            color: var(--text-color);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .menu-tile {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--card-color);
            border-radius: 16px;
            padding: 16px 18px;
            text-decoration: none;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
            height: 100%;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .menu-tile:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .menu-tile .tile-icon {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.05rem;
        }

        .menu-tile .tile-title {
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--text-color);
            margin-bottom: 2px;
            display: block;
        }

        .menu-tile .tile-count {
            font-size: 0.78rem;
            color: #8a94a6;
            font-weight: 500;
        }

        .menu-tile .tile-count .badge-count {
            background: rgba(0, 127, 62, 0.12);
            color: #007f3e;
            border-radius: 20px;
            padding: 2px 9px;
            font-weight: 700;
            margin-right: 4px;
        }

        body.dark-mode .menu-tile {
            border-color: rgba(255, 255, 255, 0.06);
        }
    </style>
    <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed">
    <!-- NAVBAR ATAS -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <a class="navbar-brand ps-3" href="index.php" style="font-weight: 700; letter-spacing: 0.5px;">BERUGENJANG</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>

        <!-- Navbar Search-->
        <form class="d-flex ms-auto me-0 me-md-3 my-2 my-md-0" role="search" action="index.php" method="get"
            id="navbarSearchForm" autocomplete="off">
            <input type="hidden" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>" />
            <div class="input-group search-navbar-group">
                <input class="form-control" type="text" name="q"
                    value="<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Cari modul, surat, atau halaman..." aria-label="Cari data" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="submit"
                    style="background-color: #239a58; border-color: #239a58;" aria-label="Cari"><i
                        class="fas fa-search"></i></button>
            </div>
        </form>

        <!-- Pilihan Tema -->
        <div class="dropdown me-2 theme-dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="Pilih Tema">
                <i class="fas fa-palette"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" onclick="setTheme('light')" title="Mode Terang">
                        <i class="fas fa-sun text-warning"></i>
                        <span>Terang</span>
                    </a></li>
                <li><a class="dropdown-item" href="#" onclick="setTheme('dark')" title="Mode Gelap">
                        <i class="fas fa-moon text-info"></i>
                        <span>Gelap</span>
                    </a></li>
                <li><a class="dropdown-item" href="#" onclick="setTheme('auto')" title="Otomatis">
                        <i class="fas fa-desktop text-secondary"></i>
                        <span>Otomatis</span>
                    </a></li>
            </ul>
        </div>

        <div class="dropdown me-3">
            <button class="btn dropdown-toggle profile-dropdown-btn" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <?php
                // Cek apakah file foto ada di folder, jika tidak ada/eror, pakai ikon default
                if (!empty($user_foto) && file_exists($user_foto)) {
                    echo '<img src="' . $user_foto . '" alt="Profile" class="profile-img-nav">';
                } else {
                    // Jika gambar tidak ditemukan, tampilkan ikon lingkaran user bawaan fontawesome
                    echo '<i class="fas fa-user-circle fa-2x me-2 text-white" style="line-height: 40px;"></i>';
                }
                ?>
                <div class="profile-info-nav d-none d-sm-block">
                    <span class="profile-name"><?= $user_nama; ?></span>
                    <span class="profile-role" style="color: #a7f3d0;"><?= ucfirst($user_role); ?></span>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile">
                <li>
                    <a class="dropdown-item item-profile" href="index.php?page=profil">
                        <i class="span-icon fas fa-user-circle"></i> My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item item-logout" href="logout.php">
                        <i class="span-icon fas fa-sign-out-alt"></i> Log Out
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <!-- BRANDING HEADER -->
                <div class="sidebar-brand-box">
                    <div class="sidebar-logo-web"><i class="fas fa-home"></i></div>
                    <div class="sidebar-brand-text">
                        <h6>SIMDES</h6>
                        <small class="sidebar-brand-subtitle">Manajemen Desa</small>
                    </div>
                </div>

                <!-- MENU ITEMS (Ditambahkan Class Active Dinamis Berdasarkan URL) -->
                <div class="sb-sidenav-menu">
                    <div class="nav mt-3">
                        <a class="nav-link <?= ($page == 'dashboard') ? 'active-green' : ''; ?>"
                            href="index.php?page=dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Dashboard
                            <?= ($page == 'dashboard') ? '<div class="active-dot"></div>' : ''; ?>
                        </a>

                        <a class="nav-link collapsed <?= $isSuratOpen ? 'active-green' : ''; ?>" href="#"
                            data-bs-toggle="collapse" data-bs-target="#collapseSurat"
                            aria-expanded="<?= $isSuratOpen ? 'true' : 'false' ?>" aria-controls="collapseSurat">
                            <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                            Layanan Surat
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            <?= $isSuratOpen ? '<div class="active-dot"></div>' : ''; ?>
                        </a>
                        <div class="collapse <?= $isSuratOpen ? 'show' : ''; ?>" id="collapseSurat"
                            aria-labelledby="headingSurat" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">

                                <a class="nav-link <?= in_array($page, ['surat-garapan', 'surat-garapan-sawah', 'surat-garapan-tambah', 'surat-garapan-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-garapan-sawah">Surat Garapan Sawah</a>
                                <a class="nav-link <?= in_array($page, ['surat-keterangan-ahli-waris', 'surat_waris', 'surat-waris', 'tambah_surat_waris', 'surat-waris-tambah', 'edit_surat_waris', 'surat-waris-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-keterangan-ahli-waris">Surat Ahli Waris</a>
                                <a class="nav-link <?= in_array($page, ['surat-undangan', 'surat-undangan-tambah', 'surat-undangan-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-undangan">Surat Undangan</a>
                                <a class="nav-link <?= in_array($page, ['surat-kelahiran', 'surat_kelahiran', 'surat-kelahiran-tambah', 'surat_kelahiran-tambah', 'surat-kelahiran-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-kelahiran">Surat Kelahiran</a>
                                <a class="nav-link <?= in_array($page, ['surat-kematian', 'surat-kematian-tambah', 'surat-kematian-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-kematian">Surat Kematian</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed <?= $isKeteranganOpen ? 'active-green' : ''; ?>" href="#"
                            data-bs-toggle="collapse" data-bs-target="#collapseKeterangan"
                            aria-expanded="<?= $isKeteranganOpen ? 'true' : 'false' ?>"
                            aria-controls="collapseKeterangan">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-signature"></i></div>
                            Layanan Keterangan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            <?= $isKeteranganOpen ? '<div class="active-dot"></div>' : ''; ?>
                        </a>

                        <div class="collapse <?= $isKeteranganOpen ? 'show' : ''; ?>" id="collapseKeterangan"
                            aria-labelledby="headingKeterangan" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?= in_array($page, ['surat-keterangan-pengantar', 'surat-pengantar', 'surat-pengantar-tambah', 'surat-pengantar-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-keterangan-pengantar">Surat Keterangan / Pengantar</a>
                                <a class="nav-link <?= in_array($page, ['surat-domisili', 'surat-domisili-tambah', 'surat-domisili-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-domisili">Surat Domisili</a>
                                <a class="nav-link <?= in_array($page, ['surat-pengantar-dukcapil', 'surat-pengantar-dukcapil-tambah', 'surat-pengantar-dukcapil-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=surat-pengantar-dukcapil">Surat Pengantar Dukcapil</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed <?= $isSuratTidakMampuOpen ? 'active-green' : ''; ?>" href="#"
                            data-bs-toggle="collapse" data-bs-target="#collapseTidakMampu"
                            aria-expanded="<?= $isSuratTidakMampuOpen ? 'true' : 'false' ?>"
                            aria-controls="collapseTidakMampu">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-medical"></i></div>
                            Surat Keterangan Tidak Mampu
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            <?= $isSuratTidakMampuOpen ? '<div class="active-dot"></div>' : ''; ?>
                        </a>
                        <div class="collapse <?= $isSuratTidakMampuOpen ? 'show' : ''; ?>" id="collapseTidakMampu"
                            aria-labelledby="headingTidakMampu" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?= in_array($page, ['bumil', 'sktm-bumil', 'sktm-bumil-tampil', 'sktm-bumil-tambah', 'sktm-bumil-edit', 'sktm-bumil-hapus', 'sktm-bumil-proses-tambah'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=sktm-bumil-tampil">Bumil</a>
                                <a class="nav-link <?= in_array($page, ['pembebasan-rawat-inab-dan-jalan', 'sktm_rawat', 'sktm-rawat', 'sktm-rawat-tampil', 'sktm-rawat-tambah', 'sktm-rawat-edit', 'sktm-rawat-hapus'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=pembebasan-rawat-inab-dan-jalan">Pembebasan Rawat Inap dan
                                    Jalan</a>
                                <a class="nav-link <?= in_array($page, ['sktm-kis', 'sktm-kis-tampil', 'sktm-kis-tambah', 'sktm-kis-edit'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=sktm-kis">SKTM KIS</a>
                                <a class="nav-link <?= in_array($page, ['sktm-kip', 'sktm-kip-tampil', 'sktm-kip-tambah', 'sktm-kip-edit', 'sktm-kip-hapus'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=sktm-kip">SKTM KIP</a>
                                <a class="nav-link <?= in_array($page, ['stunting', 'stunting-tampil', 'stunting-tambah', 'stunting-edit', 'stunting-hapus', 'sktm-stunting', 'sktm-stunting-tampil', 'sktm-stunting-tambah', 'sktm-stunting-edit', 'sktm-stunting-hapus'], true) ? 'active-green' : ''; ?>"
                                    href="index.php?page=stunting">Stunting</a>
                            </nav>
                        </div>
                        <a class="nav-link <?= ($page == 'penduduk') ? 'active-green' : ''; ?>"
                            href="index.php?page=penduduk">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Penduduk
                            <?= ($page == 'penduduk') ? '<div class="active-dot"></div>' : ''; ?>
                        </a>
                        <a class="nav-link <?= ($page == 'chart') ? 'active-green' : ''; ?>"
                            href="index.php?page=chart">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Grafik & Statistik
                            <?= ($page == 'chart') ? '<div class="active-dot"></div>' : ''; ?>
                        </a>
                        <a class="nav-link <?= ($page == 'daftar-surat') ? 'active-green' : ''; ?>"
                            href="index.php?page=daftar-surat">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Daftar Semua Surat
                            <?= ($page == 'daftar-surat') ? '<div class="active-dot"></div>' : ''; ?>
                        </a>
                        <a class="nav-link <?= ($page == 'profil-desa') ? 'active-green' : ''; ?>"
                            href="index.php?page=profil-desa">
                            <div class="sb-nav-link-icon"><i class="fas fa-map-marked-alt"></i></div>
                            Profil Desa
                            <?= ($page == 'profil-desa') ? '<div class="active-dot"></div>' : ''; ?>
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div>&copy; 2026 SIMDES</div>
                    <div class="small" style="opacity: 0.7;">v1.0.0</div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <!-- IMPLEMENTASI SWITCH CASE UNTUK HALAMAN DINAMIS -->
                    <?php
                    switch ($page) {
                        case 'profil':
                            // Menyertakan file halaman profil eksternal
                            if (file_exists('profil.php')) {
                                include 'profil.php';
                            } else {
                                echo "<h2 class='mt-4'>Halaman Profil Belum Dibuat</h2>";
                            }
                            break;
                        case 'surat-garapan-sawah':
                            include "pages/surat_garapan/tampil.php";
                            break;
                        case 'surat-garapan-tambah':
                            include "pages/surat_garapan/tambah.php";
                            break;
                        case 'surat-garapan-edit':
                            include "pages/surat_garapan/edit.php";
                            break;
                        case 'surat-kelahiran':
                        case 'surat_kelahiran':
                            include "pages/surat_kelahiran/tampil.php";
                            break;
                        case 'surat-kelahiran-tambah':
                        case 'surat_kelahiran-tambah':
                            include "pages/surat_kelahiran/tambah.php";
                            break;
                        case 'surat-kelahiran-edit':
                            include "pages/surat_kelahiran/edit.php";
                            break;
                        case 'surat-kematian':
                            include "pages/surat_kematian/tampil.php";
                            break;
                        case 'surat-kematian-tambah':
                            include "pages/surat_kematian/tambah.php";
                            break;
                        case 'surat-kematian-edit':
                            include "pages/surat_kematian/edit.php";
                            break;
                        case 'surat-keterangan-ahli-waris':
                        case 'surat_waris':
                        case 'surat-waris':
                            include 'pages/surat_ahli_waris/tampil.php';
                            break;
                        case 'surat-undangan':
                            include 'pages/surat_undangan/tampil.php';
                            break;
                        case 'surat-undangan-tambah':
                            include 'pages/surat_undangan/tambah.php';
                            break;
                        case 'surat-undangan-edit':
                            include 'pages/surat_undangan/edit.php';
                            break;
                        case 'tambah_surat_waris':
                        case 'surat-waris-tambah':
                            include 'pages/surat_ahli_waris/tambah.php';
                            break;
                        case 'edit_surat_waris':
                        case 'surat-waris-edit':
                            include 'pages/surat_ahli_waris/edit.php';
                            break;
                        case 'surat-pengantar':
                        case 'surat_pengantar':
                            include 'pages/surat_keterangan_pengantar/tampil.php';
                            break;
                        case 'surat-pengantar-tambah':
                            include 'pages/surat_keterangan_pengantar/tambah.php';
                            break;
                        case 'surat-pengantar-edit':
                            include 'pages/surat_keterangan_pengantar/edit.php';
                            break;
                        case 'surat-keterangan-pengantar':
                            include 'pages/surat_keterangan_pengantar/tampil.php';
                            break;
                        case 'surat-domisili':
                            include 'pages/surat_domisili/tampil.php';
                            break;
                        case 'surat-domisili-tambah':
                            include 'pages/surat_domisili/tambah.php';
                            break;
                        case 'surat-domisili-edit':
                            include 'pages/surat_domisili/edit.php';
                            break;
                        case 'surat-domisili-hapus':
                            include 'pages/surat_domisili/hapus.php';
                            break;
                        case 'surat-domisili-cetak':
                            include 'pages/surat_domisili/cetak.php';
                            break;
                        case 'surat-keterangan-tidak-mampu':
                            echo "<h1 class='mt-4'>Surat Keterangan Tidak Mampu</h1>";
                            echo "<ol class='breadcrumb mb-4'><li class='breadcrumb-item active'>Form untuk Surat Keterangan Tidak Mampu</li></ol>";
                            break;
                        case 'bumil':
                        case 'sktm-bumil':
                        case 'sktm-bumil-tampil':
                            include 'pages/sktm_bumil/tampil.php';
                            break;
                        case 'sktm-bumil-tambah':
                            include "pages/sktm_bumil/tambah.php";
                            break;
                        case 'sktm-bumil-edit':
                            include 'pages/sktm_bumil/edit.php';
                            break;
                        case 'sktm-bumil-hapus':
                            include 'pages/sktm_bumil/hapus.php';
                            break;
                        case 'sktm-bumil-proses-tambah':
                            include 'pages/sktm_bumil/proses_tambah.php';
                            break;
                        case 'pembebasan-rawat-inab-dan-jalan':
                        case 'sktm_rawat':
                        case 'sktm-rawat':
                        case 'sktm-rawat-tampil':
                            include 'pages/sktm_rawat/tampil.php';
                            break;
                        case 'sktm-rawat-tambah':
                            include 'pages/sktm_rawat/tambah.php';
                            break;
                        case 'sktm-rawat-edit':
                            include 'pages/sktm_rawat/edit.php';
                            break;
                        case 'sktm-rawat-hapus':
                            include 'pages/sktm_rawat/hapus.php';
                            break;
                        case 'sktm-kip':
                        case 'sktm-kip-tampil':
                            include 'pages/sktm_kip/tampil.php';
                            break;
                        case 'sktm-kip-tambah':
                            include 'pages/sktm_kip/tambah.php';
                            break;
                        case 'sktm-kip-edit':
                            include 'pages/sktm_kip/edit.php';
                            break;
                        case 'sktm-kip-hapus':
                            include 'pages/sktm_kip/hapus.php';
                            break;
                        case 'sktm-kis':
                        case 'sktm-kis-tampil':
                            include 'pages/sktm_kis/tampil.php';
                            break;
                        case 'sktm-kis-tambah':
                            include 'pages/sktm_kis/tambah.php';
                            break;
                        case 'sktm-kis-edit':
                            include 'pages/sktm_kis/edit.php';
                            break;
                        case 'stunting':
                        case 'stunting-tampil':
                        case 'sktm-stunting':
                        case 'sktm-stunting-tampil':
                            include 'pages/sktm_stunting/tampil.php';
                            break;
                        case 'stunting-tambah':
                        case 'sktm-stunting-tambah':
                            include 'pages/sktm_stunting/tambah.php';
                            break;
                        case 'stunting-edit':
                        case 'sktm-stunting-edit':
                            include 'pages/sktm_stunting/edit.php';
                            break;
                        case 'stunting-hapus':
                        case 'sktm-stunting-hapus':
                            include 'pages/sktm_stunting/proses_hapus.php';
                            break;

                        case 'surat-pengantar-dukcapil':
                            include 'pages/surat_pengantar_dukcapil/tampil.php';
                            break;
                        case 'surat-pengantar-dukcapil-tambah':
                            include 'pages/surat_pengantar_dukcapil/tambah.php';
                            break;
                        case 'surat-pengantar-dukcapil-proses-tambah':
                            include 'pages/surat_pengantar_dukcapil/proses_tambah.php';
                            break;
                        case 'surat-pengantar-dukcapil-edit':
                            include "pages/surat_pengantar_dukcapil/edit.php"; // Sesuaikan dengan lokasi file edit Anda
                            break;
                        case 'daftar-surat':
                            include 'pages/daftar_surat.php';
                            break;
                        case 'penduduk':
                            include 'pages/penduduk.php'; // Atau letak file penduduk kamu
                            break;
                        case 'chart':
                            include 'pages/chart.php';
                            break;
                        case 'tambah-penduduk':
                            include 'pages/tambah-penduduk.php';
                            break;
                        case 'edit-penduduk':
                            include 'pages/edit-penduduk.php';
                            break;
                        case 'laporan':
                            echo "<h1 class='mt-4'>Laporan</h1>";
                            echo "<ol class='breadcrumb mb-4'><li class='breadcrumb-item active'>Statistik Kependudukan & Layanan Desa</li></ol>";
                            break;
                        case 'profil-desa':
                            include 'profil_desa.php';
                            break;

                        case 'dashboard':
                        default:
                            // Hitung jumlah data setiap layanan (aman walau nama tabel sedikit berbeda)
                            $cnt_garapan = simdes_count_table($koneksi, ['tb_surat_garapan', 'surat_garapan']);
                            $cnt_waris = simdes_count_table($koneksi, ['tb_surat_waris', 'surat_waris']);
                            $cnt_undangan = simdes_count_table($koneksi, ['tb_surat_undangan', 'surat_undangan']);
                            $cnt_kelahiran = simdes_count_table($koneksi, ['tb_surat_kelahiran', 'surat_kelahiran']);
                            $cnt_kematian = simdes_count_table($koneksi, ['tb_surat_kematian', 'surat_kematian']);

                            $cnt_pengantar = simdes_count_table($koneksi, ['tb_surat_pengantar', 'surat_pengantar']);
                            $cnt_domisili = simdes_count_table($koneksi, ['tb_surat_domisili', 'surat_domisili']);
                            $cnt_dukcapil = simdes_count_table($koneksi, ['tb_surat_pengantar_dukcapil', 'tb_surat_dukcapil', 'surat_pengantar_dukcapil', 'surat_dukcapil']);

                            $cnt_bumil = simdes_count_table($koneksi, ['tb_sktm_bumil', 'sktm_bumil']);
                            $cnt_rawat = simdes_count_table($koneksi, ['tb_sktm_rawat', 'sktm_rawat', 'tb_sktm_pasien', 'sktm_pasien']);
                            $cnt_kis = simdes_count_table($koneksi, ['tb_sktm_kis', 'sktm_kis']);
                            $cnt_kip = simdes_count_table($koneksi, ['tb_sktm_kip', 'sktm_kip']);
                            $cnt_stunting = simdes_count_table($koneksi, ['tb_sktm_stunting', 'sktm_stunting']);

                            $cnt_penduduk = simdes_count_table($koneksi, ['tb_penduduk', 'penduduk', 'tb_warga', 'warga']);

                            $total_surat = $cnt_garapan + $cnt_waris + $cnt_undangan + $cnt_kelahiran + $cnt_kematian;
                            $total_keterangan = $cnt_pengantar + $cnt_domisili + $cnt_dukcapil;
                            $total_sktm = $cnt_bumil + $cnt_rawat + $cnt_kis + $cnt_kip + $cnt_stunting;
                            $total_layanan = $total_surat + $total_keterangan + $total_sktm;

                            $hari_indo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                            $bulan_indo = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
                            $tanggal_hari_ini = $hari_indo[date('l')] . ', ' . date('j') . ' ' . $bulan_indo[date('F')] . ' ' . date('Y');
                            ?>

                            <!-- HERO GREETING -->
                            <div class="dash-hero mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <h2>Selamat Datang, <?= htmlspecialchars($user_nama ?: 'Admin'); ?> 👋</h2>
                                    <p>Berikut ringkasan layanan administrasi desa hari ini. Semoga harimu lancar dan
                                        produktif.</p>
                                </div>
                                <div class="dash-date-badge">
                                    <i class="fas fa-calendar-alt me-2"></i><?= $tanggal_hari_ini; ?>
                                </div>
                            </div>

                            <!-- RINGKASAN TOTAL -->
                            <div class="row g-4 mt-1">
                                <div class="col-xl-3 col-md-6">
                                    <a href="index.php?page=daftar-surat" class="stat-card-modern" title="Lihat semua layanan"
                                        style="text-decoration:none;">
                                        <div class="stat-icon-box" style="background:#007f3e;"><i
                                                class="fas fa-layer-group"></i></div>
                                        <div>
                                            <div class="stat-value"><?= number_format($total_layanan); ?></div>
                                            <div class="stat-label">Total Seluruh Layanan</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="stat-card-modern">
                                        <div class="stat-icon-box" style="background:#2563eb;"><i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <div class="stat-value"><?= number_format($total_surat); ?></div>
                                            <div class="stat-label">Layanan Surat</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="stat-card-modern">
                                        <div class="stat-icon-box" style="background:#16a34a;"><i
                                                class="fas fa-file-signature"></i></div>
                                        <div>
                                            <div class="stat-value"><?= number_format($total_keterangan); ?></div>
                                            <div class="stat-label">Layanan Keterangan</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="stat-card-modern">
                                        <div class="stat-icon-box" style="background:#d97706;"><i
                                                class="fas fa-hand-holding-medical"></i></div>
                                        <div>
                                            <div class="stat-value"><?= number_format($total_sktm); ?></div>
                                            <div class="stat-label">Keterangan Tidak Mampu</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LAYANAN SURAT -->
                            <div class="dash-section-title">
                                <div class="bar"></div>
                                <h6>Layanan Surat</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-garapan-sawah" class="menu-tile">
                                        <div class="tile-icon" style="background:#2563eb;"><i class="fas fa-tractor"></i></div>
                                        <div>
                                            <span class="tile-title">Garapan Sawah</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_garapan; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-keterangan-ahli-waris" class="menu-tile">
                                        <div class="tile-icon" style="background:#2563eb;"><i class="fas fa-scroll"></i></div>
                                        <div>
                                            <span class="tile-title">Ahli Waris</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_waris; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-undangan" class="menu-tile">
                                        <div class="tile-icon" style="background:#2563eb;"><i
                                                class="fas fa-envelope-open-text"></i></div>
                                        <div>
                                            <span class="tile-title">Undangan</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_undangan; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-kelahiran" class="menu-tile">
                                        <div class="tile-icon" style="background:#2563eb;"><i class="fas fa-baby"></i></div>
                                        <div>
                                            <span class="tile-title">Kelahiran</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_kelahiran; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-kematian" class="menu-tile">
                                        <div class="tile-icon" style="background:#2563eb;"><i class="fas fa-cross"></i></div>
                                        <div>
                                            <span class="tile-title">Kematian</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_kematian; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- LAYANAN KETERANGAN -->
                            <div class="dash-section-title">
                                <div class="bar" style="background:#16a34a;"></div>
                                <h6>Layanan Keterangan</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-keterangan-pengantar" class="menu-tile">
                                        <div class="tile-icon" style="background:#16a34a;"><i class="fas fa-file-signature"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">Keterangan / Pengantar</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_pengantar; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-domisili" class="menu-tile">
                                        <div class="tile-icon" style="background:#16a34a;"><i class="fas fa-house-user"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">Domisili</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_domisili; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=surat-pengantar-dukcapil" class="menu-tile">
                                        <div class="tile-icon" style="background:#16a34a;"><i class="fas fa-id-card"></i></div>
                                        <div>
                                            <span class="tile-title">Pengantar Dukcapil</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_dukcapil; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- SKTM -->
                            <div class="dash-section-title">
                                <div class="bar" style="background:#d97706;"></div>
                                <h6>Surat Keterangan Tidak Mampu</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=sktm-bumil-tampil" class="menu-tile">
                                        <div class="tile-icon" style="background:#d97706;"><i class="fas fa-baby-carriage"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">Ibu Hamil (Bumil)</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_bumil; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=pembebasan-rawat-inab-dan-jalan" class="menu-tile">
                                        <div class="tile-icon" style="background:#d97706;"><i class="fas fa-procedures"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">Rawat Inap / Jalan</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_rawat; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=sktm-kis" class="menu-tile">
                                        <div class="tile-icon" style="background:#d97706;"><i class="fas fa-notes-medical"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">SKTM KIS</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_kis; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=sktm-kip" class="menu-tile">
                                        <div class="tile-icon" style="background:#d97706;"><i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">SKTM KIP</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_kip; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=stunting" class="menu-tile">
                                        <div class="tile-icon" style="background:#d97706;"><i class="fas fa-child"></i></div>
                                        <div>
                                            <span class="tile-title">Stunting</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_stunting; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- MENU LAINNYA -->
                            <div class="dash-section-title">
                                <div class="bar" style="background:#dc2626;"></div>
                                <h6>Menu Lainnya</h6>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=penduduk" class="menu-tile">
                                        <div class="tile-icon" style="background:#dc2626;"><i class="fas fa-users"></i></div>
                                        <div>
                                            <span class="tile-title">Data Penduduk</span>
                                            <span class="tile-count"><span
                                                    class="badge-count"><?= $cnt_penduduk; ?></span>data</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-4 col-6">
                                    <a href="index.php?page=profil-desa" class="menu-tile">
                                        <div class="tile-icon" style="background:#64748b;"><i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        <div>
                                            <span class="tile-title">Profil Desa</span>
                                            <span class="tile-count">Sejarah &amp; pemerintahan</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <?php
                            break;
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        function setTheme(theme) {
            if (theme === "dark") {
                document.body.classList.add("dark-mode");
            } else if (theme === "light") {
                document.body.classList.remove("dark-mode");
            } else {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.body.classList.add("dark-mode");
                } else {
                    document.body.classList.remove("dark-mode");
                }
            }
            localStorage.setItem("theme", theme);
        }

        const appSearchItems = <?= json_encode($appSearchItems, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS) ?>;

        function normalizeSearchText(value) {
            return (value || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function getMatchingSearchItems(query) {
            const searchQuery = normalizeSearchText(query || '');
            if (!searchQuery) {
                return [];
            }

            return appSearchItems.filter((item) => {
                const haystack = normalizeSearchText(`${item.label} ${item.description} ${item.keywords}`);
                return haystack.includes(searchQuery);
            }).slice(0, 6);
        }

        function hideSearchDropdown() {
            const dropdown = document.getElementById('navbar-search-dropdown');
            if (dropdown) {
                dropdown.classList.remove('show');
                dropdown.style.display = 'none';
            }
        }

        function showSearchDropdown(query, results) {
            const dropdown = document.getElementById('navbar-search-dropdown');
            if (!dropdown) {
                return;
            }

            if (!query) {
                hideSearchDropdown();
                return;
            }

            let body = '';
            if (results.length > 0) {
                body = `
                <div class="search-dropdown-body">
                    <div>Temukan modul atau halaman yang paling relevan untuk pencarian <strong>${query}</strong>.</div>
                    <div class="search-dropdown-list">
                        ${results.map((item) => `
                            <a class="search-dropdown-item" href="${item.href}">
                                <strong>${item.label}</strong>
                                <small>${item.description}</small>
                            </a>
                        `).join('')}
                    </div>
                </div>`;
            } else {
                body = `
                <div class="search-dropdown-body">
                    <div>Tidak ada hasil untuk <strong>${query}</strong>. Coba kata kunci lain seperti nama surat, modul, atau halaman.</div>
                </div>`;
            }

            dropdown.innerHTML = `
            <div class="search-dropdown-header">
                <span>${results.length > 0 ? `${results.length} hasil ditemukan` : 'Tidak ada hasil'}</span>
                <button class="search-dropdown-close" type="button" aria-label="Tutup pencarian">×</button>
            </div>
            ${body}
        `;
            dropdown.style.display = 'block';
            dropdown.classList.add('show');

            const closeButton = dropdown.querySelector('.search-dropdown-close');
            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    hideSearchDropdown();
                });
            }

            clearTimeout(window.searchDropdownTimer);
            window.searchDropdownTimer = setTimeout(hideSearchDropdown, 5000);
        }

        function applyNavbarSearch(query) {
            const searchQuery = (query || '').trim();
            const results = getMatchingSearchItems(searchQuery);
            showSearchDropdown(searchQuery, results);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('#navbarSearchForm input[name="q"]');
            const form = document.getElementById('navbarSearchForm');
            const searchGroup = document.querySelector('#navbarSearchForm .search-navbar-group');
            const dropdown = document.createElement('div');
            dropdown.id = 'navbar-search-dropdown';
            dropdown.className = 'search-dropdown';
            if (searchGroup) {
                searchGroup.appendChild(dropdown);
            }

            const initialQuery = new URLSearchParams(window.location.search).get('q') || '';
            if (searchInput) {
                searchInput.value = initialQuery;
            }
            applyNavbarSearch(initialQuery);

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    applyNavbarSearch(this.value);
                });
            }

            if (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const query = (searchInput ? searchInput.value : '').trim();
                    const results = getMatchingSearchItems(query);
                    showSearchDropdown(query, results);
                });
            }

            document.addEventListener('click', function (event) {
                if (!form || !form.contains(event.target)) {
                    hideSearchDropdown();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    hideSearchDropdown();
                }
            });
        });

        window.onload = function () {
            let theme = localStorage.getItem("theme") || "light";
            setTheme(theme);
        }
    </script>
</body>

</html>
