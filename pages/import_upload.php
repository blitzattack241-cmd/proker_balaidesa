<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../koneksi.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Impor Data Penduduk</title>
  <link rel="stylesheet" href="/css/styles.css">
  <style>
    body {
      background: #f8fafc;
    }

    .import-shell {
      max-width: 1320px;
      margin: 0 auto;
    }

    .page-hero {
      background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
      color: #fff;
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.12);
    }

    .page-hero .badge {
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .main-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    .subtle-note {
      color: #64748b;
      font-size: 0.93rem;
    }

    .info-list {
      list-style: none;
      padding-left: 0;
      margin-bottom: 0;
    }

    .info-list li {
      display: flex;
      gap: 0.75rem;
      align-items: flex-start;
      padding: 0.55rem 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .info-list li:last-child {
      border-bottom: 0;
      padding-bottom: 0;
    }

    .info-bullet {
      width: 0.65rem;
      height: 0.65rem;
      border-radius: 999px;
      background: #2563eb;
      margin-top: 0.4rem;
      flex: 0 0 auto;
    }

    .table-wrap {
      overflow: auto;
      border: 1px solid #e2e8f0;
      border-radius: 0.85rem;
    }

    .mapping-table {
      margin-bottom: 0;
      white-space: nowrap;
    }

    .mapping-table thead th {
      background: #f8fafc;
      color: #475569;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 1px solid #e2e8f0;
    }

    .mapping-table td,
    .mapping-table th {
      border-color: #e2e8f0 !important;
      vertical-align: middle;
    }

    .sample-card {
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 0.85rem;
      padding: 1rem;
    }

    .sample-title {
      font-size: 0.9rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 0.75rem;
    }
  </style>
</head>
<body>
  <div class="import-shell container-fluid px-4 py-4">
    <div class="page-hero mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge rounded-pill px-3 py-2">CSV</span>
            <span class="badge rounded-pill px-3 py-2">XLSX</span>
            <span class="badge rounded-pill px-3 py-2">XLS</span>
          </div>
          <h2 class="fw-bold mb-2">Impor Data Penduduk</h2>
          <p class="mb-0 text-white-50">Unggah file, cek pratinjau, cocokkan kolom, lalu jalankan impor dari satu halaman.</p>
        </div>
        <div class="text-md-end">
          <a href="/index.php?page=penduduk" class="btn btn-light fw-semibold rounded-3 px-3 py-2">Kembali ke Data Penduduk</a>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-lg-8">
        <div class="main-card p-4">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
              <h4 class="fw-bold mb-1">Langkah Impor</h4>
              <p class="subtle-note mb-0">Pilih file, lihat kolom yang terdeteksi, lalu sesuaikan pemetaan jika perlu.</p>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">Pratinjau & Pemetaan Kolom</span>
          </div>

          <form id="frm" enctype="multipart/form-data" class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
              <label for="file" class="form-label fw-semibold">Pilih File Impor</label>
              <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx,.xls" required>
              <div class="form-text">Dukungan file: CSV dan XLSX tanpa Composer. File XLS tetap memerlukan PhpSpreadsheet.</div>
            </div>
            <div class="col-12 col-md-4">
              <label for="mode" class="form-label fw-semibold">Mode Impor</label>
              <select id="mode" name="mode" class="form-select">
                <option value="insert_only">Tambah baru saja, jangan timpa data lama</option>
                <option value="insert_or_update">Tambah atau perbarui data yang sudah ada (berdasarkan NIK)</option>
              </select>
            </div>
            <div class="col-12 col-md-2 d-grid">
              <button type="button" id="detect" class="btn btn-primary fw-semibold py-2">Cek File</button>
            </div>
          </form>

          <div class="mt-4 row g-3 align-items-center">
            <div class="col-lg-7">
              <label for="templateName" class="form-label fw-semibold">Simpan Pemetaan sebagai Template</label>
              <div class="input-group">
                <input id="templateName" class="form-control" placeholder="Contoh: Template Penduduk">
                <button id="saveTemplate" class="btn btn-outline-primary fw-semibold" type="button">Simpan</button>
              </div>
            </div>
            <div class="col-lg-5">
              <label for="loadTemplate" class="form-label fw-semibold">Muat Template</label>
              <select id="loadTemplate" class="form-select">
                <option value="">-- Pilih template --</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="main-card p-4 h-100">
          <h5 class="fw-bold mb-3">Panduan Singkat</h5>
          <ul class="info-list">
            <li><span class="info-bullet"></span><span>Pastikan baris pertama berisi judul kolom seperti <strong>NIK</strong> dan <strong>Nama</strong>.</span></li>
            <li><span class="info-bullet"></span><span>Gunakan mode <strong>Tambah baru saja</strong> jika hanya ingin menambah data tanpa menimpa data lama.</span></li>
            <li><span class="info-bullet"></span><span>Gunakan mode <strong>Tambah atau perbarui</strong> jika NIK yang sama ingin diperbarui.</span></li>
            <li><span class="info-bullet"></span><span>Template pemetaan membantu jika struktur file sering dipakai berulang.</span></li>
          </ul>
        </div>
      </div>
    </div>

    <div id="preview" class="main-card p-4" style="display:none">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <div>
          <h4 class="fw-bold mb-1">Pratinjau Data</h4>
          <p class="subtle-note mb-0">Periksa hasil deteksi dan sesuaikan pemetaan kolom sebelum impor dijalankan.</p>
        </div>
        <button id="importBtn" class="btn btn-success fw-semibold px-4 py-2" type="button">Jalankan Impor</button>
      </div>
      <div id="mapping" class="mb-4"></div>
      <div id="sample" class="sample-card"></div>
    </div>

    <div class="mt-4">
      <div class="alert alert-info border-0 shadow-sm rounded-3 mb-0">
        <strong>Catatan:</strong> CSV dan XLSX dapat diproses tanpa Composer. Jika Anda ingin mengunggah file XLS, pastikan PhpSpreadsheet tersedia di server.
      </div>
    </div>
  </div>

  <script src="/js/import-preview.js"></script>
</body>
</html>
