<?php
/**
 * qr_helper.php
 * Modul QR Verifikasi Tanda Tangan (ACC) untuk SIMDES Balai Desa Berugenjang
 *
 * Cara pakai di halaman cetak.php mana pun (ditempatkan LANGSUNG di kolom
 * tanda tangan pejabat/kepala desa, bukan lewat auto-detect JS lagi supaya
 * pasti muncul walau struktur HTML tiap surat berbeda-beda):
 *
 *   require_once __DIR__ . '/../../includes/qr_helper.php';
 *   $token = dapatkanTokenVerifikasi($koneksi, 'surat_domisili', $id_domisili, $data['nomor_surat'] ?? '');
 *   ...
 *   <?= tampilkanQR('surat_domisili', $id_domisili, $token); ?>   // taruh di dalam kolom ttd pejabat
 */

// Load autoloader Composer (dibutuhkan untuk library QR lokal).
if (!function_exists('muatAutoloadComposerQr')) {
    function muatAutoloadComposerQr()
    {
        static $autoloadStatus = null;
        if ($autoloadStatus !== null) {
            return $autoloadStatus;
        }

        $autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
        if (!is_file($autoloadPath)) {
            $autoloadStatus = false;
            return false;
        }

        try {
            require_once $autoloadPath;
            $autoloadStatus = true;
            return true;
        } catch (Throwable $e) {
            // Hindari hard-fail halaman cetak bila autoload dependency bermasalah.
            if (!headers_sent()) {
                http_response_code(200);
            }
            $autoloadStatus = false;
            return false;
        }
    }
}

/**
 * Membuat source gambar QR berbentuk data URI secara lokal (tanpa API eksternal).
 * Mengutamakan Endroid QR Code, lalu fallback ke Bacon QR Code bila tersedia.
 */
function buatQrDataUriLokal($data, $size = 110)
{
    if (!muatAutoloadComposerQr()) {
        return '';
    }

    // Prioritas 1: Endroid SVG writer (tidak memerlukan extension GD)
    if (class_exists('\\Endroid\\QrCode\\Builder\\Builder') && class_exists('\\Endroid\\QrCode\\Writer\\SvgWriter')) {
        try {
            $builderClass = '\\Endroid\\QrCode\\Builder\\Builder';
            $writerClass = '\\Endroid\\QrCode\\Writer\\SvgWriter';

            $builder = $builderClass::create();
            $builder = $builder->writer(new $writerClass());
            $builder = $builder->data((string) $data);

            if (method_exists($builder, 'size')) {
                $builder = $builder->size((int) $size);
            }
            if (method_exists($builder, 'margin')) {
                $builder = $builder->margin(0);
            }

            $result = $builder->build();

            if (is_object($result) && method_exists($result, 'getString')) {
                return 'data:image/svg+xml;base64,' . base64_encode($result->getString());
            }
        } catch (Throwable $e) {
            // Lanjut ke fallback berikutnya.
        }
    }

    // Prioritas 2: Endroid PNG writer (butuh extension GD)
    if (class_exists('\\Endroid\\QrCode\\Builder\\Builder') && class_exists('\\Endroid\\QrCode\\Writer\\PngWriter')) {
        try {
            $builderClass = '\\Endroid\\QrCode\\Builder\\Builder';
            $writerClass = '\\Endroid\\QrCode\\Writer\\PngWriter';

            $builder = $builderClass::create();
            $builder = $builder->writer(new $writerClass());
            $builder = $builder->data((string) $data);

            if (method_exists($builder, 'size')) {
                $builder = $builder->size((int) $size);
            }
            if (method_exists($builder, 'margin')) {
                $builder = $builder->margin(0);
            }

            $result = $builder->build();

            if (is_object($result) && method_exists($result, 'getDataUri')) {
                return $result->getDataUri();
            }

            if (is_object($result) && method_exists($result, 'getString')) {
                $mime = method_exists($result, 'getMimeType') ? $result->getMimeType() : 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode($result->getString());
            }
        } catch (Throwable $e) {
            // Lanjut ke fallback berikutnya.
        }
    }

    // Prioritas 3: Bacon QR Code (fallback tambahan)
    if (
        class_exists('\\BaconQrCode\\Writer')
        && class_exists('\\BaconQrCode\\Renderer\\ImageRenderer')
        && class_exists('\\BaconQrCode\\Renderer\\Image\\SvgImageBackEnd')
        && class_exists('\\BaconQrCode\\Renderer\\RendererStyle\\RendererStyle')
    ) {
        try {
            $rendererClass = '\\BaconQrCode\\Renderer\\ImageRenderer';
            $backendClass = '\\BaconQrCode\\Renderer\\Image\\SvgImageBackEnd';
            $styleClass = '\\BaconQrCode\\Renderer\\RendererStyle\\RendererStyle';
            $writerClass = '\\BaconQrCode\\Writer';

            $renderer = new $rendererClass(new $styleClass((int) $size), new $backendClass());
            $writer = new $writerClass($renderer);
            $svg = $writer->writeString((string) $data);

            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        } catch (Throwable $e) {
            // Fallback final dipakai di bawah.
        }
    }

    return '';
}

// Mendeteksi otomatis alamat dasar website (bekerja di localhost maupun domain asli)
function getBaseUrlSimdes()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Jika host lokal (localhost / 127.0.0.1), coba fallback ke alamat IP LAN
    if (preg_match('/^(localhost|127\.0\.0\.1)$/', $host)) {
        $lanIp = $_SERVER['SERVER_ADDR'] ?? null;
        if (empty($lanIp) || strpos($lanIp, '127.') === 0) {
            $resolved = gethostbyname(gethostname());
            if ($resolved && strpos($resolved, '127.') !== 0) {
                $lanIp = $resolved;
            }
        }
        if (!empty($lanIp) && strpos($lanIp, '127.') !== 0) {
            $host = $lanIp;
        }
    }

    // Ambil path folder project secara otomatis dari lokasi file ini
    // (2 folder di atas /includes adalah root project, sesuaikan bila struktur folder berbeda)
    $root = str_replace('\\', '/', dirname(dirname(__DIR__)));
    $docRoot = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/'));
    $folderProject = trim(str_replace($docRoot, '', str_replace('\\', '/', dirname(__DIR__))), '/');

    if ($folderProject === '') {
        return $protocol . $host;
    }

    return $protocol . $host . '/' . $folderProject;
}

/**
 * Memastikan tabel yang dibutuhkan modul QR sudah ada di database.
 * Dipanggil otomatis (aman dipanggil berkali-kali / dari file manapun).
 */
function pastikanTabelQr($koneksi)
{
    static $sudahDicek = false;
    if ($sudahDicek) {
        return;
    }

    mysqli_query($koneksi, "
        CREATE TABLE IF NOT EXISTS tb_verifikasi_dokumen (
            id INT AUTO_INCREMENT PRIMARY KEY,
            jenis_surat VARCHAR(60) NOT NULL,
            id_surat INT NOT NULL,
            nomor_surat VARCHAR(150) DEFAULT '',
            token VARCHAR(64) NOT NULL,
            dibuat_pada DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_dokumen (jenis_surat, id_surat),
            KEY idx_token (token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    mysqli_query($koneksi, "
        CREATE TABLE IF NOT EXISTS tb_device_terpercaya (
            id INT AUTO_INCREMENT PRIMARY KEY,
            device_token VARCHAR(64) NOT NULL,
            keterangan VARCHAR(150) DEFAULT '',
            dibuat_pada DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_used DATETIME NULL,
            UNIQUE KEY uniq_device (device_token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $sudahDicek = true;
}

/**
 * Membuat token verifikasi baru untuk sebuah dokumen, atau mengambil
 * token yang sudah ada bila dokumen tersebut sebelumnya sudah pernah dicetak.
 * $jenis_surat adalah slug unik per jenis surat (mis. 'surat_domisili',
 * 'sktm_bumil', 'surat_kelahiran', dst) dan $id_surat adalah ID baris
 * surat tersebut di tabel masing-masing.
 */
function dapatkanTokenVerifikasi($koneksi, $jenis_surat, $id_surat, $nomor_surat = '')
{
    pastikanTabelQr($koneksi);

    $jenis_surat_esc = mysqli_real_escape_string($koneksi, $jenis_surat);
    $id_surat = (int) $id_surat;

    $cek = mysqli_query(
        $koneksi,
        "SELECT token FROM tb_verifikasi_dokumen WHERE jenis_surat = '$jenis_surat_esc' AND id_surat = $id_surat LIMIT 1"
    );

    if ($cek && mysqli_num_rows($cek) > 0) {
        $row = mysqli_fetch_assoc($cek);
        // Perbarui nomor surat bila sudah berubah sejak pertama kali dicetak
        if ($nomor_surat !== '') {
            $nomor_surat_esc = mysqli_real_escape_string($koneksi, (string) $nomor_surat);
            mysqli_query(
                $koneksi,
                "UPDATE tb_verifikasi_dokumen SET nomor_surat = '$nomor_surat_esc'
                 WHERE jenis_surat = '$jenis_surat_esc' AND id_surat = $id_surat"
            );
        }
        return $row['token'];
    }

    $token = bin2hex(random_bytes(16));
    $nomor_surat_esc = mysqli_real_escape_string($koneksi, (string) $nomor_surat);

    mysqli_query(
        $koneksi,
        "INSERT INTO tb_verifikasi_dokumen (jenis_surat, id_surat, nomor_surat, token)
         VALUES ('$jenis_surat_esc', $id_surat, '$nomor_surat_esc', '$token')"
    );

    return $token;
}

/**
 * Mengembalikan potongan HTML berisi gambar QR + keterangan singkat,
 * siap ditempel LANGSUNG di dalam kolom/blok tanda tangan pejabat pada
 * halaman cetak. Didesain kompak (ukuran kecil, tanpa lebar tetap) agar
 * pas ditaruh di layout apa pun: table, flex, ataupun div biasa -
 * sehingga bisa dipakai seragam di semua jenis surat.
 */
function tampilkanQR($jenis_surat, $id_surat, $token)
{
    $baseUrl = rtrim(getBaseUrlSimdes(), '/');
    $url = $baseUrl . '/verifikasi.php'
        . '?jenis=' . urlencode($jenis_surat)
        . '&id=' . (int) $id_surat
        . '&token=' . urlencode($token);

    // QR dibangkitkan lokal (offline-ready) dan ditanam sebagai data URI.
    $qrImgSrc = buatQrDataUriLokal($url, 110);

    // Fallback internal bila library QR belum tersedia.
    if ($qrImgSrc === '') {
        $fallback = htmlspecialchars($url);
        return '<div class="qr-verifikasi" style="margin:6px auto 0 auto;text-align:center;width:75px;">
            <div style="width:75px;height:75px;border:1px solid #999;display:flex;align-items:center;justify-content:center;font-size:6pt;line-height:1.2;color:#555;margin:0 auto;">
                QR lokal<br>tidak tersedia
            </div>
            <p style="font-size:6.2pt;margin:3px 0 0 0;color:#333;line-height:1.15;word-break:break-all;">
                Verifikasi manual:<br>' . $fallback . '
            </p>
        </div>';
    }

    return '<div class="qr-verifikasi" style="margin:6px auto 0 auto;text-align:center;width:75px;">
        <img src="' . htmlspecialchars($qrImgSrc) . '" alt="QR Verifikasi Dokumen"
             style="width:75px;height:75px;display:block;margin:0 auto;">
        <p style="font-size:6.5pt;margin:3px 0 0 0;color:#333;line-height:1.15;">
            Scan untuk verifikasi<br>keabsahan dokumen
        </p>
    </div>';
}
