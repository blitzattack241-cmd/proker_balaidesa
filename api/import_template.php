<?php
require_once __DIR__ . '/import_response.php';

import_json_begin();

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/import_helpers.php';

    $dataFile = __DIR__ . '/../data/import_mappings.json';
    if (!is_file($dataFile)) {
        file_put_contents($dataFile, json_encode(['synonyms'=>[], 'templates'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') {
        $json = json_decode(file_get_contents($dataFile), true);
        import_json_response(['ok' => true, 'templates' => $json['templates'] ?? []]);
    } else if ($method === 'POST') {
        $action = $_POST['action'] ?? '';
        $name = $_POST['name'] ?? '';
        $content = $_POST['content'] ?? '';
        $json = json_decode(file_get_contents($dataFile), true);
        if (!is_array($json)) $json = ['synonyms'=>[], 'templates'=>[]];

        if ($action === 'save' && $name !== '' && $content !== '') {
            $tpl = json_decode($content, true);
            if (!is_array($tpl)) { throw new InvalidArgumentException('Isi template tidak valid'); }
            $json['templates'][$name] = $tpl;
            file_put_contents($dataFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            import_json_response(['ok' => true]);
        } else if ($action === 'delete' && $name !== '') {
            if (isset($json['templates'][$name])) { 
                unset($json['templates'][$name]); 
                file_put_contents($dataFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); 
            }
            import_json_response(['ok' => true]);
        } else {
            throw new InvalidArgumentException('Aksi tidak valid');
        }
    } else {
        throw new InvalidArgumentException('Metode tidak didukung');
    }

} catch (Throwable $e) {
    $status = $e instanceof InvalidArgumentException ? 400 : 500;
    import_json_response(['ok' => false, 'error' => $e->getMessage()], $status);
}
