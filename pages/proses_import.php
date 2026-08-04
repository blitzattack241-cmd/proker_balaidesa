<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['import'])) {
    if (isset($_FILES['file_excel']['tmp_name']) && !empty($_FILES['file_excel']['tmp_name'])) {
        
        $filename = $_FILES['file_excel']['tmp_name'];
        $handle   = fopen($filename, "r");

        if ($handle !== FALSE) {
            $row = 0;
            $inserted = 0;
            $errors = [];

            // Deteksi pemisah (delimiter) otomatis: koma atau titik koma
            $firstLine = fgets($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            rewind($handle); // Kembali ke baris awal file

            while (($data = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                $row++;
                // Lewati baris 1 (Header)
                if ($row == 1) continue;

                // Fungsi pembersih nilai kolom
                $clean = function($val) use ($koneksi) {
                    if (!isset($val)) return '';
                    // Bersihkan dari spasi & format scientific notation angka (e.g. 3.319E+15)
                    $val = trim($val);
                    if (is_numeric($val) && strpos($val, 'E') !== false) {
                        $val = sprintf('%.0f', (float)$val);
                    }
                    return mysqli_real_escape_string($koneksi, $val);
                };

                $alamat           = $clean($data[0] ?? '');
                $rt               = $clean($data[1] ?? '');
                $rw               = $clean($data[2] ?? '');
                $nama             = $clean($data[3] ?? '');
                $no_kk            = $clean($data[4] ?? '');
                $nik              = $clean($data[5] ?? '');
                $jenis_kelamin    = $clean($data[6] ?? '');
                $tempat_tgl_lahir = $clean($data[7] ?? '');
                $umur             = (int)($data[8] ?? 0);
                $agama            = $clean($data[9] ?? '');
                $pekerjaan        = $clean($data[10] ?? '');

                // Pastikan NIK dan Nama tidak kosong
                if (!empty($nik) && !empty($nama)) {
                    $query = "INSERT INTO tb_penduduk 
                        (nik, no_kk, nama, jenis_kelamin, tempat_tgl_lahir, umur, agama, pekerjaan, alamat, rt, rw)
                        VALUES 
                        ('$nik', '$no_kk', '$nama', '$jenis_kelamin', '$tempat_tgl_lahir', '$umur', '$agama', '$pekerjaan', '$alamat', '$rt', '$rw')
                        ON DUPLICATE KEY UPDATE 
                        nama='$nama', no_kk='$no_kk', jenis_kelamin='$jenis_kelamin', tempat_tgl_lahir='$tempat_tgl_lahir', pekerjaan='$pekerjaan', alamat='$alamat', rt='$rt', rw='$rw'";
                    
                    if (mysqli_query($koneksi, $query)) {
                        $inserted++;
                    } else {
                        $errors[] = "Baris $row (NIK: $nik): " . mysqli_error($koneksi);
                    }
                }
            }
            fclose($handle);

            if (!empty($errors)) {
                $msgError = implode("\\n", array_slice($errors, 0, 5));
                echo "<script>alert('Terimpor: $inserted data.\\nAda error:\\n$msgError'); window.location='../index.php?page=penduduk';</script>";
            } else {
                echo "<script>alert('Berhasil mengimpor $inserted data penduduk!'); window.location='../index.php?page=penduduk';</script>";
            }
            exit();
        }
    }
}
?>