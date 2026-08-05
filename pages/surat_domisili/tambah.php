<?php
include 'koneksi.php';

// Ambil data pejabat untuk dropdown
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM tb_pejabat");

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor

// PROSES SIMPAN DATA FORM
if (isset($_POST['simpan'])) {
    // Reservasi nomor surat definitif saat disimpan
    $nomor_surat = mysqli_real_escape_string($koneksi, generateNomorSuratGlobal($koneksi, true));
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);
    $nama_warga = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $alamat_jalan = mysqli_real_escape_string($koneksi, $_POST['alamat_jalan']);
    $rt = mysqli_real_escape_string($koneksi, $_POST['rt']);
    $rw = mysqli_real_escape_string($koneksi, $_POST['rw']);
    $desa = mysqli_real_escape_string($koneksi, $_POST['desa']);
    $kecamatan = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $kabupaten = mysqli_real_escape_string($koneksi, $_POST['kabupaten']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $berlaku_mulai = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $keterangan_lain = mysqli_real_escape_string($koneksi, $_POST['keterangan_lain']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);

    $insert = mysqli_query($koneksi, "INSERT INTO tb_surat_domisili 
        (nomor_surat, id_pejabat, nama_warga, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, nik, alamat_jalan, rt, rw, desa, kecamatan, kabupaten, keperluan, berlaku_mulai, keterangan_lain, tanggal_surat) 
        VALUES 
        ('$nomor_surat', '$id_pejabat', '$nama_warga', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama', '$nik', '$alamat_jalan', '$rt', '$rw', '$desa', '$kecamatan', '$kabupaten', '$keperluan', '$berlaku_mulai', '$keterangan_lain', '$tanggal_surat')");

    if ($insert) {
        echo "<script>alert('Surat berhasil dibuat!'); window.location='index.php?page=surat-domisili';</script>";
    } else {
        echo "<script>alert('Gagal membuat surat: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!-- Library Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<style>
.page-title-modern {
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: -0.5px;
}

.breadcrumb-modern {
    background: transparent;
    padding: 0;
    font-size: 0.9rem;
}

.breadcrumb-modern a {
    color: #0d6efd;
    font-weight: 500;
}

.card-modern {
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    box-shadow: none !important;
}

.card-header-modern {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6 !important;
    padding: 0.75rem 1.25rem !important;
}

.card-header-title {
    font-weight: 500;
    color: #333333;
    font-size: 0.95rem;
}

.form-label {
    font-weight: 500;
    color: #333333;
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
}

.form-control,
.form-select {
    border: 1px solid #cccccc;
    border-radius: 4px;
    padding: 0.45rem 0.75rem;
    font-size: 0.9rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.section-divider {
    border-top: 1px dashed #dee2e6;
    margin: 1.5rem 0;
}

.btn-action {
    padding: 0.4rem 1.2rem;
    font-size: 0.9rem;
    border-radius: 4px;
    font-weight: 500;
}

/* Styling tambahan agar tombol hapus (clear) presisi */
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
    cursor: pointer;
    margin-right: 10px;
}
</style>

<div class="container-fluid px-3 py-3">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title-modern mb-1">Tambah Surat Keterangan Domisili</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=surat-domisili" class="text-decoration-none">Surat
                        Domisili</a></li>
                <li class="breadcrumb-item active">Tambah Surat</li>
            </ol>
        </div>
    </div>

    <!-- Card Form -->
    <div class="card card-modern mb-4">
        <div class="card-header-modern">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-envelope me-2 text-secondary"></i> Form Surat Keterangan Domisili
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <div class="row g-3">

                    <!-- Fitur Pencarian Penduduk -->
                    <div class="box-pencarian mb-4">
                        <label class="form-label text-primary fw-bold mb-2">
                            <i class="fas fa-search me-1"></i> Cari & Auto-fill Data Penduduk (Ketik No. KK / NIK /
                            Nama)
                        </label>
                        <select id="cari_penduduk" class="form-select" style="width: 100%;">
                            <option value=""></option>
                        </select>
                        <small class="text-muted mt-1 d-block">
                            <i class="fas fa-info-circle me-1"></i> Pilih nama warga yang muncul untuk mengisi otomatis
                            seluruh formulir identitas di bawah ini.
                        </small>
                    </div>

                    <!-- Bagian Dokumen -->
                    <div class="col-md-6">
                        <label class="form-label">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control"
                            value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                        <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah
                            manual)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Penandatangan (Pejabat)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat --</option>
                            <?php while ($p = mysqli_fetch_assoc($query_pejabat)) { ?>
                            <option value="<?= $p['id_pejabat']; ?>"><?= $p['nama_pejabat']; ?> (<?= $p['jabatan']; ?>)
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>
                    <h6 class="text-primary fw-bold mb-1">Identitas Pemohon</h6>

                    <!-- Bagian Identitas -->
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_warga" id="input_nama" class="form-control"
                            placeholder="Contoh: MUHAMMAD TRIYANTO" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIK (16 Digit)</label>
                        <input type="text" name="nik" id="input_nik" maxlength="16" class="form-control"
                            placeholder="Contoh: 33190..." required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="input_tempat_lahir" class="form-control"
                            placeholder="Contoh: Kudus" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="input_tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="input_jenis_kelamin" class="form-select" required>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Agama</label>
                        <input type="text" name="agama" id="input_agama" class="form-control" value="Islam" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Alamat Tinggal (Nama Jalan/Dukuh)</label>
                        <input type="text" name="alamat_jalan" id="input_alamat" class="form-control"
                            placeholder="Contoh: Berugenjang" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">RT</label>
                        <input type="text" name="rt" id="input_rt" class="form-control" placeholder="Contoh: 002"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">RW</label>
                        <input type="text" name="rw" id="input_rw" class="form-control" placeholder="Contoh: 001"
                            required>
                    </div>

                    <!-- Hidden Fields Desa Berugenjang -->
                    <input type="hidden" name="desa" value="Berugenjang">
                    <input type="hidden" name="kecamatan" value="Undaan">
                    <input type="hidden" name="kabupaten" value="Kudus">

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>
                    <h6 class="text-primary fw-bold mb-1">Keperluan & Validitas</h6>

                    <!-- Bagian Keperluan -->
                    <div class="col-12">
                        <label class="form-label">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3"
                            placeholder="Tulis tujuan surat digunakan..." required></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Berlaku Mulai</label>
                        <input type="date" name="berlaku_mulai" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Cetak Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Keterangan Lain-Lain (Opsional)</label>
                        <textarea name="keterangan_lain" class="form-control" rows="2"
                            placeholder="Nama tersebut selama ini menjadi berkelakuan baik..."></textarea>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-4 d-flex justify-content-start gap-2">
                    <button type="submit" name="simpan" class="btn btn-primary btn-action">Simpan & Cetak</button>
                    <a href="index.php?page=surat-domisili" class="btn btn-secondary btn-action">Kembali</a>
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
    // Inisialisasi Select2 AJAX
    $('#cari_penduduk').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Ketik No. KK, NIK, atau Nama Penduduk... --',
        allowClear: true, // Mengaktifkan tombol X (Remove item)
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

    // Auto-fill saat item dipilih
    $('#cari_penduduk').on('select2:select', function(e) {
        var data = e.params.data;

        $('#input_nama').val(data.nama);
        $('#input_nik').val(data.nik);

        // Pembedaan data tempat & tanggal lahir
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

            // Fallback parsing tanggal jika format string gabungan
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

        // Auto select jenis kelamin
        if (data.jenis_kelamin) {
            var jk = data.jenis_kelamin.toLowerCase();
            if (jk.includes('l')) {
                $('#input_jenis_kelamin').val('Laki-Laki');
            } else if (jk.includes('p')) {
                $('#input_jenis_kelamin').val('Perempuan');
            }
        }

        if (data.agama) {
            $('#input_agama').val(data.agama);
        }

        if (data.alamat_jalan || data.alamat_lengkap) {
            $('#input_alamat').val(data.alamat_jalan || data.alamat_lengkap);
        }

        if (data.rt) {
            $('#input_rt').val(data.rt);
        }
        if (data.rw) {
            $('#input_rw').val(data.rw);
        }
    });

    // Reset/Clear semua input formulir ketika tombol "X" (Remove item) diklik
    $('#cari_penduduk').on('select2:clear', function(e) {
        $('#input_nama').val('');
        $('#input_nik').val('');
        $('#input_tempat_lahir').val('');
        $('#input_tanggal_lahir').val('');
        $('#input_jenis_kelamin').val('Laki-Laki');
        $('#input_agama').val('Islam');
        $('#input_alamat').val('');
        $('#input_rt').val('');
        $('#input_rw').val('');
    });
});
</script>