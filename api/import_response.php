<?php

/**
 * Starts a JSON-only response for the resident-import endpoints.
 *
 * Import files can contain legacy encodings or can trigger PHP warnings.  This
 * keeps those conditions from turning an API response into an empty or mixed
 * response body.
 */
function import_json_begin(): void
{
    header('Content-Type: application/json; charset=utf-8');
    error_reporting(E_ALL);
    ini_set('display_errors', '0');

    ob_start();
    ob_clean();

    $GLOBALS['import_json_response_sent'] = false;

    set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    });

    register_shutdown_function('import_json_handle_shutdown_error');
}

function import_json_encode(array $payload): string
{
    $GLOBALS['import_json_encoding_failed'] = false;

    $options = JSON_UNESCAPED_UNICODE;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $options |= JSON_INVALID_UTF8_SUBSTITUTE;
    }

    $json = json_encode($payload, $options);

    // json_encode() returns false for malformed strings without the substitute
    // flag, excessive nesting, or another encoding issue.  Never echo false:
    // echoing it produces the empty HTTP body that caused the client error.
    if ($json === false) {
        $GLOBALS['import_json_encoding_failed'] = true;
        return '{"ok":false,"error":"Gagal menyiapkan respons impor."}';
    }

    return $json;
}

function import_json_emit(array $payload, int $status): void
{
    $GLOBALS['import_json_response_sent'] = true;

    $json = import_json_encode($payload);
    if (!empty($GLOBALS['import_json_encoding_failed'])) {
        $status = 500;
    }

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    if (!headers_sent()) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
    }

    echo $json;
}

function import_json_response(array $payload, int $status = 200): void
{
    import_json_emit($payload, $status);
    exit;
}

function import_json_handle_shutdown_error(): void
{
    if (!empty($GLOBALS['import_json_response_sent'])) {
        return;
    }

    $error = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];
    if ($error === null || !in_array($error['type'], $fatalTypes, true)) {
        return;
    }

    import_json_emit([
        'ok' => false,
        'error' => 'Impor gagal karena kesalahan server.',
    ], 500);
}
