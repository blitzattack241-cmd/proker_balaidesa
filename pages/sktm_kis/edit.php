<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// Cek apakah parameter ID ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID tidak valid!'); window.location.href = 'index.php?page=sktm-kis';</script>";
    exit;
}

$id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data lama untuk ditampilkan di form
$query_old = mysqli_query($koneksi, "SELECT * FROM tb_sktm_kis WHERE id_sktm = '$id_sktm'");
if (mysqli_num_rows($query_old) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href = 'index.php?page=sktm-kis';</script>";
    exit;
}
$data_lama = mysqli_fetch_assoc($query_old);

// Ambil data pejabat untuk dropdown tanda tangan
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Proses Penyimpanan Data ketika Form Disubmit
if (isset($_POST['submit'])) {
    $nomor_surat      = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $nama_warga       = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $tempat_lahir     = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $pekerjaan        = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $agama            = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $kewarganegaraan  = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $alamat_tinggal   = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $no_kk            = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $no_ktp           = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $keperluan        = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $anggota_keluarga = mysqli_real_escape_string($koneksi, $_POST['anggota_keluarga']);
    $berlaku_mulai    = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $tanggal_surat    = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $id_pejabat       = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    $foto_fields = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar_tidur', 'foto_dapur', 'foto_kamar_mandi'];
    $uploaded_filenames = [];
    $target_dir = "uploads/sktm_kis/";
    $upload_ok = true;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

    foreach ($foto_fields as $field) {
        // Jika user mengunggah berkas foto baru
        if (!empty($_FILES[$field]['name'])) {
            $file_extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = $field . "_" . $no_ktp . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES[$field]['tmp_name'], $target_file)) {
                    $uploaded_filenames[$field] = $new_filename;
                    
                    // Hapus foto lama dari server agar folder uploads tidak penuh berkas sampah
                    if (!empty($data_lama[$field]) && file_exists($target_dir . $data_lama[$field])) {
                        unlink($target_dir . $data_lama[$field]);
                    }
                } else {
                    $uploaded_filenames[$field] = $data_lama[$field]; // Gagal upload, pakai yang lama
                }
            } else {
                $upload_ok = false;
                echo "<script>alert('Gagal! Format file untuk $field harus JPG, JPEG, PNG, atau WEBP.');</script>";
                break;
            }
        } else {
            // Jika dikosongkan, tetap gunakan nama file foto lama
            $uploaded_filenames[$field] = $data_lama[$field];
        }
    }

    if ($upload_ok) {
        // Query update data berdasarkan id_sktm
        $query_update = "UPDATE tb_sktm_kis SET 
            nomor_surat = '$nomor_surat', 
            nama_warga = '$nama_warga', 
            tempat_lahir = '$tempat_lahir', 
            tanggal_lahir = '$tanggal_lahir', 
            jenis_kelamin = '$jenis_kelamin', 
            pekerjaan = '$pekerjaan', 
            agama = '$agama', 
            kewarganegaraan = '$kewarganegaraan', 
            alamat_tinggal = '$alamat_tinggal', 
            no_kk = '$no_kk', 
            no_ktp = '$no_ktp', 
            keperluan = '$keperluan', 
            anggota_keluarga = '$anggota_keluarga', 
            berlaku_mulai = '$berlaku_mulai', 
            tanggal_surat = '$tanggal_surat', 
            id_pejabat = '$id_pejabat',
            foto_depan = '{$uploaded_filenames['foto_depan']}', 
            foto_ruang_tamu = '{$uploaded_filenames['foto_ruang_tamu']}', 
            foto_kamar_tidur = '{$uploaded_filenames['foto_kamar_tidur']}', 
            foto_dapur = '{$uploaded_filenames['foto_dapur']}', 
            foto_kamar_mandi = '{$uploaded_filenames['foto_kamar_mandi']}'
            WHERE id_sktm = '$id_sktm'";

        if (mysqli_query($koneksi, $query_update)) {
            echo "<script>
                    alert('Data SKTM KIS berhasil diperbarui!');
                    window.location.href = 'index.php?page=sktm-kis';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui data: " . mysqli_real_escape_string($koneksi, mysqli_error($koneksi)) . "');
                  </script>";
        }
    }
}
?>

<style>
.form-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #0d6efd;
    padding-bottom: 5px;
    margin-bottom: 20px;
}

.card-modern {
    border: none !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
}

.btn-save-modern {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7) !important;
    border: none !important;
    font-weight: 600;
    padding: 10px 24px !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
}

.btn-save-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(13, 110, 253, 0.35);
}

.img-preview-sm {
    max-height: 50px;
    border-radius: 5px;
    object-fit: cover;
    border: 1px solid #dee2e6;
}
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Ubah Data Surat Keterangan Tidak Mampu</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=sktm-kis" class="text-decoration-none">Daftar SKTM
                        KIS</a></li>
                <li class="breadcrumb-item active">Ubah Surat</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <div class="card card-modern mb-4">
        <div class="card-body p-4">
            <form action="" method="POST" enctype="multipart/form-data">

                <!-- BAGIAN 1: DATA UTAMA SURAT -->
                <div class="form-section-title">
                    <i class="fas fa-envelope-open-text me-2 text-primary"></i>Informasi Pokok Surat
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor Surat (Bagian Tengah)</label>
                        <input type="text" name="nomor_surat" class="form-control"
                            value="<?= htmlspecialchars($data_lama['nomor_surat']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Lengkap Pemohon</label>
                        <input type="text" name="nama_warga" class="form-control"
                            value="<?= htmlspecialchars($data_lama['nama_warga']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            value="<?= htmlspecialchars($data_lama['tempat_lahir']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="<?= htmlspecialchars($data_lama['tanggal_lahir']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-Laki"
                                <?= ($data_lama['jenis_kelamin'] == 'Laki-Laki') ? 'selected' : ''; ?>>Laki-Laki
                            </option>
                            <option value="Perempuan"
                                <?= ($data_lama['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Agama</label>
                        <input type="text" name="agama" class="form-control"
                            value="<?= htmlspecialchars($data_lama['agama']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">No. KTP (NIK)</label>
                        <input type="text" name="no_ktp" class="form-control" minlength="16" maxlength="16"
                            value="<?= htmlspecialchars($data_lama['no_ktp']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">No. Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" class="form-control" minlength="16" maxlength="16"
                            value="<?= htmlspecialchars($data_lama['no_kk']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control"
                            value="<?= htmlspecialchars($data_lama['pekerjaan']); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" class="form-control"
                            value="<?= htmlspecialchars($data_lama['kewarganegaraan']); ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_tinggal" class="form-control" rows="3"
                            required><?= htmlspecialchars($data_lama['alamat_tinggal']); ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3"
                            required><?= htmlspecialchars($data_lama['keperluan']); ?></textarea>
                    </div>
                </div>

                <!-- BAGIAN 2: DETAIL ANGGOTA KELUARGA -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-users me-2 text-primary"></i>Anggota Keluarga yang Didaftarkan
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Daftar Nama & NIK Anggota Keluarga</label>
                    <textarea name="anggota_keluarga" class="form-control" rows="4"
                        required><?= htmlspecialchars($data_lama['anggota_keluarga']); ?></textarea>
                </div>

                <!-- BAGIAN 3: PARAMETER SURAT & TANDA TANGAN -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Legalitas & Penandatanganan
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Berlaku Mulai</label>
                        <input type="date" name="berlaku_mulai" class="form-control"
                            value="<?= htmlspecialchars($data_lama['berlaku_mulai']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tanggal Surat Dibuat</label>
                        <input type="date" name="tanggal_surat" class="form-control"
                            value="<?= htmlspecialchars($data_lama['tanggal_surat']); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Pejabat Penandatangan</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat Desa --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)) { ?>
                            <option value="<?= $pejabat['id_pejabat']; ?>"
                                <?= ($data_lama['id_pejabat'] == $pejabat['id_pejabat']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- BAGIAN 4: FOTO DOKUMENTASI KONDISI RUMAH -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi Rumah (Biarkan kosong jika tidak
                    diubah)
                </div>
                <div class="row mb-3">
                    <!-- Foto Depan -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Depan Rumah</label>
                        <input type="file" name="foto_depan" class="form-control mb-1" accept="image/*">
                        <?php if(!empty($data_lama['foto_depan'])): ?>
                        <div class="d-flex align-items-center gap-2"><img
                                src="uploads/sktm_kis/<?= $data_lama['foto_depan']; ?>" class="img-preview-sm"><span
                                class="small text-muted">Ada berkas lama</span></div>
                        <?php endif; ?>
                    </div>
                    <!-- Foto Ruang Tamu -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Ruang Tamu</label>
                        <input type="file" name="foto_ruang_tamu" class="form-control mb-1" accept="image/*">
                        <?php if(!empty($data_lama['foto_ruang_tamu'])): ?>
                        <div class="d-flex align-items-center gap-2"><img
                                src="uploads/sktm_kis/<?= $data_lama['foto_ruang_tamu']; ?>"
                                class="img-preview-sm"><span class="small text-muted">Ada berkas lama</span></div>
                        <?php endif; ?>
                    </div>
                    <!-- Foto Kamar Tidur -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Kamar Tidur</label>
                        <input type="file" name="foto_kamar_tidur" class="form-control mb-1" accept="image/*">
                        <?php if(!empty($data_lama['foto_kamar_tidur'])): ?>
                        <div class="d-flex align-items-center gap-2"><img
                                src="uploads/sktm_kis/<?= $data_lama['foto_kamar_tidur']; ?>"
                                class="img-preview-sm"><span class="small text-muted">Ada berkas lama</span></div>
                        <?php endif; ?>
                    </div>
                    <!-- Foto Dapur -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Dapur</label>
                        <input type="file" name="foto_dapur" class="form-control mb-1" accept="image/*">
                        <?php if(!empty($data_lama['foto_dapur'])): ?>
                        <div class="d-flex align-items-center gap-2"><img
                                src="uploads/sktm_kis/<?= $data_lama['foto_dapur']; ?>" class="img-preview-sm"><span
                                class="small text-muted">Ada berkas lama</span></div>
                        <?php endif; ?>
                    </div>
                    <!-- Foto Kamar Mandi -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Kamar Mandi</label>
                        <input type="file" name="foto_kamar_mandi" class="form-control mb-1" accept="image/*">
                        <?php if(!empty($data_lama['foto_kamar_mandi'])): ?>
                        <div class="d-flex align-items-center gap-2"><img
                                src="uploads/sktm_kis/<?= $data_lama['foto_kamar_mandi']; ?>"
                                class="img-preview-sm"><span class="small text-muted">Ada berkas lama</span></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tombol Submit & Kembali -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php?page=sktm-kis" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" name="submit" class="btn btn-primary btn-save-modern text-white">
                        <i class="fas fa-save me-1"></i> Perbarui Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>