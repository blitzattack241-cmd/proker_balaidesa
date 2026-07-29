<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman Access Control
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>alert('Akses ditolak!'); window.location.href = 'index.php?page=dashboard';</script>";
    exit;
}

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// Target nama tabel master
$tableTarget = 'tb_surat_kelahiran';

// =========================================================================
// LOGIKA GENERATE NOMOR SURAT KELAHIRAN OTOMATIS
// =========================================================================
$tahun_sekarang = date('Y');

// Mengurutkan berdasarkan primary key 'id_surat' secara DESCENDING
$query_no = "SELECT nomor_surat FROM `$tableTarget` 
             WHERE nomor_surat LIKE '%/$tahun_sekarang' 
             ORDER BY id_surat DESC LIMIT 1"; 

$result_no = mysqli_query($koneksi, $query_no);

$nomor_urut_baru = 1; // Default urutan pertama jika belum ada data tahun ini

if ($result_no && mysqli_num_rows($result_no) > 0) {
    $row_no = mysqli_fetch_assoc($result_no);
    $nomor_terakhir = $row_no['nomor_surat']; // Contoh di DB: "474.1/05/2026"
    
    // Pecah string nomor berdasarkan slash (/)
    $bagian = explode('/', $nomor_terakhir);
    
    if (isset($bagian[0])) {
        $angka_saja = (int) preg_replace('/[^0-9]/', '', $bagian[0]);
        
        // Jika bagian pertama adalah kode klasifikasi (474.1 atau 474), ambil angka di bagian ke-2
        if (($angka_saja == 4741 || $angka_saja == 474) && isset($bagian[1])) {
            $angka_saja = (int) preg_replace('/[^0-9]/', '', $bagian[1]);
        }
        
        if ($angka_saja > 0) {
            $nomor_urut_baru = $angka_saja + 1;
        }
    } 
}

// Format nomor urut jadi 2 digit / 3 digit (misal 5 + 1 -> 06)
$nomor_formatted = sprintf("%02d", $nomor_urut_baru);

// Format baku nomor surat kelahiran desa
$nomor_surat_otomatis = "474.3/" . $nomor_formatted . "/31.07.16/   /VII/" . $tahun_sekarang;


// PROSES SIMPAN DATA FORM
if (isset($_POST['simpan'])) {
    // 1. Data Umum & KK
    $nomor_surat          = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat        = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_kepala_keluarga = mysqli_real_escape_string($koneksi, $_POST['nama_kepala_keluarga']);
    $nomor_kk             = mysqli_real_escape_string($koneksi, $_POST['nomor_kk']);

    // 2. Data Bayi
    $nama_bayi            = mysqli_real_escape_string($koneksi, $_POST['nama_bayi']);
    $jenis_kelamin_bayi   = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin_bayi']);
    $tempat_dilahirkan    = mysqli_real_escape_string($koneksi, $_POST['tempat_dilahirkan']);
    $tempat_kelahiran_kab = mysqli_real_escape_string($koneksi, $_POST['tempat_kelahiran_kab']);
    $hari_lahir_bayi      = mysqli_real_escape_string($koneksi, $_POST['hari_lahir_bayi']);
    $tanggal_lahir_bayi   = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_bayi']);
    $pukul_lahir_bayi     = mysqli_real_escape_string($koneksi, $_POST['pukul_lahir_bayi']);
    $jenis_kelahiran      = mysqli_real_escape_string($koneksi, $_POST['jenis_kelahiran']);
    $kelahiran_ke         = mysqli_real_escape_string($koneksi, $_POST['kelahiran_ke']);
    $penolong_kelahiran   = mysqli_real_escape_string($koneksi, $_POST['penolong_kelahiran']);
    $berat_bayi_gram      = mysqli_real_escape_string($koneksi, $_POST['berat_bayi_gram']);
    $panjang_bayi_cm      = mysqli_real_escape_string($koneksi, $_POST['panjang_bayi_cm']);

    // 3. Data Ibu
    $nik_ibu              = mysqli_real_escape_string($koneksi, $_POST['nik_ibu']);
    $nama_ibu             = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $tanggal_lahir_ibu    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ibu']);
    $umur_ibu             = mysqli_real_escape_string($koneksi, $_POST['umur_ibu']);
    $pekerjaan_ibu        = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ibu']);
    $alamat_ibu           = mysqli_real_escape_string($koneksi, $_POST['alamat_ibu']);
    $desa_ibu             = mysqli_real_escape_string($koneksi, $_POST['desa_ibu']);
    $kecamatan_ibu        = mysqli_real_escape_string($koneksi, $_POST['kecamatan_ibu']);
    $kabupaten_ibu        = mysqli_real_escape_string($koneksi, $_POST['kabupaten_ibu']);

    // 4. Data Ayah
    $nik_ayah             = mysqli_real_escape_string($koneksi, $_POST['nik_ayah']);
    $nama_ayah            = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $tanggal_lahir_ayah   = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ayah']);
    $umur_ayah            = mysqli_real_escape_string($koneksi, $_POST['umur_ayah']);
    $pekerjaan_ayah       = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ayah']);
    $alamat_ayah          = mysqli_real_escape_string($koneksi, $_POST['alamat_ayah']);

    // 5. Data Pelapor & Saksi
    $nik_pelapor          = mysqli_real_escape_string($koneksi, $_POST['nik_pelapor']);
    $nama_pelapor         = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']);
    $nik_saksi1           = mysqli_real_escape_string($koneksi, $_POST['nik_saksi1']);
    $nama_saksi1          = mysqli_real_escape_string($koneksi, $_POST['nama_saksi1']);
    $nik_saksi2           = mysqli_real_escape_string($koneksi, $_POST['nik_saksi2']);
    $nama_saksi2          = mysqli_real_escape_string($koneksi, $_POST['nama_saksi2']);

    // Query INSERT masal ke kolom master
    $sqlInsert = "INSERT INTO `$tableTarget` (
        nomor_surat, tanggal_surat, nama_kepala_keluarga, nomor_kk,
        nama_bayi, jenis_kelamin_bayi, tempat_dilahirkan, tempat_kelahiran_kab, hari_lahir_bayi, tanggal_lahir_bayi, pukul_lahir_bayi, jenis_kelahiran, kelahiran_ke, penolong_kelahiran, berat_bayi_gram, panjang_bayi_cm,
        nik_ibu, nama_ibu, tanggal_lahir_ibu, umur_ibu, pekerjaan_ibu, alamat_ibu, desa_ibu, kecamatan_ibu, kabupaten_ibu,
        nik_ayah, nama_ayah, tanggal_lahir_ayah, umur_ayah, pekerjaan_ayah, alamat_ayah,
        nik_pelapor, nama_pelapor, nik_saksi1, nama_saksi1, nik_saksi2, nama_saksi2
    ) VALUES (
        '$nomor_surat', '$tanggal_surat', '$nama_kepala_keluarga', '$nomor_kk',
        '$nama_bayi', '$jenis_kelamin_bayi', '$tempat_dilahirkan', '$tempat_kelahiran_kab', '$hari_lahir_bayi', '$tanggal_lahir_bayi', '$pukul_lahir_bayi', '$jenis_kelahiran', '$kelahiran_ke', '$penolong_kelahiran', '$berat_bayi_gram', '$panjang_bayi_cm',
        '$nik_ibu', '$nama_ibu', '$tanggal_lahir_ibu', '$umur_ibu', '$pekerjaan_ibu', '$alamat_ibu', '$desa_ibu', '$kecamatan_ibu', '$kabupaten_ibu',
        '$nik_ayah', '$nama_ayah', '$tanggal_lahir_ayah', '$umur_ayah', '$pekerjaan_ayah', '$alamat_ayah',
        '$nik_pelapor', '$nama_pelapor', '$nik_saksi1', '$nama_saksi1', '$nik_saksi2', '$nama_saksi2'
    )";
    
    if (mysqli_query($koneksi, $sqlInsert)) {
        echo "<script>alert('Data Formulir F-2.01 Berhasil Disimpan!'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
    } else {
        echo "<div class='alert alert-danger m-4'>Error: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<style>
.page-title-modern {
    font-weight: 700;
    color: #2c3e50;
}

.card-modern {
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.section-form-title {
    font-size: 1.05rem;
    font-weight: 600;
    color: #0d6efd;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 8px;
    margin-top: 15px;
    margin-bottom: 15px;
}

.form-label-modern {
    font-weight: 600;
    color: #495057;
    font-size: 0.85rem;
}

.form-control-modern {
    border-radius: 8px !important;
    padding: 8px 12px !important;
    font-size: 0.9rem;
}
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title-modern m-0">Tambah Formulir Kelahiran (F-2.01)</h3>
            <p class="text-muted small m-0">Input data registrasi kelahiran penduduk desa secara lengkap sesuai standar
                Capil</p>
        </div>
    </div>

    <form action="" method="POST" class="needs-validation" novalidate>
        <div class="card card-modern p-4 mb-4">

            <!-- SECTION 1: DATA ATAS / KELUARGA -->
            <div class="section-form-title"><i class="fas fa-folder me-2"></i>1. REGISTRASI SURAT & KELUARGA</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nomor Surat</label>
                    <input type="text" class="form-control form-control-modern" name="nomor_surat"
                        value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis dari urutan data
                        terakhir.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control form-control-modern" name="nama_kepala_keluarga"
                        placeholder="Nama Kepala Keluarga" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nomor KK</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nomor_kk"
                        maxlength="16" value="331904" placeholder="16 Digit No. KK" required>
                </div>
            </div>

            <!-- SECTION 2: DATA ANAK / BAYI -->
            <div class="section-form-title text-success" style="border-color: #e8f5e9;"><i
                    class="fas fa-baby me-2"></i>2. DATA BAYI / ANAK</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Lengkap Bayi</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_bayi"
                        placeholder="NAMA LENGKAP BAYI" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Jenis Kelamin</label>
                    <select class="form-select form-control-modern" name="jenis_kelamin_bayi" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Dilahirkan</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_dilahirkan"
                        placeholder="RS / Puskesmas / Rumah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Kelahiran (Kab/Kota)</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_kelahiran_kab"
                        value="Kudus" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hari Lahir</label>
                    <input type="text" class="form-control form-control-modern" name="hari_lahir_bayi"
                        placeholder="Senin/Selasa..." required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_bayi" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pukul / Jam Lahir</label>
                    <input type="time" class="form-control form-control-modern" name="pukul_lahir_bayi" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Jenis Kelahiran</label>
                    <input type="text" class="form-control form-control-modern" name="jenis_kelahiran" value="Tunggal"
                        placeholder="Tunggal/Kembar" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Anak Ke-</label>
                    <input type="number" class="form-control form-control-modern" name="kelahiran_ke" min="1"
                        placeholder="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Penolong Kelahiran</label>
                    <input type="text" class="form-control form-control-modern" name="penolong_kelahiran"
                        placeholder="Dokter / Bidan / Dukun" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Berat Bayi (Gram)</label>
                    <input type="number" class="form-control form-control-modern" name="berat_bayi_gram"
                        placeholder="e.g. 3000" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Panjang Bayi (Cm)</label>
                    <input type="number" class="form-control form-control-modern" name="panjang_bayi_cm"
                        placeholder="e.g. 50" required>
                </div>
            </div>

            <!-- SECTION 3: DATA IBU -->
            <div class="section-form-title text-danger" style="border-color: #ffebee;"><i
                    class="fas fa-female me-2"></i>3. DATA IBU</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="nik_ibu" maxlength="16"
                        placeholder="16 Digit NIK Ibu" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Lengkap Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="nama_ibu"
                        placeholder="Nama Lengkap Ibu" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tgl Lahir Ibu</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ibu" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur Ibu</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ibu" placeholder="Thn"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Pekerjaan Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ibu"
                        placeholder="IRT / Swasta" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat Jalan/Dukuh</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ibu"
                        placeholder="Rt 01 Rw 02" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ibu" value="Berugenjang"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ibu" value="Undaan"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ibu" value="Kudus"
                        required>
                </div>
            </div>

            <!-- SECTION 4: DATA AYAH -->
            <div class="section-form-title text-info" style="border-color: #e0f7fa;"><i class="fas fa-male me-2"></i>4.
                DATA AYAH</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="nik_ayah" maxlength="16"
                        placeholder="16 Digit NIK Ayah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Lengkap Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="nama_ayah"
                        placeholder="Nama Lengkap Ayah" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tgl Lahir Ayah</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ayah" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur Ayah</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ayah" placeholder="Thn"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Pekerjaan Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ayah"
                        placeholder="Pekerjaan Ayah" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label form-label-modern">Alamat Lengkap Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ayah"
                        placeholder="Isi alamat jika berbeda dengan Ibu, jika sama tulis 'Sama dengan Ibu'" required>
                </div>
            </div>

            <!-- SECTION 5: PELAPOR & SAKSI -->
            <div class="section-form-title text-warning" style="border-color: #fffde7;"><i
                    class="fas fa-users me-2"></i>5. PELAPOR & SAKSI PENCATATAN</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nik_pelapor" maxlength="16"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nama_pelapor" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi1" maxlength="16"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nama_saksi1" required>
                </div>
                <div class="col-md-3 offset-md-6">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi2" maxlength="16"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nama_saksi2" required>
                </div>
            </div>

            <!-- FOOTER ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                <a href="index.php?page=surat-kelahiran" class="btn btn-secondary px-4 py-2"
                    style="border-radius: 8px;"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 py-2"
                    style="border-radius: 8px; background: linear-gradient(135deg, #0d6efd, #0056b3);"><i
                        class="fas fa-save me-2"></i>Simpan Formulir</button>
            </div>

        </div>
    </form>
</div>

<script>
// Validasi Interaktif Bootstrap Browser
(function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>