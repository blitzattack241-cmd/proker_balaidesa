<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
ob_clean();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/import_helpers.php';

$dataFile = __DIR__ . '/../data/import_mappings.json';
if (!is_file($dataFile)) {
    file_put_contents($dataFile, json_encode(['synonyms'=>[], 'templates'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $json = json_decode(file_get_contents($dataFile), true);
    echo json_encode(['templates' => $json['templates'] ?? []], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    $name = $_POST['name'] ?? '';
    $content = $_POST['content'] ?? '';
    $json = json_decode(file_get_contents($dataFile), true);
    if (!is_array($json)) $json = ['synonyms'=>[], 'templates'=>[]];

    if ($action === 'save' && $name !== '' && $content !== '') {
        $tpl = json_decode($content, true);
        if (!is_array($tpl)) { echo json_encode(['error'=>'Isi template tidak valid']); exit; }
        $json['templates'][$name] = $tpl;
        file_put_contents($dataFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        echo json_encode(['ok'=>true]); exit;
    }

    if ($action === 'delete' && $name !== '') {
        if (isset($json['templates'][$name])) { unset($json['templates'][$name]); file_put_contents($dataFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); }
        echo json_encode(['ok'=>true]); exit;
    }

    echo json_encode(['error' => 'Aksi tidak valid']);
    exit;
}

echo json_encode(['error'=>'Metode tidak didukung']);
exit;
