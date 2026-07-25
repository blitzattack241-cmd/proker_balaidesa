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
    $url = getBaseUrlSimdes() . '/verifikasi.php'
        . '?jenis=' . urlencode($jenis_surat)
        . '&id=' . (int) $id_surat
        . '&token=' . urlencode($token);

    // Menggunakan layanan QR gratis (tidak butuh instalasi library tambahan).
    // Bila server tidak memiliki akses internet saat mencetak, ganti dengan
    // library lokal seperti endroid/qr-code (sudah tersedia di /vendor).
    $qrImgSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=110x110&data=' . urlencode($url);

    return '<div class="qr-verifikasi" style="margin:6px auto 0 auto;text-align:center;width:75px;">
        <img src="' . htmlspecialchars($qrImgSrc) . '" alt="QR Verifikasi Dokumen"
             style="width:75px;height:75px;display:block;margin:0 auto;">
        <p style="font-size:6.5pt;margin:3px 0 0 0;color:#333;line-height:1.15;">
            Scan untuk verifikasi<br>keabsahan dokumen
        </p>
    </div>';
}
