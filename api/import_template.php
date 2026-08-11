<?php
// Set error handler to suppress output
error_reporting(E_ALL);
ini_set('display_errors', '0');
set_error_handler(function() {});

ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
// Clean any output that happened during session start
ob_end_clean();
ob_start();

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/import_helpers.php';

try {
    $dataFile = __DIR__ . '/../data/import_mappings.json';
    if (!is_file($dataFile)) {
        file_put_contents($dataFile, json_encode(['synonyms'=>[], 'templates'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') {
        $json = json_decode(file_get_contents($dataFile), true);
        echo json_encode(['templates' => $json['templates'] ?? []], JSON_UNESCAPED_UNICODE);
    } else if ($method === 'POST') {
        $action = $_POST['action'] ?? '';
        $name = $_POST['name'] ?? '';
        $content = $_POST['content'] ?? '';
        $json = json_decode(file_get_contents($dataFile), true);
        if (!is_array($json)) $json = ['synonyms'=>[], 'templates'=>[]];

        if ($action === 'save' && $name !== '' && $content !== '') {
            $tpl = json_decode($content, true);
            if (!is_array($tpl)) { throw new Exception('Isi template tidak valid'); }
            $json['templates'][$name] = $tpl;
            file_put_contents($dataFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            echo json_encode(['ok'=>true]);
        } else if ($action === 'delete' && $name !== '') {
            if (isset($json['templates'][$name])) { 
                unset($json['templates'][$name]); 
                file_put_contents($dataFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); 
            }
            echo json_encode(['ok'=>true]);
        } else {
            throw new Exception('Aksi tidak valid');
        }
    } else {
        throw new Exception('Metode tidak didukung');
    }

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

ob_end_flush();
exit;

echo json_encode(['error'=>'Metode tidak didukung']);
exit;
