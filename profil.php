<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/koneksi.php';
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

$query_user = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($query_user);

if (!$user) {
    echo "<div class='alert alert-warning m-4'>Data profil tidak ditemukan.</div>";
    exit;
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : $user['role'];
$role_label = ucfirst($role);

$stored_profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : '';
$profile_picture = 'uplouds/logo.png';

if (!empty($stored_profile_picture)) {
    if (file_exists($stored_profile_picture)) {
        $profile_picture = $stored_profile_picture;
    } elseif (file_exists('uploads/profil/' . $stored_profile_picture)) {
        $profile_picture = 'uploads/profil/' . $stored_profile_picture;
    }
}

$extra_field_name = '';
$extra_field_label = '';
$extra_field_value = '';

if ($role === 'dosen') {
    $extra_field_name = 'nip';
    $extra_field_label = 'NIP';
    $extra_field_value = isset($user['nip']) ? $user['nip'] : '';
} elseif ($role === 'mahasiswa') {
    $extra_field_name = 'nim';
    $extra_field_label = 'NIM';
    $extra_field_value = isset($user['nim']) ? $user['nim'] : '';
}

$message = '';
$message_type = '';

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'updated') {
        $message = 'Profil berhasil diperbarui.';
        $message_type = 'success';
    } elseif ($_GET['status'] === 'password-updated') {
        $message = 'Password berhasil diperbarui.';
        $message_type = 'success';
    } elseif ($_GET['status'] === 'error') {
        $message = 'Terjadi kesalahan saat menyimpan perubahan.';
        $message_type = 'danger';
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Profil</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Profil</li>
    </ol>

    <?php if ($message): ?>
        <div class="alert alert-<?= htmlspecialchars($message_type) ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm border-0 text-center py-4 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="position-relative mb-3">
                        <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture"
                            class="rounded-circle img-thumbnail shadow-sm"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <h4 class="font-weight-bold mb-1 text-dark"><?= htmlspecialchars($user['nama']) ?></h4>
                    <p class="text-primary font-weight-600 mb-0"><?= htmlspecialchars($role_label) ?></p>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3">
                    <ul class="nav nav-tabs border-bottom-0" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 font-weight-bold" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#overview" type="button" role="tab" aria-controls="overview"
                                aria-selected="true" style="color: #007f3e;">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 font-weight-bold" id="edit-tab" data-bs-toggle="tab"
                                data-bs-target="#edit" type="button" role="tab" aria-controls="edit"
                                aria-selected="false" style="color: #007f3e;">Edit Profile</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 font-weight-bold" id="password-tab" data-bs-toggle="tab"
                                data-bs-target="#password" type="button" role="tab" aria-controls="password"
                                aria-selected="false" style="color: #007f3e;">Change Password</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content pt-2" id="profileTabContent">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel"
                            aria-labelledby="overview-tab">
                            <h5 class="card-title font-weight-bold mb-4 text-dark">Profile Details</h5>

                            <div class="row mb-3">
                                <div class="col-md-4 text-muted">Nama Lengkap</div>
                                <div class="col-md-8 text-dark font-weight-bold"><?= htmlspecialchars($user['nama']) ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-muted">Email</div>
                                <div class="col-md-8 text-dark"><?= htmlspecialchars($user['email']) ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-muted">Role</div>
                                <div class="col-md-8 text-dark"><?= htmlspecialchars($role_label) ?></div>
                            </div>
                            <?php if ($extra_field_name): ?>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted"><?= htmlspecialchars($extra_field_label) ?></div>
                                    <div class="col-md-8 text-dark"><?= htmlspecialchars($extra_field_value) ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="row mb-3">
                                <div class="col-md-4 text-muted">Alamat</div>
                                <div class="col-md-8 text-dark"><?= htmlspecialchars($user['alamat'] ?? '') ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-muted">Telepon</div>
                                <div class="col-md-8 text-dark"><?= htmlspecialchars($user['telepon'] ?? '') ?></div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                            <h5 class="card-title font-weight-bold mb-4 text-dark">Edit Profile</h5>
                            <form action="proses_profil.php?action=update_info" method="POST"
                                enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Foto Profil</label>
                                    <div class="col-md-8">
                                        <input type="file" name="profile_picture" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Nama Lengkap</label>
                                    <div class="col-md-8">
                                        <input type="text" name="nama" class="form-control"
                                            value="<?= htmlspecialchars($user['nama']) ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Email</label>
                                    <div class="col-md-8">
                                        <input type="email" name="email" class="form-control"
                                            value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                </div>
                                <?php if ($extra_field_name): ?>
                                    <div class="row mb-3">
                                        <label
                                            class="col-md-4 col-form-label text-muted"><?= htmlspecialchars($extra_field_label) ?></label>
                                        <div class="col-md-8">
                                            <input type="text" name="<?= htmlspecialchars($extra_field_name) ?>"
                                                class="form-control" value="<?= htmlspecialchars($extra_field_value) ?>">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Alamat</label>
                                    <div class="col-md-8">
                                        <textarea name="alamat" class="form-control"
                                            rows="3"><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Telepon</label>
                                    <div class="col-md-8">
                                        <input type="text" name="telepon" class="form-control"
                                            value="<?= htmlspecialchars($user['telepon'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn text-white px-4"
                                            style="background-color: #007f3e;">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                            <h5 class="card-title font-weight-bold mb-4 text-dark">Change Password</h5>
                            <form action="proses_profil.php?action=update_password" method="POST">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Password Saat Ini</label>
                                    <div class="col-md-8">
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Password Baru</label>
                                    <div class="col-md-8">
                                        <input type="password" name="new_password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-muted">Konfirmasi Password Baru</label>
                                    <div class="col-md-8">
                                        <input type="password" name="confirm_password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn text-white px-4"
                                            style="background-color: #007f3e;">Ubah Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        border: none;
        background: none;
        font-size: 15px;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link.active {
        border-bottom: 2px solid #007f3e !important;
        background: none !important;
        font-weight: 700;
    }

    .font-weight-bold {
        font-weight: 600 !important;
    }

    .font-weight-600 {
        font-weight: 600;
    }

    .text-dark {
        color: #2b303a !important;
    }
</style>