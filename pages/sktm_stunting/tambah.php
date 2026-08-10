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

if (!isset($koneksi)) {
    $koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
}
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// Ambil data pejabat untuk pilihan penandatangan surat
$pejabat_query = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor
?>

<!-- Library Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<!-- Style Kustom untuk Formulir Modern -->
<style>
.card-form {
    border: none !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
}

.card-form-header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f1f3f5 !important;
    padding: 1.5rem !important;
}

.form-label-custom {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.form-control-custom {
    border-radius: 8px !important;
    padding: 0.6rem 1rem !important;
    border: 1px solid #ced4da !important;
    transition: all 0.2s ease;
}

.form-control-custom:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
}

.section-divider {
    border-top: 2px dashed #e9ecef;
    margin: 2rem 0;
}

.section-title-custom {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0d6efd;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit-custom {
    background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 24px !important;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    transition: all 0.25s ease;
}

.btn-submit-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(13, 110, 253, 0.35);
}

.btn-batal-custom {
    border-radius: 8px !important;
    padding: 10px 24px !important;
    font-weight: 600;
}

/* Container Box Auto-fill Pencarian */
.box-pencarian-container {
    background-color: #f8faff;
    border: 1px dashed #0d6efd;
    border-radius: 10px;
    padding: 15px;
}
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark mt-2 mb-1">Tambah SKTM Stunting</h3>
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item"><a href="index.php?page=sktm-stunting" class="text-decoration-none">Daftar SKTM
                    Stunting</a></li>
            <li class="breadcrumb-item active">Buat Surat Baru</li>
        </ol>
    </div>

    <!-- Card Form -->
    <div class="card card-form mb-5">
        <div class="card-form-header">
            <h5 class="mb-0 fw-bold text-secondary"><i class="fas fa-file-medical me-2 text-primary"></i> Formulir
                Pembuatan Surat</h5>
        </div>
        <div class="card-body p-4">
            <form action="pages/sktm_stunting/proses_tambah.php" method="POST">

                <!-- BOX AUTO-FILL DATA PENDUDUK -->
                <div class="box-pencarian-container mb-4">
                    <label class="form-label form-label-custom text-primary fw-bold mb-2">
                        <i class="fas fa-search me-1"></i> CARI & AUTO-FILL DATA ORANG TUA / WALI (KETIK NO. KK / NIK /
                        NAMA)
                    </label>
                    <select id="cari_penduduk" class="form-select" style="width: 100%;">
                        <option value=""></option>
                    </select>
                    <small class="text-muted mt-2 d-block" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i> Pilih nama orang tua/wali yang muncul untuk mengisi
                        otomatis formulir di bawah ini.
                    </small>
                </div>

                <!-- BAGIAN 1: INFORMASI SURAT -->
                <div class="section-title-custom">
                    <i class="fas fa-info-circle"></i> Detail Administrasi Surat
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control form-control-custom"
                            placeholder="Contoh: 474 / 001 / 31.07.16 / 2026" required
                            value="<?= htmlspecialchars($nomor_otomatis); ?>">
                        <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah
                            manual)</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Tanggal Surat dibuat</label>
                        <input type="date" name="tanggal_surat" class="form-control form-control-custom" required
                            value="<?= date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- BAGIAN 2: DATA ORANG TUA / WALI -->
                <div class="section-title-custom">
                    <i class="fas fa-user-friends"></i> Identitas Orang Tua / Wali
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nama Lengkap Orang Tua / Wali</label>
                        <input type="text" name="nama_warga" id="input_nama"
                            class="form-control form-control-custom text-uppercase"
                            placeholder="Masukkan nama lengkap orang tua" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nomor Induk Kependudukan (No. KTP)</label>
                        <input type="text" name="no_ktp" id="input_nik" class="form-control form-control-custom"
                            placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nomor Kartu Keluarga (No. KK)</label>
                        <input type="text" name="no_kk" id="input_no_kk" maxlength="16"
                            class="form-control form-control-custom" value="331904" placeholder="Contoh: 33190..."
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-custom">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="input_tempat_lahir"
                            class="form-control form-control-custom" placeholder="Contoh: Kudus" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-custom">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="input_tanggal_lahir"
                            class="form-control form-control-custom" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="input_jenis_kelamin" class="form-select form-control-custom"
                            required>
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">Agama</label>
                        <select name="agama" id="input_agama" class="form-select form-control-custom" required>
                            <option value="Islam" selected>Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Khonghucu">Khonghucu</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="input_pekerjaan"
                            class="form-control form-control-custom" placeholder="Contoh: Buruh Harian Lepas" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label form-label-custom">Alamat Tinggal Domisili</label>
                        <textarea name="alamat_tinggal" id="input_alamat" class="form-control form-control-custom"
                            rows="2" placeholder="Masukkan alamat lengkap rumah orang tua saat ini..."
                            required></textarea>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- BAGIAN 3: DATA ANAK & TUJUAN -->
                <div class="section-title-custom">
                    <i class="fas fa-baby"></i> Identitas Anak Terindikasi Stunting & Keperluan
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nama Anak</label>
                        <input type="text" name="nama_anak" class="form-control form-control-custom"
                            placeholder="Masukkan nama anak balita" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Kewarganegaraan Orang Tua/Anak</label>
                        <input type="text" name="kewarganegaraan" class="form-control form-control-custom" value="WNI"
                            required>
                    </div>
                    <div class="col-12">
                        <label class="form-label form-label-custom">Keperluan Surat</label>
                        <textarea name="keperluan" class="form-control form-control-custom" rows="3"
                            placeholder="Contoh: Mendapatkan bantuan pemenuhan gizi / PMT (Pemberian Makanan Tambahan) dari Puskesmas atau Dinas Kesehatan"
                            required>Syarat pengajuan bantuan penanganan balita stunting / PMT.</textarea>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- BAGIAN 4: PENANDATANGAN -->
                <div class="section-title-custom">
                    <i class="fas fa-pen-nib"></i> Validasi Otoritas Perangkat Desa
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Pejabat Penandatangan</label>
                        <select name="id_pejabat" class="form-select form-control-custom" required>
                            <option value="" disabled selected>-- Pilih Pejabat yang Mengesahkan --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($pejabat_query)): ?>
                            <option value="<?= $pejabat['id_pejabat']; ?>">
                                <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="mt-5 d-flex justify-content-end gap-3">
                    <a href="index.php?page=sktm-stunting" class="btn btn-outline-secondary btn-batal-custom">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" name="simpan" class="btn btn-primary btn-submit-custom">
                        <i class="fas fa-save me-1"></i> Simpan & Buat Surat
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Library jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2 pencarian penduduk via AJAX
    $('#cari_penduduk').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Ketik No. KK, NIK, atau Nama Orang Tua / Wali... --',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: 'api/get_penduduk.php',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });

    // Auto-fill form saat data orang tua dipilih
    $('#cari_penduduk').on('select2:select', function(e) {
        var data = e.params.data;

        $('#input_nama').val(data.nama || '');
        $('#input_nik').val(data.nik || '');
        $('#input_no_kk').val(data.no_kk || data.kk || '331904');

        // Pengisian Tempat & Tanggal Lahir
        if (data.tgl_lahir) {
            $('#input_tanggal_lahir').val(data.tgl_lahir);
        } else if (data.tanggal_lahir) {
            $('#input_tanggal_lahir').val(data.tanggal_lahir);
        }

        if (data.tempat_lahir) {
            $('#input_tempat_lahir').val(data.tempat_lahir);
        } else if (data.tempat_tgl_lahir) {
            var ttl = data.tempat_tgl_lahir.split(',');
            $('#input_tempat_lahir').val(ttl[0].trim());

            if (ttl.length > 1 && !$('#input_tanggal_lahir').val()) {
                var rawDate = ttl[1].trim();
                if (rawDate.includes('-') || rawDate.includes('/')) {
                    var delimiter = rawDate.includes('-') ? '-' : '/';
                    var parts = rawDate.split(delimiter);
                    if (parts[0].length === 2 && parts[2].length === 4) {
                        rawDate = parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(
                            2, '0');
                    }
                }
                $('#input_tanggal_lahir').val(rawDate);
            }
        }

        // Auto select Jenis Kelamin
        if (data.jenis_kelamin) {
            var jk = data.jenis_kelamin.toString().toLowerCase();
            if (jk.includes('l')) {
                $('#input_jenis_kelamin').val('Laki-laki');
            } else if (jk.includes('p')) {
                $('#input_jenis_kelamin').val('Perempuan');
            }
        }

        // Auto select Agama
        if (data.agama) {
            $('#input_agama').val(data.agama);
        }

        // Pekerjaan & Alamat
        if (data.pekerjaan) {
            $('#input_pekerjaan').val(data.pekerjaan);
        }

        if (data.alamat_lengkap) {
            $('#input_alamat').val(data.alamat_lengkap);
        } else if (data.alamat) {
            $('#input_alamat').val(data.alamat);
        }
    });

    // Reset isi form saat pilihan dibersihkan
    $('#cari_penduduk').on('select2:clear', function(e) {
        $('#input_nama').val('');
        $('#input_nik').val('');
        $('#input_no_kk').val('331904');
        $('#input_tempat_lahir').val('');
        $('#input_tanggal_lahir').val('');
        $('#input_jenis_kelamin').val('');
        $('#input_agama').val('Islam');
        $('#input_pekerjaan').val('');
        $('#input_alamat').val('');
    });
});
</script>