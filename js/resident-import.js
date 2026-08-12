document.addEventListener('DOMContentLoaded', function () {
  const importButton = document.getElementById('residentImportButton');
  const fileInput = document.getElementById('residentImportFile');
  const progress = document.getElementById('residentImportProgress');
  const status = document.getElementById('residentImportStatus');
  const progressBar = document.getElementById('residentImportProgressBar');
  const result = document.getElementById('residentImportResult');
  const doneButton = document.getElementById('residentImportDone');

  if (!importButton || !fileInput || !progress) return;

  function setProgress(message) {
    status.textContent = message;
  }

  function showProgress(message) {
    result.className = 'small mt-3 d-none';
    doneButton.classList.add('d-none');
    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated bg-success w-100';
    progress.classList.remove('d-none');
    progress.classList.add('d-flex');
    setProgress(message);
  }

  function showResult(message, isError) {
    progressBar.className = 'progress-bar ' + (isError ? 'bg-danger' : 'bg-success') + ' w-100';
    result.textContent = message;
    result.className = 'small mt-3 ' + (isError ? 'text-danger' : 'text-success');
    doneButton.textContent = isError ? 'Tutup' : 'Muat Ulang Data';
    doneButton.classList.remove('d-none');
  }

  async function parseJsonResponse(response) {
    const body = await response.text();
    if (!body.trim()) throw new Error('Server mengembalikan respons kosong.');

    let payload;
    try {
      payload = JSON.parse(body);
    } catch (_) {
      throw new Error('Server mengembalikan respons impor yang tidak valid.');
    }

    if (!payload || typeof payload !== 'object' || Array.isArray(payload)) {
      throw new Error('Server mengembalikan format respons yang tidak valid.');
    }
    if (!response.ok || payload.ok === false) {
      throw new Error(payload.error || ('Impor gagal (HTTP ' + response.status + ').'));
    }

    return payload;
  }

  async function postFile(url, file) {
    const formData = new FormData();
    formData.append('file', file);
    const response = await fetch(url, { method: 'POST', body: formData });
    return parseJsonResponse(response);
  }

  importButton.addEventListener('click', function () {
    fileInput.value = '';
    fileInput.click();
  });

  fileInput.addEventListener('change', async function () {
    const file = fileInput.files[0];
    if (!file) return;

    try {
      showProgress('Membaca dan memvalidasi struktur file…');
      await postFile('/api/import_detect.php', file);

      setProgress('Struktur file valid. Mengimpor data penduduk…');
      const summary = await postFile('/api/import_execute.php', file);
      const details = [
        summary.inserted + ' ditambahkan',
        summary.updated + ' diperbarui',
        summary.skipped_existing + ' NIK sudah ada',
        summary.skipped_invalid + ' baris tidak valid',
        summary.skipped_header_rows + ' baris header dilewati',
        summary.failed + ' gagal',
      ];
      showResult('Impor selesai: ' + details.join(', ') + '.', false);
    } catch (error) {
      showResult(error.message || 'Impor gagal diproses.', true);
    }
  });

  doneButton.addEventListener('click', function () {
    if (result.classList.contains('text-success')) {
      window.location.reload();
    } else {
      progress.classList.add('d-none');
      progress.classList.remove('d-flex');
    }
  });
});
