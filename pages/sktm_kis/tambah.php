<?php
require_once __DIR__ . '/../../koneksi.php';

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

<!-- CDN CSS Select2 & Select2 Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

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

    /* Style tambahan untuk Box Auto-fill Pencarian */
    .box-pencarian-container {
        background-color: #f8faff;
        border: 1px dashed #0d6efd;
        border-radius: 10px;
        padding: 15px;
    }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Buat Surat Keterangan Tidak Mampu (KIS)</h3>
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

                <!-- BOX AUTO-FILL DATA PENDUDUK -->
                <div class="box-pencarian-container mb-4">
                    <label class="form-label text-primary fw-bold mb-2">
                        <i class="fas fa-search me-1"></i> CARI & AUTO-FILL DATA PEMOHON (KETIK NO. KK / NIK / NAMA)
                    </label>
                    <select id="cari_penduduk" class="form-select" style="width: 100%;">
                        <option value=""></option>
                    </select>
                    <small class="text-muted mt-2 d-block" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i> Pilih nama pemohon yang muncul untuk mengisikan otomatis
                        data ke formulir di bawah ini.
                    </small>
                </div>

                <!-- BAGIAN 1: DATA UTAMA SURAT -->
                <div class="form-section-title">
                    <i class="fas fa-envelope-open-text me-2 text-primary"></i>Informasi Pokok Surat
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control bg-light"
                            value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                        <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah
                            manual)</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Lengkap Pemohon</label>
                        <input type="text" id="nama_warga" name="nama_warga" class="form-control text-uppercase"
                            placeholder="Masukkan nama pemohon" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                            placeholder="Tempat lahir" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Agama</label>
                        <select id="agama" name="agama" class="form-select" required>
                            <option value="Islam" selected>Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">No. KTP (NIK)</label>
                        <input type="text" id="no_ktp" name="no_ktp" class="form-control" minlength="16" maxlength="16"
                            placeholder="16 Digit NIK" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="no_kk" class="form-label fw-bold">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" id="no_kk" name="no_kk" maxlength="16" class="form-control" value="331904"
                            placeholder="Contoh: 33190..." required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" class="form-control"
                            placeholder="Pekerjaan saat ini" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Kewarganegaraan</label>
                        <input type="text" id="kewarganegaraan" name="kewarganegaraan" class="form-control" value="WNI"
                            required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea id="alamat_tinggal" name="alamat_tinggal" class="form-control" rows="3"
                            placeholder="Alamat tinggal pemohon..." required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Keperluan</label>
                        <textarea id="keperluan" name="keperluan" class="form-control" rows="3"
                            placeholder="Contoh: Permohonan Pengajuan Peralihan BPJS Mandiri kelas 3 ke KIS/JKN APBD..."
                            required>Permohonan Pengajuan Peralihan BPJS Mandiri kelas 3 ke KIS/JKN APBD</textarea>
                    </div>
                </div>

                <!-- BAGIAN 2: DETAIL ANGGOTA KELUARGA -->
                <div class="form-section-title mt-4">
                    <i class="fas fa-users me-2 text-primary"></i>Anggota Keluarga yang Didaftarkan
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Daftar Nama & NIK Anggota Keluarga</label>
                    <textarea id="anggota_keluarga" name="anggota_keluarga" class="form-control" rows="4"
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
                        <input type="date" id="berlaku_mulai" name="berlaku_mulai" class="form-control"
                            value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tanggal Surat Dibuat</label>
                        <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control"
                            value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Pejabat Penandatangan</label>
                        <select id="id_pejabat" name="id_pejabat" class="form-select" required>
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

<!-- CDN Library jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Inisialisasi Select2 pencarian pemohon via AJAX
        $('#cari_penduduk').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Ketik No. KK, NIK, atau Nama Pemohon... --',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: 'api/get_penduduk.php',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        // Auto-fill form saat data pemohon dipilih
        $('#cari_penduduk').on('select2:select', function (e) {
            var data = e.params.data;

            $('#nama_warga').val(data.nama || '');
            $('#no_ktp').val(data.nik || '');
            $('#no_kk').val(data.no_kk || data.kk || '331904');

            // Handling Tempat & Tanggal Lahir
            if (data.tgl_lahir) {
                $('#tanggal_lahir').val(data.tgl_lahir);
            } else if (data.tanggal_lahir) {
                $('#tanggal_lahir').val(data.tanggal_lahir);
            }

            if (data.tempat_lahir) {
                $('#tempat_lahir').val(data.tempat_lahir);
            } else if (data.tempat_tgl_lahir) {
                var ttl = data.tempat_tgl_lahir.split(',');
                $('#tempat_lahir').val(ttl[0].trim());

                if (ttl.length > 1 && !$('#tanggal_lahir').val()) {
                    var rawDate = ttl[1].trim();
                    if (rawDate.includes('-') || rawDate.includes('/')) {
                        var delimiter = rawDate.includes('-') ? '-' : '/';
                        var parts = rawDate.split(delimiter);
                        if (parts[0].length === 2 && parts[2].length === 4) {
                            rawDate = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(
                                2, '0');
                        }
                    }
                    $('#tanggal_lahir').val(rawDate);
                }
            }

            // Auto Select Jenis Kelamin
            if (data.jenis_kelamin) {
                var jk = data.jenis_kelamin.toString().toLowerCase();
                if (jk.includes('l')) {
                    $('#jenis_kelamin').val('Laki-Laki');
                } else if (jk.includes('p')) {
                    $('#jenis_kelamin').val('Perempuan');
                }
            }

            // Auto Select Agama
            if (data.agama) {
                $('#agama').val(data.agama);
            }

            // Pekerjaan & Alamat
            if (data.pekerjaan) {
                $('#pekerjaan').val(data.pekerjaan);
            }

            if (data.alamat_lengkap) {
                $('#alamat_tinggal').val(data.alamat_lengkap);
            } else if (data.alamat) {
                $('#alamat_tinggal').val(data.alamat);
            }
        });

        // Reset isi form saat pilihan dihapus
        $('#cari_penduduk').on('select2:clear', function (e) {
            $('#nama_warga').val('');
            $('#no_ktp').val('');
            $('#no_kk').val('331904');
            $('#tempat_lahir').val('');
            $('#tanggal_lahir').val('');
            $('#jenis_kelamin').val('');
            $('#agama').val('Islam');
            $('#pekerjaan').val('');
            $('#alamat_tinggal').val('');
        });
    });
</script>