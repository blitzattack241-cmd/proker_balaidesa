<?php
// Pastikan koneksi database tersedia.
if (!isset($koneksi)) {
    require_once __DIR__ . '/../../koneksi.php';
}

// Ambil ID Surat dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Surat tidak valid!'); window.location.href='index.php?page=surat-pengantar-dukcapil';</script>";
    exit;
}

$id_surat = (int) $_GET['id'];

// Cari nama tabel yang aktif di database
$tableActive = 'tb_surat_dukcapil';
$tableCandidates = ['tb_surat_pengantar_dukcapil', 'tb_surat_dukcapil', 'surat_pengantar_dukcapil', 'surat_dukcapil'];
foreach ($tableCandidates as $tableCandidate) {
    $check = mysqli_query($koneksi, "SHOW TABLES LIKE '$tableCandidate'");
    if ($check && mysqli_num_rows($check) > 0) {
        $tableActive = $tableCandidate;
        break;
    }
}

// Query mengambil data surat lama
$query = mysqli_query($koneksi, "SELECT * FROM `$tableActive` WHERE id_surat = $id_surat");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data surat tidak ditemukan di database!'); window.location.href='index.php?page=surat-pengantar-dukcapil';</script>";
    exit;
}

// Proses Eksekusi Update saat Tombol Simpan diklik
if (isset($_POST['update_surat'])) {
    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $jenis_dikirim = mysqli_real_escape_string($koneksi, $_POST['jenis_dikirim']);
    $banyaknya = mysqli_real_escape_string($koneksi, $_POST['banyaknya']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    $update = mysqli_query($koneksi, "UPDATE `$tableActive` SET 
        nomor_surat = '$nomor_surat', 
        tanggal_surat = '$tanggal_surat', 
        jenis_dikirim = '$jenis_dikirim', 
        banyaknya = '$banyaknya', 
        keterangan = '$keterangan' 
        WHERE id_surat = $id_surat");

    if ($update) {
        echo "<script>
                alert('Data surat pengantar berhasil diperbarui!'); 
                window.location.href='index.php?page=surat-pengantar-dukcapil';
              </script>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Gagal mengupdate data: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Surat Pengantar Dukcapil</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?page=dashboard" class="text-decoration-none">Dashboard</a>
        </li>
        <li class="breadcrumb-item"><a href="index.php?page=surat-pengantar-dukcapil"
                class="text-decoration-none">Daftar Surat Pengantar</a></li>
        <li class="breadcrumb-item active">Edit Surat</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <!-- Header Card: Menggunakan warna hijau dan icon tabel/form persis seperti halaman utama & tambah -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-edit me-1"></i> Form Modifikasi Surat Pengantar Dukcapil
            </div>
            <a href="index.php?page=surat-pengantar-dukcapil" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <!-- Baris 1: No Surat & Tanggal Sejajar (Gaya Form Tambah) -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">No. Surat</label>
                        <input type="text" name="nomor_surat" class="form-control"
                            value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Tanggal</label>
                        <input type="date" name="tanggal_surat" class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_surat']); ?>" required>
                    </div>
                </div>

                <!-- Baris 2: Jenis Yang Dikirim -->
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Jenis Yang Dikirim</label>
                    <textarea name="jenis_dikirim" class="form-control" rows="4"
                        placeholder="Masukkan rincian jenis berkas..."
                        required><?= htmlspecialchars($data['jenis_dikirim']); ?></textarea>
                </div>

                <!-- Baris 3: Banyaknya Berkas -->
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Banyaknya</label>
                    <input type="text" name="banyaknya" class="form-control"
                        value="<?= htmlspecialchars($data['banyaknya']); ?>" placeholder="Contoh: 1 Bendel, 2 Lembar"
                        required>
                </div>

                <!-- Baris 4: Keterangan Tambahan -->
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"
                        placeholder="Contoh: Dikirim dengan hormat, untuk dipergunakan sebagaimana mestinya."
                        required><?= htmlspecialchars($data['keterangan']); ?></textarea>
                </div>

                <!-- Footer Tombol Aksi di Kiri/Kanan sesuai standar SIMDES Anda -->
                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php?page=surat-pengantar-dukcapil" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="update_surat" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>