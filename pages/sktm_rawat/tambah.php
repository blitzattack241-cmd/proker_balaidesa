<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = 'index.php?page=dashboard';
          </script>";
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
// --- LOGIC GENERATE NOMOR SURAT OTOMATIS ---
$tahun_sekarang = date('Y');

// Ambil nomor urut tertinggi pada tahun berjalan
$query_max_no = mysqli_query($koneksi, "
    SELECT MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_surat, '/', 2), '/', -1) AS UNSIGNED)) as max_no 
    FROM tb_sktm_rawat 
    WHERE nomor_surat LIKE '%/$tahun_sekarang'
");

$data_max = mysqli_fetch_assoc($query_max_no);
$no_urut_terakhir = $data_max['max_no'] ?? 0;

// Increment nomor urut
$no_urut_baru = $no_urut_terakhir + 1;

// Format 2 digit angka (contoh: 01, 02, dst.)
$no_urut_formatted = str_pad($no_urut_baru, 2, "0", STR_PAD_LEFT);

// Format Nomor Surat Sesuai Template Baru
$nomor_surat_otomatis = "145 / " . $no_urut_formatted . " / 31.07.16 / " . $tahun_sekarang;

// Ambil data pejabat penandatangan untuk dropdown
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Proses Simpan Data Form
if (isset($_POST['simpan'])) {
    // Ambil Data Pemohon & Surat
    $nomor_surat        = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $nama_pemohon       = mysqli_real_escape_string($koneksi, $_POST['nama_pemohon']);
    $tempat_lahir       = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir      = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin      = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $pekerjaan          = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $agama              = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $kewarganegaraan    = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $alamat_tinggal     = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $no_kk              = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $no_ktp             = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $rumah_sakit_tujuan = mysqli_real_escape_string($koneksi, $_POST['rumah_sakit_tujuan']);
    $berlaku_mulai      = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $tanggal_surat      = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $id_pejabat         = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    // Proses Unggah Gambar (5 Kategori Foto)
    $foto_fields = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
    $uploaded_filenames = [];
    $upload_dir = "assets/img/sktm_rawat/";

    // Pastikan folder penyimpanan ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $upload_ok = true;
    foreach ($foto_fields as $field) {
        $uploaded_filenames[$field] = null; // Default null jika tidak upload

        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $file_tmp   = $_FILES[$field]['tmp_name'];
            $file_name  = $_FILES[$field]['name'];
            $file_ext   = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Validasi Format
            $allowed_ext = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $allowed_ext)) {
                echo "<script>alert('Format file $field harus JPG, JPEG, atau PNG!');</script>";
                $upload_ok = false;
                break;
            }

            // Validasi Ukuran (Maksimal 2MB)
            if ($_FILES[$field]['size'] > 2 * 1024 * 1024) {
                echo "<script>alert('Ukuran file $field maksimal 2MB!');</script>";
                $upload_ok = false;
                break;
            }

            // Rename File Unik agar tidak bentrok
            $new_name = $field . "_" . time() . "_" . uniqid() . "." . $file_ext;
            
            if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                $uploaded_filenames[$field] = $new_name;
            } else {
                echo "<script>alert('Gagal mengunggah file $field!');</script>";
                $upload_ok = false;
                break;
            }
        }
    }

    if ($upload_ok) {
        // Query insert tabel utama
        $insert_sktm = "INSERT INTO tb_sktm_rawat (
            nomor_surat, nama_pemohon, tempat_lahir, tanggal_lahir, jenis_kelamin, 
            pekerjaan, agama, kewarganegaraan, alamat_tinggal, no_kk, no_ktp, 
            rumah_sakit_tujuan, berlaku_mulai, tanggal_surat, id_pejabat, 
            foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet
        ) VALUES (
            '$nomor_surat', '$nama_pemohon', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', 
            '$pekerjaan', '$agama', '$kewarganegaraan', '$alamat_tinggal', '$no_kk', '$no_ktp', 
            '$rumah_sakit_tujuan', '$berlaku_mulai', '$tanggal_surat', '$id_pejabat',
            '{$uploaded_filenames['foto_depan']}', '{$uploaded_filenames['foto_ruang_tamu']}', 
            '{$uploaded_filenames['foto_kamar']}', '{$uploaded_filenames['foto_dapur']}', 
            '{$uploaded_filenames['foto_toilet']}'
        )";

        if (mysqli_query($koneksi, $insert_sktm)) {
            $id_sktm_baru = mysqli_insert_id($koneksi);

            // Proses Insert Detail Pasien (Multiple Pasien/One-To-Many)
            if (isset($_POST['nama_pasien']) && is_array($_POST['nama_pasien'])) {
                $nama_pasien_arr = $_POST['nama_pasien'];
                $nik_pasien_arr  = $_POST['nik_pasien'];

                for ($i = 0; $i < count($nama_pasien_arr); $i++) {
                    $nama_p = mysqli_real_escape_string($koneksi, $nama_pasien_arr[$i]);
                    $nik_p  = mysqli_real_escape_string($koneksi, $nik_pasien_arr[$i]);

                    if (!empty($nama_p) && !empty($nik_p)) {
                        mysqli_query($koneksi, "
                            INSERT INTO tb_sktm_rawat_pasien (id_sktm, nama_pasien, nik_pasien) 
                            VALUES ('$id_sktm_baru', '$nama_p', '$nik_p')
                        ");
                    }
                }
            }

            echo "<script>
                    alert('Data Surat Pembebasan Rawat Berhasil Disimpan!');
                    window.location.href = 'index.php?page=sktm-rawat';
                  </script>";
        } else {
            echo "<script>alert('Gagal menyimpan data utama: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}
?>

<div class="container-fluid px-4 py-3">
    <!-- Header Alur Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h3 class="fw-bold text-dark mt-2 mb-1">Tambah SKTM Pembebasan Rawat</h3>
            <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-info text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=sktm-rawat"
                        class="text-info text-decoration-none">Daftar Surat</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
            <!-- SISI KIRI: DATA UTAMA PEMOHON -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold text-secondary mb-0"><i
                                class="fas fa-user-edit me-2 text-primary"></i>Data Identitas Pemohon</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Surat</label>
                                <input type="text" class="form-control bg-light" name="nomor_surat"
                                    value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                                <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis oleh sistem,
                                    namun tetap dapat disesuaikan jika perlu.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap Pemohon</label>
                                <input type="text" class="form-control" name="nama_pemohon"
                                    placeholder="Masukkan nama pemegang surat" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pekerjaan</label>
                                <input type="text" class="form-control" name="pekerjaan"
                                    placeholder="Contoh: Buruh Harian Lepas" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Agama</label>
                                <input type="text" class="form-control" name="agama" value="Islam" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kewarganegaraan</label>
                                <input type="text" class="form-control" name="kewarganegaraan" value="Indonesia"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">No. Kartu Keluarga (KK)</label>
                                <input type="text" class="form-control" name="no_kk" maxlength="16" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIK Pemohon (KTP)</label>
                                <input type="text" class="form-control" name="no_ktp" maxlength="16" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rumah Sakit/Puskesmas Tujuan</label>
                                <input type="text" class="form-control" name="rumah_sakit_tujuan"
                                    placeholder="Contoh: RS. MARDI RAHAYU Kudus" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Lengkap Tinggal</label>
                                <textarea class="form-control" name="alamat_tinggal" rows="2"
                                    placeholder="Tuliskan nama jalan, RT/RW, dukuh" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS: DAFTAR PASIEN -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div
                        class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold text-secondary mb-0"><i
                                class="fas fa-users me-2 text-success"></i>Daftar Pasien / Anggota Keluarga yang Sakit
                        </h5>
                        <button type="button" class="btn btn-sm btn-success fw-bold" id="add-pasien-row">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Pasien
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="container-pasien">
                            <!-- Baris input pertama (Default) -->
                            <div class="row g-2 mb-3 pasien-row">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.85rem;">Nama
                                        Pasien</label>
                                    <input type="text" name="nama_pasien[]" class="form-control"
                                        placeholder="Masukkan Nama Pasien" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold text-muted" style="font-size: 0.85rem;">NIK
                                        Pasien</label>
                                    <input type="text" name="nik_pasien[]" class="form-control" maxlength="16"
                                        placeholder="NIK Pasien (16 digit)" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger w-100 btn-remove-pasien" disabled>
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Anda dapat
                            mendaftarkan lebih dari satu pasien jika diperlukan.</small>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: DOKUMEN & PEJABAT -->
            <div class="col-lg-4">
                <!-- PANEL SUBMIT & TANGGAL -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai Berlaku</label>
                            <input type="date" class="form-control" name="berlaku_mulai" value="<?= date('Y-m-d'); ?>"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Surat Dikeluarkan</label>
                            <input type="date" class="form-control" name="tanggal_surat" value="<?= date('Y-m-d'); ?>"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pejabat Penandatangan</label>
                            <select class="form-select" name="id_pejabat" required>
                                <option value="" disabled selected>-- Pilih Pejabat --</option>
                                <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>">
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?> -
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary w-100 fw-bold py-2 mb-2"
                            style="border-radius: 8px;">
                            <i class="fas fa-save me-1"></i> Simpan Surat
                        </button>
                        <a href="index.php?page=sktm-rawat" class="btn btn-outline-secondary w-100 fw-bold py-2"
                            style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- PANEL UPLOAD FOTO RUMAH -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold text-secondary mb-0"><i
                                class="fas fa-camera me-2 text-danger"></i>Arsip Foto Rumah (Lampiran)</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- 1. Foto Depan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">1. Foto Tampak Depan</label>
                            <input type="file" class="form-control form-control-sm img-input" name="foto_depan"
                                data-target="preview-depan">
                            <div class="mt-2 text-center">
                                <img id="preview-depan" src="assets/img/no-image.png" class="img-thumbnail"
                                    style="max-height: 110px; display: none;">
                            </div>
                        </div>
                        <!-- 2. Foto Ruang Tamu -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">2. Foto Ruang Tamu</label>
                            <input type="file" class="form-control form-control-sm img-input" name="foto_ruang_tamu"
                                data-target="preview-tamu">
                            <div class="mt-2 text-center">
                                <img id="preview-tamu" src="assets/img/no-image.png" class="img-thumbnail"
                                    style="max-height: 110px; display: none;">
                            </div>
                        </div>
                        <!-- 3. Foto Kamar -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">3. Foto Kamar Tidur</label>
                            <input type="file" class="form-control form-control-sm img-input" name="foto_kamar"
                                data-target="preview-kamar">
                            <div class="mt-2 text-center">
                                <img id="preview-kamar" src="assets/img/no-image.png" class="img-thumbnail"
                                    style="max-height: 110px; display: none;">
                            </div>
                        </div>
                        <!-- 4. Foto Dapur -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">4. Foto Dapur</label>
                            <input type="file" class="form-control form-control-sm img-input" name="foto_dapur"
                                data-target="preview-dapur">
                            <div class="mt-2 text-center">
                                <img id="preview-dapur" src="assets/img/no-image.png" class="img-thumbnail"
                                    style="max-height: 110px; display: none;">
                            </div>
                        </div>
                        <!-- 5. Foto Toilet -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">5. Foto Kamar Mandi/Toilet</label>
                            <input type="file" class="form-control form-control-sm img-input" name="foto_toilet"
                                data-target="preview-toilet">
                            <div class="mt-2 text-center">
                                <img id="preview-toilet" src="assets/img/no-image.png" class="img-thumbnail"
                                    style="max-height: 110px; display: none;">
                            </div>
                        </div>
                        <span class="text-muted small d-block" style="font-size: 0.75rem;"><i
                                class="fas fa-info-circle me-1"></i>Format file diperbolehkan: JPG, JPEG, PNG (Maks
                            2MB)</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JAVASCRIPT VALIDASI & DINAMISASI FORM -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const containerPasien = document.getElementById("container-pasien");
    const btnAddPasien = document.getElementById("add-pasien-row");

    // 1. Fungsi Tambah Baris Pasien Baru (One-To-Many)
    btnAddPasien.addEventListener("click", function() {
        const rows = containerPasien.querySelectorAll(".pasien-row");
        const lastRow = rows[rows.length - 1];

        // Buat element baru
        const newRow = document.createElement("div");
        newRow.className = "row g-2 mb-3 pasien-row";
        newRow.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="nama_pasien[]" class="form-control" placeholder="Masukkan Nama Pasien" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="nik_pasien[]" class="form-control" maxlength="16" placeholder="NIK Pasien (16 digit)" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100 btn-remove-pasien">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;

        containerPasien.appendChild(newRow);
        toggleRemoveButtons();
    });

    // 2. Event Listener untuk Hapus Baris Pasien
    containerPasien.addEventListener("click", function(e) {
        if (e.target.closest(".btn-remove-pasien")) {
            const row = e.target.closest(".pasien-row");
            row.remove();
            toggleRemoveButtons();
        }
    });

    // Fungsi Mengaktifkan/Menonaktifkan Tombol Hapus Baris Pasien
    function toggleRemoveButtons() {
        const rows = containerPasien.querySelectorAll(".pasien-row");
        rows.forEach((row, index) => {
            const btnRemove = row.querySelector(".btn-remove-pasien");
            if (rows.length === 1) {
                btnRemove.setAttribute("disabled", "true");
            } else {
                btnRemove.removeAttribute("disabled");
            }
        });
    }

    // 3. Fitur Pratinjau Gambar Langsung (Live Preview Image)
    const imgInputs = document.querySelectorAll(".img-input");
    imgInputs.forEach(input => {
        input.addEventListener("change", function() {
            const targetId = this.getAttribute("data-target");
            const previewImg = document.getElementById(targetId);

            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.setAttribute("src", e.target.result);
                    previewImg.style.display = "block";
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                previewImg.style.display = "none";
            }
        });
    });
});
</script>