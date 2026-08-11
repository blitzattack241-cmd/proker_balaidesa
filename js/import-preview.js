document.addEventListener('DOMContentLoaded', function(){
  const fileInput = document.getElementById('file');
  const detectBtn = document.getElementById('detect');
  const preview = document.getElementById('preview');
  const mappingDiv = document.getElementById('mapping');
  const sampleDiv = document.getElementById('sample');
  let lastResponse = null;

  const fieldLabels = {
    skip: 'Lewati',
    nama: 'Nama',
    nik: 'NIK',
    no_kk: 'No. KK',
    jenis_kelamin: 'Jenis Kelamin',
    tgl_lahir: 'Tanggal Lahir',
    tempat_lahir: 'Tempat Lahir',
    alamat: 'Alamat',
    rt: 'RT',
    rw: 'RW',
    umur: 'Umur',
    pendidikan: 'Pendidikan',
    pekerjaan: 'Pekerjaan',
    agama: 'Agama'
  };

  function getFieldLabel(value) {
    return fieldLabels[value] || value;
  }

  function showError(message) {
    alert(message);
  }

  async function parseJsonResponse(response) {
    const body = await response.text();
    if (!body.trim()) {
      throw new Error('Server mengembalikan respons kosong (HTTP ' + response.status + ').');
    }

    let payload;
    try {
      payload = JSON.parse(body);
    } catch (_) {
      throw new Error('Server mengembalikan respons JSON tidak valid (HTTP ' + response.status + ').');
    }

    if (!payload || typeof payload !== 'object' || Array.isArray(payload)) {
      throw new Error('Server mengembalikan format respons impor yang tidak valid (HTTP ' + response.status + ').');
    }

    if (!response.ok && !payload.error) {
      payload.error = 'Permintaan impor gagal (HTTP ' + response.status + ').';
    }

    return payload;
  }

  detectBtn.addEventListener('click', function(){
    const f = fileInput.files[0];
    if (!f) return showError('Silakan pilih file terlebih dahulu.');
    const fd = new FormData();
    fd.append('file', f);
    fetch('/api/import_detect.php', {method:'POST', body: fd}).then(parseJsonResponse).then(resp=>{
      if (resp.error) return showError(resp.error);
      lastResponse = resp;
      renderMapping(resp);
      renderSample(resp);
      preview.style.display='block';
    }).catch(e=>{showError('Terjadi kesalahan saat membaca file: ' + e)});
  });

  // load templates
  fetch('/api/import_template.php').then(parseJsonResponse).then(js=>{
    const sel = document.getElementById('loadTemplate');
    if (!js.templates) return;
    Object.keys(js.templates).forEach(name=>{
      const o = document.createElement('option'); o.value = name; o.textContent = name; sel.appendChild(o);
    });
  }).catch(()=>{});

  function renderMapping(resp){
    mappingDiv.innerHTML='';
    const table = document.createElement('table');
    table.className = 'table table-bordered table-hover align-middle mapping-table';
    const tr = document.createElement('tr');
    tr.innerHTML = '<th>Kolom Asal</th><th>Saran Sistem</th><th>Pemetaan</th>';
    table.appendChild(tr);
    const canonFields = ['skip','nama','nik','no_kk','jenis_kelamin','tgl_lahir','tempat_lahir','alamat','rt','rw','umur','pendidikan','pekerjaan','agama'];
    resp.suggestions.forEach((s, idx)=>{
      const row = document.createElement('tr');
      const src = document.createElement('td'); src.textContent = s.original;
      const sug = document.createElement('td'); sug.textContent = s.suggested ? getFieldLabel(s.suggested) + ' ('+s.score+')' : '-';
      const selTd = document.createElement('td');
      const sel = document.createElement('select');
      sel.className = 'form-select form-select-sm';
      canonFields.forEach(f=>{const o=document.createElement('option');o.value=f; o.textContent=getFieldLabel(f); if(f===s.suggested) o.selected=true; sel.appendChild(o)});
      selTd.appendChild(sel);
      row.appendChild(src); row.appendChild(sug); row.appendChild(selTd);
      table.appendChild(row);
    });
    mappingDiv.appendChild(table);
  }

  function applyTemplate(template){
    // template is mapping: index -> field
    const selects = mappingDiv.querySelectorAll('select');
    selects.forEach((sel, idx)=>{
      if (template[idx]) {
        const val = template[idx];
        for (let i=0;i<sel.options.length;i++) if (sel.options[i].value===val) { sel.selectedIndex=i; break; }
      }
    });
  }

  function renderSample(resp){
    sampleDiv.innerHTML='';
    const h = document.createElement('div'); h.className = 'sample-title'; h.textContent='Contoh Baris Data (maksimal 10 baris)'; sampleDiv.appendChild(h);
    const tbl = document.createElement('table');
    tbl.className = 'table table-sm table-bordered bg-white mb-0';
    const hdr = document.createElement('tr'); resp.headers.forEach(hd=>{const th=document.createElement('th');th.textContent=hd;hdr.appendChild(th)}); tbl.appendChild(hdr);
    resp.sample.forEach(r=>{const tr=document.createElement('tr'); resp.headers.forEach((_,i)=>{const td=document.createElement('td'); td.textContent = r[i] ?? ''; tr.appendChild(td)}); tbl.appendChild(tr)});
    sampleDiv.appendChild(tbl);
  }

  document.getElementById('importBtn').addEventListener('click', function(){
    if (!lastResponse) return showError('Pratinjau belum dimuat. Silakan pilih file dan klik tombol cek file terlebih dahulu.');
    const f = fileInput.files[0];
    const fd = new FormData();
    fd.append('file', f);
    fd.append('header_index', lastResponse.header_index);
    const mode = document.getElementById('mode') ? document.getElementById('mode').value : 'insert_only';
    fd.append('mode', mode);
    // build mapping
    const selects = mappingDiv.querySelectorAll('select');
    const mapping = {};
    selects.forEach((sel, idx)=>{ mapping[idx] = sel.value; });
    fd.append('mapping', JSON.stringify(mapping));
    fetch('/api/import_execute.php', {method:'POST', body: fd}).then(parseJsonResponse).then(resp=>{
      if (resp.error) return showError(resp.error);
      alert('Berhasil: '+resp.inserted+' ditambahkan, '+resp.updated+' diperbarui, '+resp.failed+' gagal, '+resp.skipped+' dilewati.');
      if (resp.fail_rows && resp.fail_rows.length) console.log('Baris gagal:', resp.fail_rows);
      window.location='/index.php?page=penduduk';
    }).catch(e=>{showError('Impor gagal: '+e)});
  });

  document.getElementById('saveTemplate').addEventListener('click', function(){
    const name = (document.getElementById('templateName').value || '').trim();
    if (!name) return showError('Silakan isi nama template terlebih dahulu.');
    const selects = mappingDiv.querySelectorAll('select');
    const mapping = {};
    selects.forEach((sel, idx)=>{ mapping[idx] = sel.value; });
    const fd = new FormData();
    fd.append('action','save'); fd.append('name', name); fd.append('content', JSON.stringify(mapping));
    fetch('/api/import_template.php', {method:'POST', body: fd}).then(parseJsonResponse).then(resp=>{ if (resp.ok) { alert('Template berhasil disimpan.'); const opt = document.createElement('option'); opt.value=name; opt.textContent=name; document.getElementById('loadTemplate').appendChild(opt);} else showError(resp.error || 'Penyimpanan template gagal.'); }).catch(e=>showError('Gagal menyimpan template: '+e));
  });

  document.getElementById('loadTemplate').addEventListener('change', function(){
    const name = this.value;
    if (!name) return;
    fetch('/data/import_mappings.json').then(parseJsonResponse).then(js=>{
      if (js.templates && js.templates[name]) applyTemplate(js.templates[name]);
    }).catch(()=>showError('Template tidak dapat dimuat.'));
  });
});
