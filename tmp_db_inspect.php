<?php
$mysqli = new mysqli('localhost', 'root', '', 'db_balaidesa');
if ($mysqli->connect_errno) {
    echo "CONNECT_ERROR: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}
$res = $mysqli->query('SHOW TABLES');
while ($row = $res->fetch_row()) {
    echo $row[0] . PHP_EOL;
}
$mysqli->close();
