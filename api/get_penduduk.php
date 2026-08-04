<?php
header('Content-Type: application/json');
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

if (!$koneksi) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit();
}

// 1. Jika melakukan pencarian dinamis (autocomplete select2)
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, trim($_GET['search']));
    
    // Cari berdasarkan Nama, NIK, atau No. KK
    $query = "SELECT id, nik, no_kk, nama, jenis_kelamin, tempat_tgl_lahir, pekerjaan, alamat, rt, rw 
              FROM tb_penduduk 
              WHERE nama LIKE '%$search%' OR nik LIKE '%$search%' OR no_kk LIKE '%$search%' 
              LIMIT 15";
    
    $result = mysqli_query($koneksi, $query);
    $data = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'id' => $row['id'],
            'text' => $row['nama'] . " | NIK: " . $row['nik'] . " | KK: " . ($row['no_kk'] ?: '-'),
            'nik' => $row['nik'],
            'no_kk' => $row['no_kk'],
            'nama' => $row['nama'],
            'tempat_tgl_lahir' => $row['tempat_tgl_lahir'],
            'pekerjaan' => $row['pekerjaan'],
            'alamat_lengkap' => $row['alamat'] . " RT " . $row['rt'] . " / RW " . $row['rw']
        ];
    }
    
    echo json_encode(['results' => $data]);
    exit();
}

// 2. Jika mengambil detail 1 penduduk berdasarkan ID yang dipilih
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM tb_penduduk WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $row['alamat_lengkap'] = $row['alamat'] . " RT " . sprintf("%03d", $row['rt']) . " / RW " . sprintf("%03d", $row['rw']);
        echo json_encode(['status' => 'success', 'data' => $row]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}
?>