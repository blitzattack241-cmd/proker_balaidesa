<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Impor Data Penduduk</title>
  <link rel="stylesheet" href="/css/styles.css">
  <link rel="stylesheet" href="/css/responsive.css">
</head>
<body class="bg-light">
  <main class="container py-5" style="max-width: 640px;">
    <section class="bg-white border rounded-4 shadow-sm p-4 p-md-5 text-center">
      <i class="fas fa-file-excel text-success mb-3" style="font-size: 3rem;"></i>
      <h1 class="h3 fw-bold">Impor Data Penduduk</h1>
      <p class="text-muted mb-4">Pilih file dan sistem akan memvalidasi serta mengimpor data secara otomatis.</p>
      <button id="residentImportButton" class="btn btn-success px-4" type="button">
        <i class="fas fa-file-import me-1"></i> Pilih File Excel / CSV
      </button>
      <a href="/index.php?page=penduduk" class="btn btn-light ms-2">Kembali</a>
    </section>
  </main>

  <input id="residentImportFile" type="file" accept=".csv,.xlsx,.xls" class="d-none">
  <div id="residentImportProgress" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center p-3" style="z-index: 1080; background: rgba(15, 23, 42, .55);">
    <div class="bg-white rounded-4 shadow-lg p-4" style="width: min(100%, 440px);" role="status" aria-live="polite">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="spinner-border text-success" aria-hidden="true"></div>
        <div>
          <h2 class="h5 fw-bold mb-1">Memproses impor penduduk</h2>
          <p id="residentImportStatus" class="text-muted small mb-0">Menunggu file dipilih.</p>
        </div>
      </div>
      <div class="progress" style="height: .55rem;"><div id="residentImportProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success w-100"></div></div>
      <div id="residentImportResult" class="small mt-3 d-none"></div>
      <div class="text-end mt-3"><button id="residentImportDone" type="button" class="btn btn-success d-none">Muat Ulang Data</button></div>
    </div>
  </div>

  <script src="/js/resident-import.js"></script>
</body>
</html>
