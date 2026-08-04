<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
}

// Pastikan ID ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Penduduk tidak ditemukan!'); window.location='index.php?page=penduduk';</script>";
    exit();
}

$id = (int)$_GET['id'];

// Ambil Data Penduduk Berdasarkan ID
$querySelect = "SELECT * FROM tb_penduduk WHERE id = $id";
$resultSelect = mysqli_query($koneksi, $querySelect);

if (mysqli_num_rows($resultSelect) == 0) {
    echo "<script>alert('Data penduduk tidak ditemukan!'); window.location='index.php?page=penduduk';</script>";
    exit();
}

$data = mysqli_fetch_assoc($resultSelect);

// Proses Update Data Penduduk
if (isset($_POST['update'])) {
    $nik              = mysqli_real_escape_string($koneksi, trim($_POST['nik']));
    $no_kk            = mysqli_real_escape_string($koneksi, trim($_POST['no_kk']));
    $nama             = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $jenis_kelamin    = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin']));
    $tempat_tgl_lahir = mysqli_real_escape_string($koneksi, trim($_POST['tempat_tgl_lahir']));
    $umur             = (int)$_POST['umur'];
    $agama            = mysqli_real_escape_string($koneksi, trim($_POST['agama']));
    $pekerjaan        = mysqli_real_escape_string($koneksi, trim($_POST['pekerjaan']));
    $alamat           = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $rt               = mysqli_real_escape_string($koneksi, trim($_POST['rt']));
    $rw               = mysqli_real_escape_string($koneksi, trim($_POST['rw']));

    // Cek jika NIK diganti dan bentrok dengan NIK milik orang lain
    $cekNik = mysqli_query($koneksi, "SELECT id FROM tb_penduduk WHERE nik = '$nik' AND id != $id");
    if (mysqli_num_rows($cekNik) > 0) {
        echo "<script>alert('NIK $nik sudah digunakan oleh penduduk lain!');</script>";
    } else {
        $queryUpdate = "UPDATE tb_penduduk SET 
                        nik = '$nik',
                        no_kk = '$no_kk',
                        nama = '$nama',
                        jenis_kelamin = '$jenis_kelamin',
                        tempat_tgl_lahir = '$tempat_tgl_lahir',
                        umur = '$umur',
                        agama = '$agama',
                        pekerjaan = '$pekerjaan',
                        alamat = '$alamat',
                        rt = '$rt',
                        rw = '$rw'
                        WHERE id = $id";
        
        if (mysqli_query($koneksi, $queryUpdate)) {
            echo "<script>alert('Data penduduk berhasil diperbarui!'); window.location='index.php?page=penduduk';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal mengupdate data: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}
?>

<style>
.form-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 2rem;
}
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Edit Data Penduduk</h2>
            <p class="text-muted mb-0">Ubah formulir di bawah untuk memperbarui data penduduk Desa Berugenjang.</p>
        </div>
        <a href="index.php?page=penduduk" class="btn btn-outline-secondary rounded-3 fw-semibold px-3 py-2 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Form Edit Penduduk -->
    <div class="form-card shadow-sm">
        <form action="" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK (Nomor Induk Kependudukan) <span
                            class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control" maxlength="16"
                        value="<?php echo htmlspecialchars($data['nik']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. KK (Kartu Keluarga)</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16"
                        value="<?php echo htmlspecialchars($data['no_kk']); ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control"
                        value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="Laki-laki"
                            <?php echo ($data['jenis_kelamin'] == 'Laki-laki' || strtoupper(substr($data['jenis_kelamin'], 0, 1)) === 'L') ? 'selected' : ''; ?>>
                            Laki-laki</option>
                        <option value="Perempuan"
                            <?php echo ($data['jenis_kelamin'] == 'Perempuan' || strtoupper(substr($data['jenis_kelamin'], 0, 1)) === 'P') ? 'selected' : ''; ?>>
                            Perempuan</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tempat, Tanggal Lahir</label>
                    <input type="text" name="tempat_tgl_lahir" class="form-control"
                        value="<?php echo htmlspecialchars($data['tempat_tgl_lahir']); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Umur (Tahun)</label>
                    <input type="number" name="umur" class="form-control"
                        value="<?php echo htmlspecialchars($data['umur']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Agama</label>
                    <select name="agama" class="form-select">
                        <?php 
                        $listAgama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'];
                        foreach ($listAgama as $agm) {
                            $selected = (strcasecmp($data['agama'], $agm) == 0) ? 'selected' : '';
                            echo "<option value='$agm' $selected>$agm</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control"
                        value="<?php echo htmlspecialchars($data['pekerjaan']); ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Alamat / Dukuh</label>
                    <input type="text" name="alamat" class="form-control"
                        value="<?php echo htmlspecialchars($data['alamat']); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RT</label>
                    <input type="text" name="rt" class="form-control"
                        value="<?php echo htmlspecialchars($data['rt']); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RW</label>
                    <input type="text" name="rw" class="form-control"
                        value="<?php echo htmlspecialchars($data['rw']); ?>">
                </div>

                <div class="col-12 mt-4 text-end">
                    <a href="index.php?page=penduduk" class="btn btn-light me-2 fw-semibold px-4">Batal</a>
                    <button type="submit" name="update" class="btn btn-warning text-white fw-semibold px-4">
                        <i class="fas fa-save me-1"></i> Perbarui Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>