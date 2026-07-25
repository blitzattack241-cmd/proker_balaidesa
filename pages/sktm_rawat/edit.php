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

// Ambil ID dari URL
$id_sktm = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_sktm <= 0) {
    echo "<script>
            alert('ID Data tidak valid!');
            window.location.href = 'index.php?page=sktm-rawat';
          </script>";
    exit;
}

// Ambil data utama dari tb_sktm_rawat
$query_sktm = mysqli_query($koneksi, "SELECT * FROM tb_sktm_rawat WHERE id_sktm = $id_sktm");
$data = mysqli_fetch_assoc($query_sktm);

if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=sktm-rawat';
          </script>";
    exit;
}

// Ambil data detail pasien dari tb_sktm_rawat_pasien
$query_pasien = mysqli_query($koneksi, "SELECT * FROM tb_sktm_rawat_pasien WHERE id_sktm = $id_sktm");

// Ambil data pejabat penandatangan untuk dropdown
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM tb_pejabat ORDER BY nama_pejabat ASC");

// PROSES UPDATE DATA
if (isset($_POST['update'])) {
    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $nama_pemohon = mysqli_real_escape_string($koneksi, $_POST['nama_pemohon']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $kewarganegaraan = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $alamat_tinggal = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $no_kk = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $no_ktp = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $rumah_sakit_tujuan = mysqli_real_escape_string($koneksi, $_POST['rumah_sakit_tujuan']);
    $berlaku_mulai = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    // Proses Unggah & Update Gambar (5 Kategori Foto)
    $foto_fields = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
    $uploaded_filenames = [];
    $upload_dir = "assets/img/sktm_rawat/";

    // Pastikan folder penyimpanan ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $upload_ok = true;
    foreach ($foto_fields as $field) {
        // Default menggunakan nama file lama yang sudah ada di database
        $uploaded_filenames[$field] = $data[$field];

        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES[$field]['tmp_name'];
            $file_name = $_FILES[$field]['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

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

            // Rename File Unik
            $new_name = $field . "_" . time() . "_" . uniqid() . "." . $file_ext;

            if (move_uploaded_file($file_tmp, $upload_dir . $new_name)) {
                // Hapus file lama fisik dari server jika sebelumnya ada
                if (!empty($data[$field]) && file_exists($upload_dir . $data[$field])) {
                    unlink($upload_dir . $data[$field]);
                }
                $uploaded_filenames[$field] = $new_name;
            } else {
                echo "<script>alert('Gagal mengunggah file baru untuk $field!');</script>";
                $upload_ok = false;
                break;
            }
        }
    }

    if ($upload_ok) {
        // Query update tabel utama
        $update_sktm = "UPDATE tb_sktm_rawat SET 
            nomor_surat        = '$nomor_surat',
            nama_pemohon       = '$nama_pemohon',
            tempat_lahir       = '$tempat_lahir',
            tanggal_lahir      = '$tanggal_lahir',
            jenis_kelamin      = '$jenis_kelamin',
            pekerjaan          = '$pekerjaan',
            agama              = '$agama',
            kewarganegaraan    = '$kewarganegaraan',
            alamat_tinggal     = '$alamat_tinggal',
            no_kk              = '$no_kk',
            no_ktp             = '$no_ktp',
            rumah_sakit_tujuan = '$rumah_sakit_tujuan',
            berlaku_mulai      = '$berlaku_mulai',
            tanggal_surat      = '$tanggal_surat',
            id_pejabat         = '$id_pejabat',
            foto_depan         = '{$uploaded_filenames['foto_depan']}',
            foto_ruang_tamu    = '{$uploaded_filenames['foto_ruang_tamu']}',
            foto_kamar         = '{$uploaded_filenames['foto_kamar']}',
            foto_dapur         = '{$uploaded_filenames['foto_dapur']}',
            foto_toilet        = '{$uploaded_filenames['foto_toilet']}'
            WHERE id_sktm      = $id_sktm";

        if (mysqli_query($koneksi, $update_sktm)) {
            // Sinkronisasi data pasien (Hapus semua data pasien lama lalu tulis ulang yang baru)
            mysqli_query($koneksi, "DELETE FROM tb_sktm_rawat_pasien WHERE id_sktm = $id_sktm");

            if (isset($_POST['nama_pasien']) && is_array($_POST['nama_pasien'])) {
                $nama_pasien_arr = $_POST['nama_pasien'];
                $nik_pasien_arr = $_POST['nik_pasien'];

                for ($i = 0; $i < count($nama_pasien_arr); $i++) {
                    $nama_p = mysqli_real_escape_string($koneksi, $nama_pasien_arr[$i]);
                    $nik_p = mysqli_real_escape_string($koneksi, $nik_pasien_arr[$i]);

                    if (!empty($nama_p) && !empty($nik_p)) {
                        mysqli_query($koneksi, "
                            INSERT INTO tb_sktm_rawat_pasien (id_sktm, nama_pasien, nik_pasien) 
                            VALUES ('$id_sktm', '$nama_p', '$nik_p')
                        ");
                    }
                }
            }

            echo "<script>
                    alert('Data Surat Pembebasan Rawat Berhasil Diperbarui!');
                    window.location.href = 'index.php?page=sktm-rawat';
                  </script>";
        } else {
            echo "<script>alert('Gagal memperbarui data utama: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}
?>

<div class="container-fluid px-4 py-3">
    <!-- Header Alur Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h3 class="fw-bold text-dark mt-2 mb-1">Edit SKTM Pembebasan Rawat</h3>
            <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; font-size: 0.9rem;">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-info text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=sktm-rawat"
                        class="text-info text-decoration-none">Daftar Surat</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                                class="fas fa-user-edit me-2 text-primary"></i>Edit Identitas Pemohon</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Surat</label>
                                <input type="text" class="form-control" name="nomor_surat"
                                    value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap Pemohon</label>
                                <input type="text" class="form-control" name="nama_pemohon"
                                    value="<?= htmlspecialchars($data['nama_pemohon']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir"
                                    value="<?= htmlspecialchars($data['tempat_lahir']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir"
                                    value="<?= $data['tanggal_lahir']; ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="Laki-Laki" <?= ($data['jenis_kelamin'] == 'Laki-Laki') ? 'selected' : ''; ?>>Laki-Laki
                                    </option>
                                    <option value="Perempuan" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pekerjaan</label>
                                <input type="text" class="form-control" name="pekerjaan"
                                    value="<?= htmlspecialchars($data['pekerjaan']); ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Agama</label>
                                <input type="text" class="form-control" name="agama"
                                    value="<?= htmlspecialchars($data['agama']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kewarganegaraan</label>
                                <input type="text" class="form-control" name="kewarganegaraan"
                                    value="<?= htmlspecialchars($data['kewarganegaraan']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">No. Kartu Keluarga (KK)</label>
                                <input type="text" class="form-control" name="no_kk" maxlength="16"
                                    value="<?= htmlspecialchars($data['no_kk']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIK Pemohon (KTP)</label>
                                <input type="text" class="form-control" name="no_ktp" maxlength="16"
                                    value="<?= htmlspecialchars($data['no_ktp']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rumah Sakit/Puskesmas Tujuan</label>
                                <input type="text" class="form-control" name="rumah_sakit_tujuan"
                                    value="<?= htmlspecialchars($data['rumah_sakit_tujuan']); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Lengkap Tinggal</label>
                                <textarea class="form-control" name="alamat_tinggal" rows="2"
                                    required><?= htmlspecialchars($data['alamat_tinggal']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM DINAMIS: DAFTAR PASIEN (SINKRON DATA SEBELUMNYA) -->
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
                            <?php
                            $count_p = 0;
                            if (mysqli_num_rows($query_pasien) > 0) {
                                while ($pasien = mysqli_fetch_assoc($query_pasien)) {
                                    $count_p++;
                                    ?>
                                    <div class="row g-2 mb-3 pasien-row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-muted" style="font-size: 0.85rem;">Nama
                                                Pasien</label>
                                            <input type="text" name="nama_pasien[]" class="form-control"
                                                value="<?= htmlspecialchars($pasien['nama_pasien']); ?>" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold text-muted" style="font-size: 0.85rem;">NIK
                                                Pasien</label>
                                            <input type="text" name="nik_pasien[]" class="form-control" maxlength="16"
                                                value="<?= htmlspecialchars($pasien['nik_pasien']); ?>" required>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger w-100 btn-remove-pasien">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                // Default baris kosong jika tidak sengaja data pasiennya kosong di DB
                                ?>
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
                            <?php } ?>
                        </div>
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Sistem akan
                            menyimpan pembaruan daftar pasien setelah Anda menekan tombol Simpan.</small>
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
                            <input type="date" class="form-control" name="berlaku_mulai"
                                value="<?= $data['berlaku_mulai']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Surat Dikeluarkan</label>
                            <input type="date" class="form-control" name="tanggal_surat"
                                value="<?= $data['tanggal_surat']; ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Pejabat Penandatangan</label>
                            <select class="form-select" name="id_pejabat" required>
                                <option value="" disabled>-- Pilih Pejabat --</option>
                                <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                    <option value="<?= $pejabat['id_pejabat']; ?>"
                                        <?= ($pejabat['id_pejabat'] == $data['id_pejabat']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($pejabat['nama_pejabat']); ?> -
                                        (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary w-100 fw-bold py-2 mb-2"
                            style="border-radius: 8px;">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="index.php?page=sktm-rawat" class="btn btn-outline-secondary w-100 fw-bold py-2"
                            style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- PANEL PREVIEW & UPLOAD FOTO BARU -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold text-secondary mb-0"><i
                                class="fas fa-camera me-2 text-danger"></i>Perbarui Arsip Foto Rumah</h5>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        $labels = [
                            'foto_depan' => '1. Foto Tampak Depan',
                            'foto_ruang_tamu' => '2. Foto Ruang Tamu',
                            'foto_kamar' => '3. Foto Kamar Tidur',
                            'foto_dapur' => '4. Foto Dapur',
                            'foto_toilet' => '5. Foto Kamar Mandi/Toilet'
                        ];

                        foreach ($labels as $field => $label_title):
                            $img_src = !empty($data[$field]) && file_exists("assets/img/sktm_rawat/" . $data[$field])
                                ? "assets/img/sktm_rawat/" . $data[$field]
                                : "assets/img/no-image.png";
                            $display = !empty($data[$field]) ? "block" : "none";
                            ?>
                            <div class="mb-3 border-bottom pb-3">
                                <label class="form-label fw-semibold small"><?= $label_title; ?></label>
                                <input type="file" class="form-control form-control-sm img-input" name="<?= $field; ?>"
                                    data-target="preview-<?= $field; ?>">
                                <div class="mt-2 text-center">
                                    <img id="preview-<?= $field; ?>" src="<?= $img_src; ?>" class="img-thumbnail"
                                        style="max-height: 115px; display: <?= $display; ?>;">
                                    <?php if ($img_src != "assets/img/no-image.png"): ?>
                                        <span class="badge bg-success mt-1 d-inline-block" style="font-size: 0.7rem;"><i
                                                class="fas fa-check-circle me-1"></i>Sudah Terarsip</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <span class="text-muted small d-block" style="font-size: 0.75rem;"><i
                                class="fas fa-info-circle me-1"></i>Format diperbolehkan: JPG, JPEG, PNG (Maks 2MB).
                            Biarkan kosong jika tidak ingin mengubah berkas foto lama.</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JAVASCRIPT VALIDASI & DINAMISASI FORM -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const containerPasien = document.getElementById("container-pasien");
        const btnAddPasien = document.getElementById("add-pasien-row");

        // Jalankan pengecekan status tombol hapus pertama kali dijalankan
        toggleRemoveButtons();

        // 1. Fungsi Tambah Baris Pasien Baru (One-To-Many)
        btnAddPasien.addEventListener("click", function () {
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
        containerPasien.addEventListener("click", function (e) {
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

        // 3. Fitur Pratinjau Gambar Langsung (Live Preview Image saat Upload)
        const imgInputs = document.querySelectorAll(".img-input");
        imgInputs.forEach(input => {
            input.addEventListener("change", function () {
                const targetId = this.getAttribute("data-target");
                const previewImg = document.getElementById(targetId);

                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.setAttribute("src", e.target.result);
                        previewImg.style.display = "block";
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    });
</script>