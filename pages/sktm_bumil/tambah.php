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

// Koneksi database jika belum di-include global
if (!isset($koneksi)) {
    $koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
}

// Query data pejabat penandatangan
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY id_pejabat ASC");

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor
?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Buat SKTM Ibu Hamil (Bumil)</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?page=sktm-bumil">Daftar SKTM Bumil</a></li>
        <li class="breadcrumb-item active">Tambah Surat</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-medical me-1"></i> Form Input Data SKTM Ibu Hamil
        </div>
        <div class="card-body">
            <!-- Wajib menggunakan enctype="multipart/form-data" karena ada input file/gambar -->
            <form action="pages/sktm_bumil/proses_tambah.php" method="POST" enctype="multipart/form-data">

                <h5 class="text-primary mb-3"><i class="fas fa-envelope-open-text me-2"></i>Informasi Surat</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nomor_surat" class="form-label">Nomor Surat</label>
                        <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                            value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                        <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis dengan format: 145 /
                            [URUT] / 31.07.16 / 2026</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                            value="<?= date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3"><i class="fas fa-user-pregnant me-2"></i>Data Identitas Ibu Hamil</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_warga" class="form-label">Nama Lengkap Ibu</label>
                        <input type="text" class="form-control" id="nama_warga" name="nama_warga"
                            placeholder="Nama lengkap sesuai KTP" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Perempuan" selected>Perempuan</option>
                            <option value="Laki-laki">Laki-laki (Untuk surat non-Bumil)</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_ktp" class="form-label">Nomor NIK (KTP)</label>
                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" maxlength="16"
                            placeholder="16 Digit NIK" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="no_kk" class="form-label">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" class="form-control" id="no_kk" name="no_kk" maxlength="16"
                            placeholder="16 Digit No. KK" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                            placeholder="Contoh: Kudus" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="agama" class="form-label">Agama</label>
                        <select class="form-select" id="agama" name="agama" required>
                            <option value="Islam" selected>Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                            placeholder="Contoh: Mengurus Rumah Tangga" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kewarganegaraan" class="form-label">Warganegaraan</label>
                        <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" value="WNI"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat_tinggal" class="form-label">Alamat / Tempat Tinggal</label>
                    <textarea class="form-control" id="alamat_tinggal" name="alamat_tinggal" rows="2"
                        placeholder="Contoh: RT 02 / RW 01 Desa Berugenjang Kec. Undaan Kab. Kudus" required></textarea>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3"><i class="fas fa-file-invoice me-2"></i>Detail Keperluan & Validitas</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="keperluan" class="form-label">Keperluan Utama</label>
                        <input type="text" class="form-control" id="keperluan" name="keperluan"
                            value="Persyaratan Mendapatkan Surat Keterangan Ibu Hamil (Bumil) Pembebasan Biaya Persalinan"
                            required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="berlaku_mulai" class="form-label">Berlaku Mulai</label>
                        <input type="date" class="form-control" id="berlaku_mulai" name="berlaku_mulai"
                            value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="berlaku_selesai" class="form-label">Sampai Dengan</label>
                        <input type="text" class="form-control" id="berlaku_selesai" name="berlaku_selesai"
                            value="Selesai" placeholder="Contoh: Selesai / 1 Bulan" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan_lain" class="form-label">Keterangan Lain-lain (Narasi Bukti Tidak
                        Mampu)</label>
                    <textarea class="form-control" id="keterangan_lain" name="keterangan_lain" rows="3"
                        required>Orang tersebut di atas benar-benar warga Desa Berugenjang Kecamatan Undaan Kabupaten Kudus, sepanjang pengetahuan kami keadaan ekonominya TIDAK MAMPU / MISKIN dan layak untuk diberikan pembebasan biaya perawatan/persalinan.</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_pejabat" class="form-label">Pejabat Penandatangan Surat</label>
                        <select class="form-select" id="id_pejabat" name="id_pejabat" required>
                            <option value="">-- Pilih Pejabat Penandatangan --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>">
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_camat" class="form-label">Nama Camat (Mengetahui)</label>
                        <input type="text" class="form-control" id="nama_camat" name="nama_camat"
                            placeholder="Nama Camat Undaan saat ini" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3"><i class="fas fa-camera me-2"></i>Dokumentasi Lampiran Kondisi Rumah (File
                    Gambar)</h5>
                <p class="text-muted small">* Format file wajib bertipe JPG/JPEG/PNG dengan ukuran ideal maksimal 2MB
                    per foto.</p>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="foto_depan" class="form-label fw-bold">1. Foto Tampak Depan Rumah</label>
                        <input type="file" class="form-control" id="foto_depan" name="foto_depan" accept="image/*"
                            required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="foto_ruang_tamu" class="form-label fw-bold">2. Foto Ruang Tamu</label>
                        <input type="file" class="form-control" id="foto_ruang_tamu" name="foto_ruang_tamu"
                            accept="image/*" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="foto_kamar" class="form-label fw-bold">3. Foto Kamar Tidur</label>
                        <input type="file" class="form-control" id="foto_kamar" name="foto_kamar" accept="image/*"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="foto_dapur" class="form-label fw-bold">4. Foto Bagian Dapur</label>
                        <input type="file" class="form-control" id="foto_dapur" name="foto_dapur" accept="image/*"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="foto_toilet" class="form-label fw-bold">5. Foto Kamar Mandi / Toilet</label>
                        <input type="file" class="form-control" id="foto_toilet" name="foto_toilet" accept="image/*"
                            required>
                    </div>
                </div>

                <div class="mt-4 mb-2">
                    <button type="submit" name="simpan" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i>
                        Simpan & Lanjutkan</button>
                    <a href="index.php?page=sktm-bumil" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>