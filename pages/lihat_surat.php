<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
}

function tableExists(mysqli $koneksi, string $namaTable): bool
{
    $check = mysqli_query($koneksi, "SHOW TABLES LIKE '" . mysqli_real_escape_string($koneksi, $namaTable) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function columnExists(mysqli $koneksi, string $namaTable, string $namaKolom): bool
{
    $check = mysqli_query($koneksi, "SHOW COLUMNS FROM `" . mysqli_real_escape_string($koneksi, $namaTable) . "` LIKE '" . mysqli_real_escape_string($koneksi, $namaKolom) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

$table = $_GET['s'] ?? '';
$idCol = $_GET['col'] ?? '';
$idVal = $_GET['id'] ?? '';

if ($table === '' || $idCol === '' || $idVal === '') {
    echo "<div class='container-fluid p-4'><div class='alert alert-warning'>Parameter tidak lengkap.</div></div>";
    return;
}

$table = mysqli_real_escape_string($koneksi, $table);
$idCol = mysqli_real_escape_string($koneksi, $idCol);
// idVal may be numeric or string; we'll escape and quote
$idValEscaped = mysqli_real_escape_string($koneksi, $idVal);

if (!tableExists($koneksi, $table)) {
    echo "<div class='container-fluid p-4'><div class='alert alert-danger'>Tabel tidak ditemukan: " . htmlspecialchars($table) . "</div></div>";
    return;
}

if (!columnExists($koneksi, $table, $idCol)) {
    echo "<div class='container-fluid p-4'><div class='alert alert-danger'>Kolom kunci tidak ditemukan pada tabel.</div></div>";
    return;
}

$sql = "SELECT * FROM `" . $table . "` WHERE `" . $idCol . "` = '" . $idValEscaped . "' LIMIT 1";
$res = mysqli_query($koneksi, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    echo "<div class='container-fluid p-4'><div class='alert alert-info'>Data tidak ditemukan.</div></div>";
    return;
}

$row = mysqli_fetch_assoc($res);

?>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Detail Surat</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=daftar-surat" class="text-decoration-none">Daftar
                        Surat</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
        <a href="index.php?page=daftar-surat" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card card-modern">
        <div class="card-header-modern">
            <div class="card-header-title"><i class="fas fa-envelope-open-text me-2 text-primary"></i> Informasi Lengkap
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <?php foreach ($row as $col => $val): ?>
                            <tr>
                                <th style="width:30%;"><?php echo htmlspecialchars($col); ?></th>
                                <td><?php echo nl2br(htmlspecialchars((string) $val)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>