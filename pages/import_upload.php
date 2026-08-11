<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../koneksi.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Import Penduduk</title>
  <link rel="stylesheet" href="/css/styles.css">
  <style>table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:6px}</style>
</head>
<body>
  <h2>Import Penduduk</h2>
  <form id="frm" enctype="multipart/form-data">
    <input type="file" name="file" id="file" accept=".csv,.xlsx,.xls" required>
    <label for="mode">Mode:</label>
    <select id="mode" name="mode">
      <option value="insert_only">Insert only (do not overwrite existing)</option>
      <option value="insert_or_update">Insert or Update existing (match by NIK)</option>
    </select>
    <button type="button" id="detect">Detect & Preview</button>
  </form>
  <p style="margin-top:8px;color:#666">CSV and XLSX work without Composer. XLS needs PhpSpreadsheet.</p>
  <div style="margin-top:8px">
    <label for="templateName">Save mapping as template:</label>
    <input id="templateName" placeholder="Template name">
    <button id="saveTemplate">Save template</button>
    <select id="loadTemplate"><option value="">--Load template--</option></select>
  </div>

  <div id="preview" style="margin-top:12px;display:none">
    <h3>Preview & Mapping</h3>
    <div id="mapping"></div>
    <div id="sample"></div>
    <button id="importBtn">Import</button>
  </div>

  <script src="/js/import-preview.js"></script>
</body>
</html>
