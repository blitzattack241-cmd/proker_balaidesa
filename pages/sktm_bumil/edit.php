<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = 'index.php?page=dashboard';
          </script>";
    exit;
}

// legacy direct connect removed; use centralized connection
// keep session and access checks above
// require central connection
require_once __DIR__ . '/../../koneksi.php';

// Pastikan parameter ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=sktm-bumil';
          </script>";
    exit;
}

$id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data SKTM Bumil lama berdasarkan ID
$query_data = mysqli_query($koneksi, "SELECT * FROM tb_sktm_bumil WHERE id_sktm = '$id_sktm'");
if (mysqli_num_rows($query_data) === 0) {
    echo "<script>
            alert('Data tidak ditemukan di database!');
            window.location.href = 'index.php?page=sktm-bumil';
          </script>";
    exit;
}
$data = mysqli_fetch_assoc($query_data);

// Ambil data opsi pejabat penandatangan
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY id_pejabat ASC");
?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Edit SKTM Ibu Hamil (Bumil)</h3>
    require_once __DIR__ . '/../../koneksi.php';
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="index.php?page=sktm-bumil">Daftar SKTM Bumil</a></li>
    <li class="breadcrumb-item active">Edit Surat</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i> Form Edit Data SKTM Ibu Hamil
        </div>
        <div class="card-body">
            <!-- Wajib menggunakan enctype="multipart/form-data" karena bisa melakukan update file -->
            <form action="pages/sktm_bumil/proses_edit.php" method="POST" enctype="multipart/form-data">

                <!-- ID Tersembunyi (Hidden) -->
                <input type="hidden" name="id_sktm" value="<?= $data['id_sktm']; ?>">

                <h5 class="text-primary mb-3"><i class="fas fa-envelope-open-text me-2"></i>Informasi Surat</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nomor_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                            value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                            value="<?= $data['tanggal_surat']; ?>" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3"><i class="fas fa-user-pregnant me-2"></i>Data Identitas Ibu Hamil</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_warga" class="form-label">Nama Lengkap Ibu</label>
                        <input type="text" class="form-control" id="nama_warga" name="nama_warga"
                            value="<?= htmlspecialchars($data['nama_warga']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>
                                Perempuan</option>
                            <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>
                                Laki-laki</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_ktp" class="form-label">Nomor NIK (KTP)</label>
                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" maxlength="16"
                            value="<?= htmlspecialchars($data['no_ktp']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="no_kk" class="form-label">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" class="form-control" id="no_kk" name="no_kk" maxlength="16"
                            value="<?= htmlspecialchars($data['no_kk']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                            value="<?= htmlspecialchars($data['tempat_lahir']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                            value="<?= $data['tanggal_lahir']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="agama" class="form-label">Agama</label>
                        <select class="form-select" id="agama" name="agama" required>
                            <?php
                            $list_agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'];
                            foreach ($list_agama as $ag) {
                                $selected = ($data['agama'] == $ag) ? 'selected' : '';
                                echo "<option value='$ag' $selected>$ag</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                            value="<?= htmlspecialchars($data['pekerjaan']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kewarganegaraan" class="form-label">Warganegaraan</label>
                        <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan"
                            value="<?= htmlspecialchars($data['kewarganegaraan']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat_tinggal" class="form-label">Alamat / Tempat Tinggal</label>
                    <textarea class="form-control" id="alamat_tinggal" name="alamat_tinggal" rows="2"
                        required><?= htmlspecialchars($data['alamat_tinggal']); ?></textarea>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3"><i class="fas fa-file-invoice me-2"></i>Detail Keperluan & Validitas</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="keperluan" class="form-label">Keperluan Utama</label>
                        <input type="text" class="form-control" id="keperluan" name="keperluan"
                            value="<?= htmlspecialchars($data['keperluan']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="berlaku_mulai" class="form-label">Berlaku Mulai</label>
                        <input type="date" class="form-control" id="berlaku_mulai" name="berlaku_mulai"
                            value="<?= $data['berlaku_mulai']; ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="berlaku_selesai" class="form-label">Sampai Dengan</label>
                        <input type="text" class="form-control" id="berlaku_selesai" name="berlaku_selesai"
                            value="<?= htmlspecialchars($data['berlaku_selesai']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan_lain" class="form-label">Keterangan Lain-lain (Narasi Bukti Tidak
                        Mampu)</label>
                    <textarea class="form-control" id="keterangan_lain" name="keterangan_lain" rows="3"
                        required><?= htmlspecialchars($data['keterangan_lain']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_pejabat" class="form-label">Pejabat Penandatangan Surat</label>
                        <select class="form-select" id="id_pejabat" name="id_pejabat" required>
                            <option value="">-- Pilih Pejabat Penandatangan --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>"
                                    <?= $data['id_pejabat'] == $pejabat['id_pejabat'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_camat" class="form-label">Nama Camat (Mengetahui)</label>
                        <input type="text" class="form-control" id="nama_camat" name="nama_camat"
                            value="<?= htmlspecialchars($data['nama_camat']); ?>" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3"><i class="fas fa-camera me-2"></i>Ganti Lampiran Kondisi Rumah (Biarkan
                    Kosong Jika Tidak Diubah)</h5>

                <!-- Sesi Upload File Foto Mengganti Berkas Lama -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="foto_depan" class="form-label fw-bold">1. Foto Tampak Depan</label>
                        <input type="file" class="form-control mb-1" id="foto_depan" name="foto_depan" accept="image/*">
                        <small class="text-muted d-block">File aktif: <span
                                class="text-success"><?= !empty($data['foto_depan']) ? $data['foto_depan'] : 'Belum ada foto'; ?></span></small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="foto_ruang_tamu" class="form-label fw-bold">2. Foto Ruang Tamu</label>
                        <input type="file" class="form-control mb-1" id="foto_ruang_tamu" name="foto_ruang_tamu"
                            accept="image/*">
                        <small class="text-muted d-block">File aktif: <span
                                class="text-success"><?= !empty($data['foto_ruang_tamu']) ? $data['foto_ruang_tamu'] : 'Belum ada foto'; ?></span></small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="foto_kamar" class="form-label fw-bold">3. Foto Kamar Tidur</label>
                        <input type="file" class="form-control mb-1" id="foto_kamar" name="foto_kamar" accept="image/*">
                        <small class="text-muted d-block">File aktif: <span
                                class="text-success"><?= !empty($data['foto_kamar']) ? $data['foto_kamar'] : 'Belum ada foto'; ?></span></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="foto_dapur" class="form-label fw-bold">4. Foto Bagian Dapur</label>
                        <input type="file" class="form-control mb-1" id="foto_dapur" name="foto_dapur" accept="image/*">
                        <small class="text-muted d-block">File aktif: <span
                                class="text-success"><?= !empty($data['foto_dapur']) ? $data['foto_dapur'] : 'Belum ada foto'; ?></span></small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="foto_toilet" class="form-label fw-bold">5. Foto Kamar Mandi / WC</label>
                        <input type="file" class="form-control mb-1" id="foto_toilet" name="foto_toilet"
                            accept="image/*">
                        <small class="text-muted d-block">File aktif: <span
                                class="text-success"><?= !empty($data['foto_toilet']) ? $data['foto_toilet'] : 'Belum ada foto'; ?></span></small>
                    </div>
                </div>

                <div class="mt-4 mb-2">
                    <button type="submit" name="update" class="btn btn-warning px-4 fw-bold"><i
                            class="fas fa-save me-1"></i> Simpan Perubahan</button>
                    <a href="index.php?page=sktm-bumil" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>