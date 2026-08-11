<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
require_once __DIR__ . '/../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
}

// Pastikan ID ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Penduduk tidak ditemukan!'); window.location='index.php?page=penduduk';</script>";
    exit();
}

$id = (int) $_GET['id'];

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
    $nik = mysqli_real_escape_string($koneksi, trim($_POST['nik']));
    $no_kk = mysqli_real_escape_string($koneksi, trim($_POST['no_kk']));
    $kepala_kk = mysqli_real_escape_string($koneksi, trim($_POST['kepala_kk']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $jenis_kelamin = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin']));
    $status_keluarga = mysqli_real_escape_string($koneksi, trim($_POST['status_keluarga']));
    $tempat_lahir = mysqli_real_escape_string($koneksi, trim($_POST['tempat_lahir']));
    $tgl_lahir = mysqli_real_escape_string($koneksi, trim($_POST['tgl_lahir']));
    $status_pernikahan = mysqli_real_escape_string($koneksi, trim($_POST['status_pernikahan']));
    $agama = mysqli_real_escape_string($koneksi, trim($_POST['agama']));
    $kewarganegaraan = mysqli_real_escape_string($koneksi, trim($_POST['kewarganegaraan']));
    $suku = mysqli_real_escape_string($koneksi, trim($_POST['suku']));
    $pendidikan = mysqli_real_escape_string($koneksi, trim($_POST['pendidikan']));
    $pekerjaan = mysqli_real_escape_string($koneksi, trim($_POST['pekerjaan']));
    $rt = mysqli_real_escape_string($koneksi, trim($_POST['rt']));
    $rw = mysqli_real_escape_string($koneksi, trim($_POST['rw']));

    // Format nilai tanggal untuk Query
    $tgl_lahir_val = !empty($tgl_lahir) ? "'$tgl_lahir'" : "NULL";

    // Cek jika NIK diganti dan bentrok dengan NIK milik orang lain
    $cekNik = mysqli_query($koneksi, "SELECT id FROM tb_penduduk WHERE nik = '$nik' AND id != $id");
    if (mysqli_num_rows($cekNik) > 0) {
        echo "<script>alert('NIK $nik sudah digunakan oleh penduduk lain!');</script>";
    } else {
        $queryUpdate = "UPDATE tb_penduduk SET 
                        rt = '$rt',
                        rw = '$rw',
                        no_kk = '$no_kk',
                        kepala_kk = '$kepala_kk',
                        nik = '$nik',
                        nama = '$nama',
                        jenis_kelamin = '$jenis_kelamin',
                        status_keluarga = '$status_keluarga',
                        tempat_lahir = '$tempat_lahir',
                        tgl_lahir = $tgl_lahir_val,
                        status_pernikahan = '$status_pernikahan',
                        agama = '$agama',
                        kewarganegaraan = '$kewarganegaraan',
                        suku = '$suku',
                        pendidikan = '$pendidikan',
                        pekerjaan = '$pekerjaan'
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

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #0f172a;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 0.5rem;
    margin-bottom: 1.25rem;
}
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Edit Data Penduduk</h2>
            <p class="text-muted mb-0">Perbarui formulir di bawah untuk memperbarui data penduduk Desa Berugenjang.</p>
        </div>
        <a href="index.php?page=penduduk" class="btn btn-outline-secondary rounded-3 fw-semibold px-3 py-2 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Form Edit Penduduk -->
    <div class="form-card shadow-sm">
        <form action="" method="POST">

            <!-- SEKSI 1: KELUARGA & WILAYAH -->
            <div class="section-title">
                <i class="fas fa-home me-2 text-primary"></i>Data Keluarga & Wilayah
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. KK (Kartu Keluarga)</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16"
                        value="<?php echo htmlspecialchars($data['no_kk'] ?? ''); ?>" placeholder="16 digit No. KK">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Kepala Keluarga</label>
                    <input type="text" name="kepala_kk" class="form-control"
                        value="<?php echo htmlspecialchars($data['kepala_kk'] ?? ''); ?>" placeholder="Nama Kepala KK">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RT</label>
                    <input type="text" name="rt" class="form-control"
                        value="<?php echo htmlspecialchars($data['rt'] ?? ''); ?>" placeholder="Contoh: 1">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RW</label>
                    <input type="text" name="rw" class="form-control"
                        value="<?php echo htmlspecialchars($data['rw'] ?? ''); ?>" placeholder="Contoh: 1">
                </div>
            </div>

            <!-- SEKSI 2: IDENTITAS DIRI -->
            <div class="section-title">
                <i class="fas fa-user me-2 text-primary"></i>Identitas Diri
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK (Nomor Induk Kependudukan) <span
                            class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control" maxlength="16"
                        value="<?php echo htmlspecialchars($data['nik'] ?? ''); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control"
                        value="<?php echo htmlspecialchars($data['nama'] ?? ''); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="Laki-laki"
                            <?php echo (strcasecmp($data['jenis_kelamin'] ?? '', 'Laki-laki') == 0 || strtoupper(substr($data['jenis_kelamin'] ?? '', 0, 1)) === 'L') ? 'selected' : ''; ?>>
                            Laki-laki</option>
                        <option value="Perempuan"
                            <?php echo (strcasecmp($data['jenis_kelamin'] ?? '', 'Perempuan') == 0 || strtoupper(substr($data['jenis_kelamin'] ?? '', 0, 1)) === 'P') ? 'selected' : ''; ?>>
                            Perempuan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Hubungan Keluarga</label>
                    <select name="status_keluarga" class="form-select">
                        <?php
                        $listStatusKeluarga = ['KEPALA KELUARGA', 'SUAMI', 'ISTRI', 'ANAK', 'CUCU', 'ORANG TUA', 'MERTUA', 'FAMILI LAIN'];
                        $valSK = strtoupper(trim($data['status_keluarga'] ?? 'ANAK'));
                        foreach ($listStatusKeluarga as $sk) {
                            $selected = ($valSK === $sk) ? 'selected' : '';
                            echo "<option value='$sk' $selected>$sk</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Pernikahan</label>
                    <select name="status_pernikahan" class="form-select">
                        <?php
                        $listStatusNikah = ['BELUM KAWIN', 'KAWIN', 'CERAI HIDUP', 'CERAI MATI'];
                        $valSN = strtoupper(trim($data['status_pernikahan'] ?? 'BELUM KAWIN'));
                        foreach ($listStatusNikah as $sn) {
                            $selected = ($valSN === $sn) ? 'selected' : '';
                            echo "<option value='$sn' $selected>$sn</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control"
                        value="<?php echo htmlspecialchars($data['tempat_lahir'] ?? ''); ?>"
                        placeholder="Contoh: Kudus">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control"
                        value="<?php echo htmlspecialchars($data['tgl_lahir'] ?? ''); ?>">
                </div>
            </div>

            <!-- SEKSI 3: INFORMASI TAMBAHAN -->
            <div class="section-title">
                <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Tambahan
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Agama</label>
                    <select name="agama" class="form-select">
                        <?php
                        $listAgama = ['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KHONGHUCU'];
                        $valAgama = strtoupper(trim($data['agama'] ?? 'ISLAM'));
                        foreach ($listAgama as $agm) {
                            $selected = ($valAgama === $agm) ? 'selected' : '';
                            echo "<option value='$agm' $selected>$agm</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" class="form-control"
                        value="<?php echo htmlspecialchars($data['kewarganegaraan'] ?? 'WNI'); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Suku</label>
                    <input type="text" name="suku" class="form-control"
                        value="<?php echo htmlspecialchars($data['suku'] ?? 'JAWA'); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" class="form-control"
                        value="<?php echo htmlspecialchars($data['pendidikan'] ?? ''); ?>"
                        placeholder="Contoh: SLTA/SEDERAJAT">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control"
                        value="<?php echo htmlspecialchars($data['pekerjaan'] ?? ''); ?>"
                        placeholder="Contoh: WIRASWASTA">
                </div>
            </div>

            <!-- BUTTON ACTION -->
            <div class="col-12 mt-4 text-end border-top pt-3">
                <a href="index.php?page=penduduk" class="btn btn-light me-2 fw-semibold px-4">Batal</a>
                <button type="submit" name="update" class="btn btn-warning text-white fw-semibold px-4">
                    <i class="fas fa-save me-1"></i> Perbarui Data
                </button>
            </div>

        </form>
    </div>
</div>