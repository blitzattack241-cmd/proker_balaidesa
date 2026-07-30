<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor

// Ambil data pejabat untuk dropdown tanda tangan
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Proses Penyimpanan Data ketika Form Disubmit
if (isset($_POST['submit'])) {
    // Reservasi nomor surat definitif di sini (saat benar-benar disimpan),
    // bukan saat halaman form dibuka, agar nomor tidak bertambah saat batal/reload.
    $nomor_surat = mysqli_real_escape_string($koneksi, generateNomorSuratGlobal($koneksi, true));
    $nama_warga = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $kewarganegaraan = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $alamat_tinggal = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $no_kk = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $no_ktp = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $anggota_keluarga = mysqli_real_escape_string($koneksi, $_POST['anggota_keluarga']);
    $berlaku_mulai = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    // Array nama input file foto untuk looping proses upload
    $foto_fields = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar_tidur', 'foto_dapur', 'foto_kamar_mandi'];
    $uploaded_filenames = [];

    // Tentukan direktori penyimpanan file gambar
    $target_dir = "uploads/sktm_kis/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $upload_ok = true;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

    foreach ($foto_fields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $file_extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));

            // Validasi Ekstensi File
            if (in_array($file_extension, $allowed_extensions)) {
                // Penamaan file unik menggunakan format: jenis_foto_nik_timestamp.ekstensi
                $new_filename = $field . "_" . $no_ktp . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $new_filename;

                // Proses upload file ke direktori target
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $target_file)) {
                    $uploaded_filenames[$field] = $new_filename;
                } else {
                    $uploaded_filenames[$field] = NULL;
                }
            } else {
                $upload_ok = false;
                echo "<script>alert('Gagal! Format file untuk $field harus JPG, JPEG, PNG, atau WEBP.');</script>";
                break;
            }
        } else {
            $uploaded_filenames[$field] = NULL;
        }
    }

    // Jalankan query jika lolos validasi upload file
    if ($upload_ok) {
        $query_insert = "INSERT INTO tb_sktm_kis (
            nomor_surat, nama_warga, tempat_lahir, tanggal_lahir, jenis_kelamin, pekerjaan, agama, 
            kewarganegaraan, alamat_tinggal, no_kk, no_ktp, keperluan, anggota_keluarga, 
            berlaku_mulai, tanggal_surat, id_pejabat, 
            foto_depan, foto_ruang_tamu, foto_kamar_tidur, foto_dapur, foto_kamar_mandi
        ) VALUES (
            '$nomor_surat', '$nama_warga', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$pekerjaan', '$agama', 
            '$kewarganegaraan', '$alamat_tinggal', '$no_kk', '$no_ktp', '$keperluan', '$anggota_keluarga', 
            '$berlaku_mulai', '$tanggal_surat', '$id_pejabat',
            '{$uploaded_filenames['foto_depan']}', '{$uploaded_filenames['foto_ruang_tamu']}', 
            '{$uploaded_filenames['foto_kamar_tidur']}', '{$uploaded_filenames['foto_dapur']}', 
            '{$uploaded_filenames['foto_kamar_mandi']}'
        )";

        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>
                    alert('Data SKTM KIS berhasil ditambahkan!');
                    window.location.href = 'index.php?page=sktm-kis';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menyimpan data ke database: " . mysqli_real_escape_string($koneksi, mysqli_error($koneksi)) . "');
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
        background: linear-gradient(135deg, #198754, #157347) !important;
        border: none !important;
        font-weight: 600;
        padding: 10px 24px !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.2);
        color: white !important;
    }

    .btn-save-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(25, 135, 84, 0.35);
        color: white !important;
    }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Buat Surat Keterangan Tidak Mampu</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=sktm-kis" class="text-decoration-none">Daftar SKTM
                        KIS</a></li>
                <li class="breadcrumb-item active">Buat Surat Baru</li>
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
                        <label class="form-label fw-bold">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control bg-light"
                            value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                        <small class="text-muted" style="font-size: 0.75rem;">*Nomor terisi otomatis (Kode SKTM KIS:
                            474), tetap dapat diubah jika diperlukan.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Lengkap Pemohon</label>
                        <input type="text" name="nama_warga" class="form-control" placeholder="Masukkan nama pemohon"
                            required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat lahir" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Agama</label>
                        <input type="text" name="agama" class="form-control" value="Islam" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">No. KTP (NIK)</label>
                        <input type="text" name="no_ktp" class="form-control" minlength="16" maxlength="16"
                            placeholder="16 Digit NIK" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">No. Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" class="form-control" minlength="16" maxlength="16"
                            placeholder="16 Digit No. KK" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan saat ini"
                            required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" class="form-control" value="WNI" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_tinggal" class="form-control" rows="3"
                            placeholder="Alamat tinggal pemohon..." required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3"
                            placeholder="Contoh: Permohonan Pengajuan Peralihan BPJS Mandiri kelas 3 ke KIS/JKN APBD..."
                            required></textarea>
                    </div>
                </div>

                <!-- BAGIAN 2: DETAIL ANGGOTA KELUARGA -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-users me-2 text-primary"></i>Anggota Keluarga yang Didaftarkan
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Daftar Nama & NIK Anggota Keluarga</label>
                    <textarea name="anggota_keluarga" class="form-control" rows="4"
                        placeholder="Format penulisan bebas, disarankan:&#10;1. NAMA (NIK)&#10;2. NAMA (NIK)"
                        required></textarea>
                    <div class="form-text text-muted">Tuliskan nama anggota keluarga beserta NIK-nya masing-masing yang
                        diusulkan mendapat KIS.</div>
                </div>

                <!-- BAGIAN 3: PARAMETER SURAT & TANDA TANGAN -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Legalitas & Penandatanganan
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Berlaku Mulai</label>
                        <input type="date" name="berlaku_mulai" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tanggal Surat Dibuat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Pejabat Penandatangan</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat Desa --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)) { ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>">
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- BAGIAN 4: FOTO DOKUMENTASI KONDISI RUMAH -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-camera me-2 text-primary"></i>Foto Dokumentasi Rumah (Opsional)
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Depan Rumah</label>
                        <input type="file" name="foto_depan" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Ruang Tamu</label>
                        <input type="file" name="foto_ruang_tamu" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Kamar Tidur</label>
                        <input type="file" name="foto_kamar_tidur" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Dapur</label>
                        <input type="file" name="foto_dapur" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Foto Kamar Mandi</label>
                        <input type="file" name="foto_kamar_mandi" class="form-control" accept="image/*">
                    </div>
                </div>

                <!-- Tombol Submit & Kembali -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php?page=sktm-kis" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" name="submit" class="btn btn-success btn-save-modern">
                        <i class="fas fa-save me-1"></i> Simpan Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>