<?php
$mysqli = new mysqli('localhost', 'root', '', 'db_balaidesa');
if ($mysqli->connect_errno) {
    echo 'connect-failed:' . $mysqli->connect_error;
    exit(1);
}
$result = $mysqli->query("SHOW COLUMNS FROM tb_undangan_tujuan LIKE 'nama_jabatan_tujuan'");
if ($result && $result->num_rows === 0) {
    $mysqli->query("ALTER TABLE tb_undangan_tujuan ADD COLUMN nama_jabatan_tujuan VARCHAR(100) DEFAULT NULL");
    echo 'column-added';
} else {
    echo 'column-exists';
}
$mysqli->close();
