<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}

$koneksi = mysqli_connect('localhost', 'root', '', 'db_balaidesa');
if (mysqli_connect_errno()) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

$user_id = (int) $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

if ($action === 'update_info') {
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama'] ?? ''));
    $email = mysqli_real_escape_string($koneksi, trim($_POST['email'] ?? ''));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat'] ?? ''));
    $telepon = mysqli_real_escape_string($koneksi, trim($_POST['telepon'] ?? ''));
    $role = $_SESSION['role'] ?? '';

    $extra_fields = [];
    if ($role === 'dosen') {
        $extra_fields['nip'] = mysqli_real_escape_string($koneksi, trim($_POST['nip'] ?? ''));
    } elseif ($role === 'mahasiswa') {
        $extra_fields['nim'] = mysqli_real_escape_string($koneksi, trim($_POST['nim'] ?? ''));
    }

    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $folder = 'uploads/profil/';
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $filename = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $folder . $filename)) {
                $profile_picture = $filename;
            }
        }
    }

    $query = "UPDATE tb_user SET nama = '$nama', email = '$email', alamat = '$alamat', telepon = '$telepon'";
    if ($profile_picture !== '') {
        $query .= ", profile_picture = '$profile_picture'";
    }
    foreach ($extra_fields as $field => $value) {
        $query .= ", $field = '$value'";
    }
    $query .= " WHERE user_id = $user_id";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['alamat'] = $alamat;
        $_SESSION['telepon'] = $telepon;
        if ($profile_picture !== '') {
            $_SESSION['profile_picture'] = 'uploads/profil/' . $profile_picture;
        }
        header('Location: index.php?page=profil&status=updated');
        exit;
    }

    header('Location: index.php?page=profil&status=error');
    exit;
}

if ($action === 'update_password') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $query_user = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE user_id = $user_id");
    $user = mysqli_fetch_assoc($query_user);

    if (!$user || $current_password !== $user['password']) {
        header('Location: index.php?page=profil&status=error');
        exit;
    }

    if ($new_password !== $confirm_password) {
        header('Location: index.php?page=profil&status=error');
        exit;
    }

    if (mysqli_query($koneksi, "UPDATE tb_user SET password = '$new_password' WHERE user_id = $user_id")) {
        header('Location: index.php?page=profil&status=password-updated');
        exit;
    }

    header('Location: index.php?page=profil&status=error');
    exit;
}

header('Location: index.php?page=profil');
exit;
