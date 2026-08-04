<?php
$mysqli = new mysqli('localhost', 'root', '', 'db_balaidesa');
if ($mysqli->connect_errno) {
    echo "CONNECT_ERROR: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$tables = [
    'tb_surat_garapan','surat_garapan',
    'tb_surat_waris','surat_waris',
    'tb_surat_undangan','surat_undangan',
    'tb_surat_kelahiran','surat_kelahiran',
    'tb_surat_kematian','surat_kematian',
    'tb_surat_pengantar','surat_pengantar',
    'tb_surat_domisili','surat_domisili',
    'tb_surat_pengantar_dukcapil','tb_surat_dukcapil','surat_pengantar_dukcapil','surat_dukcapil',
    'tb_sktm_bumil','sktm_bumil','tb_sktm_rawat','sktm_rawat','tb_sktm_pasien','sktm_pasien',
    'tb_sktm_kis','sktm_kis','tb_sktm_kip','sktm_kip','tb_sktm_stunting','sktm_stunting'
];

foreach ($tables as $table) {
    $check = $mysqli->query("SHOW TABLES LIKE '$table'");
    if (!$check || $check->num_rows === 0) {
        continue;
    }

    echo "TABLE $table\n";
    $cols = $mysqli->query("SHOW COLUMNS FROM `$table`");
    while ($row = $cols->fetch_assoc()) {
        echo " - {$row['Field']} ({$row['Type']})\n";
    }

    $candidateCols = ['nomor_surat','no_surat','nomor','surat_nomor','no_surat_surat'];
    $found = [];
    foreach ($candidateCols as $col) { 
        $res = $mysqli->query("SHOW COLUMNS FROM `$table` LIKE '$col'");
        if ($res && $res->num_rows > 0) { $found[] = $col; }
    }
    if ($found) {
        echo " candidate number cols: " . implode(', ', $found) . "\n";
        foreach ($found as $col) {
            $res = $mysqli->query("SELECT id, `$col` AS nomor_value FROM `$table` ORDER BY id DESC LIMIT 5");
            if ($res) {
                echo "  sample values for $col:\n";
                while ($row = $res->fetch_assoc()) {
                    echo "   - id={$row['id']} value=" . var_export($row['nomor_value'], true) . "\n";
                }
            }
        }
    }
    echo "---\n";
}
$mysqli->close();
