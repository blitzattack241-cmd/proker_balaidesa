<?php
// Koneksi database (sesuaikan dengan config Anda jika ada file koneksi global)
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// =========================================================================
// LOGIKA GENERATE NOMOR SURAT OTOMATIS
// =========================================================================
$tahun_sekarang = date('Y');

// Query untuk mengambil surat terakhir pada tahun berjalan
// Catatan: Jika primary key Anda id_garapan, pastikan 'ORDER BY id_garapan'
$query_no = "SELECT nomor_surat FROM tb_surat_garapan 
             WHERE nomor_surat LIKE '%/$tahun_sekarang' 
             ORDER BY id_garapan DESC LIMIT 1"; 

$result_no = mysqli_query($koneksi, $query_no);

// Jika query gagal karena kolom primary key bukan 'id_garapan', coba urutkan dari ID bawaan/terakhir
if (!$result_no) {
    $query_no = "SELECT nomor_surat FROM tb_surat_garapan 
                 WHERE nomor_surat LIKE '%/$tahun_sekarang' 
                 LIMIT 1";
    $result_no = mysqli_query($koneksi, $query_no);
}

$nomor_urut_baru = 1; // Default jika belum ada data sama sekali tahun ini

if ($result_no && mysqli_num_rows($result_no) > 0) {
    $row_no = mysqli_fetch_assoc($result_no);
    $nomor_terakhir = $row_no['nomor_surat']; // Contoh: "581/ 005 /31.07.16/2026"
    
    // Pecah string nomor berdasarkan karakter "/"
    $bagian = explode('/', $nomor_terakhir);
    
    // Mengambil bagian tengah (nomor urut) dan dikonversi ke angka
    if (isset($bagian[1])) {
        $angka_saja = (int) preg_replace('/[^0-9]/', '', $bagian[1]);
        if ($angka_saja > 0) {
            $nomor_urut_baru = $angka_saja + 1;
        }
    }
}

// Format nomor urut jadi 3 digit (misal 1 -> 001, 12 -> 012)
$nomor_formatted = sprintf("%03d", $nomor_urut_baru);

// Susun string Nomor Surat Otomatis
$nomor_surat_otomatis = "581/ " . $nomor_formatted . " /31.07.16/" . $tahun_sekarang;


// =========================================================================
// LOGIKA PROSES SIMPAN FORM
// =========================================================================
if (isset($_POST['simpan'])) {
    // Ambil data utama dari form
    $nomor_surat         = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat       = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_penggarap      = mysqli_real_escape_string($koneksi, $_POST['nama_penggarap']);
    $bin_binti_penggarap = mysqli_real_escape_string($koneksi, $_POST['bin_binti_penggarap']);
    $nama_pasangan       = mysqli_real_escape_string($koneksi, $_POST['nama_pasangan']);
    $bin_binti_pasangan  = mysqli_real_escape_string($koneksi, $_POST['bin_binti_pasangan']);
    $tempat_lahir        = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir       = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $agama               = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $pekerjaan           = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat_tinggal      = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $keperluan           = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    
    // 1. Insert ke Tabel Utama
    $sql_utama = "INSERT INTO tb_surat_garapan 
                  (nomor_surat, tanggal_surat, nama_penggarap, bin_binti_penggarap, nama_pasangan, bin_binti_pasangan, tempat_lahir, tanggal_lahir, agama, pekerjaan, alamat_tinggal, keperluan) 
                  VALUES 
                  ('$nomor_surat', '$tanggal_surat', '$nama_penggarap', '$bin_binti_penggarap', '$nama_pasangan', '$bin_binti_pasangan', '$tempat_lahir', '$tanggal_lahir', '$agama', '$pekerjaan', '$alamat_tinggal', '$keperluan')";
    
    if (mysqli_query($koneksi, $sql_utama)) {
        // Ambil ID dari baris utama yang baru saja masuk
        $id_garapan_baru = mysqli_insert_id($koneksi);
        
        // Ambil array baris rincian sawah
        $atas_nama   = $_POST['sawah_atas_nama'];
        $desa        = $_POST['terletak_di_desa'];
        $blok        = $_POST['blok'];
        $persil      = $_POST['persil'];
        $luas        = $_POST['luas_m2'];
        
        // 2. Looping rincian sawah untuk di-insert satu per satu
        $sukses_detail = true;
        for ($i = 0; $i < count($atas_nama); $i++) {
            if (!empty($atas_nama[$i])) {
                $an = mysqli_real_escape_string($koneksi, $atas_nama[$i]);
                $ds = mysqli_real_escape_string($koneksi, $desa[$i]);
                $bk = mysqli_real_escape_string($koneksi, $blok[$i]);
                $pr = mysqli_real_escape_string($koneksi, $persil[$i]);
                $ls = (int)$luas[$i];
                
                $sql_detail = "INSERT INTO tb_surat_garapan_detail 
                               (id_garapan, sawah_atas_nama, terletak_di_desa, blok, persil, luas_m2) 
                               VALUES 
                               ('$id_garapan_baru', '$an', '$ds', '$bk', '$pr', '$ls')";
                if (!mysqli_query($koneksi, $sql_detail)) {
                    $sukses_detail = false;
                }
            }
        }
        
        if ($sukses_detail) {
            echo "<script>
                    alert('Data Surat Garapan Berhasil Disimpan!');
                    window.location.href = 'index.php?page=surat-garapan-sawah';
                  </script>";
        } else {
            echo "<div class='alert alert-warning m-3'>Surat utama tersimpan, namun beberapa rincian sawah gagal dimasukkan.</div>";
        }
    } else {
        echo "<div class='alert alert-danger m-3'>Gagal menyimpan data utama: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<style>
.card-modern {
    border: none !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}
</style>

<div class="container-fluid px-4 py-3">
    <h3 class="mt-4 fw-bold text-dark">Tambah Surat Keterangan Garapan Sawah</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?page=dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?page=surat-garapan" class="text-decoration-none">Surat
                Garapan</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <form action="" method="POST">
        <div class="row">
            <!-- Bagian Kiri: Identitas Penggarap -->
            <div class="col-lg-6">
                <div class="card card-modern mb-4">
                    <div class="card-header bg-primary text-white fw-bold py-3" style="border-radius: 15px 15px 0 0;">
                        <i class="fas fa-user me-2"></i> Identitas Penggarap & Surat
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nomor Surat</label>
                                <!-- Nilai otomatis diisi via PHP pada atribut value -->
                                <input type="text" name="nomor_surat" class="form-control"
                                    value="<?= $nomor_surat_otomatis; ?>" placeholder="581/   /31.07.16/2026" required>
                                <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah
                                    manual)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" class="form-control"
                                    value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label class="form-label">Nama Penggarap</label>
                                <input type="text" name="nama_penggarap" class="form-control" placeholder="Nama Lengkap"
                                    required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Bin / Binti</label>
                                <input type="text" name="bin_binti_penggarap" class="form-control"
                                    placeholder="Ayah Kandung">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label class="form-label">Nama Suami / Istri</label>
                                <input type="text" name="nama_pasangan" class="form-control"
                                    placeholder="Nama Pasangan (Kosongkan jika tidak ada)">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Bin / Binti Pasangan</label>
                                <input type="text" name="bin_binti_pasangan" class="form-control"
                                    placeholder="Ayah Pasangan">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: Kudus"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan: Alamat, Pekerjaan & Keperluan -->
            <div class="col-lg-6">
                <div class="card card-modern mb-4">
                    <div class="card-header bg-secondary text-white fw-bold py-3" style="border-radius: 15px 15px 0 0;">
                        <i class="fas fa-file-alt me-2"></i> Keterangan Tambahan
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Agama</label>
                                <select name="agama" class="form-select" required>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="form-control" placeholder="Contoh: Petani"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tempat Tinggal / Alamat</label>
                            <textarea name="alamat_tinggal" class="form-control" rows="2"
                                placeholder="Desa Berugenjang, RT ... / RW ... Undaan Kudus" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keperluan</label>
                            <textarea name="keperluan" class="form-control" rows="3"
                                placeholder="Guna tambah modal biaya garap pertanian..."
                                required>Mohon Pinjaman di BRI Unit Undaan guna tambah modal biaya garap pertanian</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah: Input Baris Sawah Dinamis -->
        <div class="card card-modern mb-4">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center py-3"
                style="border-radius: 15px 15px 0 0;">
                <span><i class="fas fa-map-marked-alt me-2"></i> Detail Sawah Garapan</span>
                <button type="button" class="btn btn-success btn-sm fw-bold" id="add_row_sawah">
                    <i class="fas fa-plus me-1"></i> Tambah Baris Sawah
                </button>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="table_sawah">
                        <thead class="table-light">
                            <tr class="text-center text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                                <th width="25%">Sawah Atas Nama</th>
                                <th width="20%">Terletak di Desa</th>
                                <th width="20%">Blok</th>
                                <th width="15%">Persil</th>
                                <th width="15%">Luas (M2)</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sawah_body">
                            <!-- Baris pertama (default) -->
                            <tr>
                                <td><input type="text" name="sawah_atas_nama[]" class="form-control"
                                        placeholder="Sawah atas nama siapa?" required></td>
                                <td><input type="text" name="terletak_di_desa[]" value="Berugenjang"
                                        class="form-control" required></td>
                                <td><input type="text" name="blok[]" class="form-control" placeholder="Nama Blok"
                                        required></td>
                                <td><input type="text" name="persil[]" class="form-control" placeholder="No. Persil"
                                        required></td>
                                <td><input type="number" name="luas_m2[]" class="form-control" placeholder="Luas"
                                        required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-row-sawah"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <a href="index.php?page=surat-garapan-sawah" class="btn btn-light border px-4 py-2 me-2">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary px-5 py-2">Simpan Surat</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript Penambah & Penghapus Baris Sawah -->
<script>
document.getElementById('add_row_sawah').addEventListener('click', function() {
    var tbody = document.getElementById('sawah_body');
    var barisBaru = `
            <tr>
                <td><input type="text" name="sawah_atas_nama[]" class="form-control" placeholder="Sawah atas nama siapa?" required></td>
                <td><input type="text" name="terletak_di_desa[]" value="Berugenjang" class="form-control" required></td>
                <td><input type="text" name="blok[]" class="form-control" placeholder="Nama Blok" required></td>
                <td><input type="text" name="persil[]" class="form-control" placeholder="No. Persil" required></td>
                <td><input type="number" name="luas_m2[]" class="form-control" placeholder="Luas" required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row-sawah"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
    tbody.insertAdjacentHTML('beforeend', barisBaru);
});

document.getElementById('sawah_body').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row-sawah')) {
        var totalBaris = document.querySelectorAll('#sawah_body tr').length;
        if (totalBaris > 1) {
            e.target.closest('tr').remove();
        } else {
            alert('Gagal! Minimal harus ada 1 rincian sawah yang dicatat.');
        }
    }
});
</script>