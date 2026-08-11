document.addEventListener('DOMContentLoaded', function(){
  const fileInput = document.getElementById('file');
  const detectBtn = document.getElementById('detect');
  const preview = document.getElementById('preview');
  const mappingDiv = document.getElementById('mapping');
  const sampleDiv = document.getElementById('sample');
  let lastResponse = null;

  detectBtn.addEventListener('click', function(){
    const f = fileInput.files[0];
    if (!f) return alert('Choose a file');
    const fd = new FormData();
    fd.append('file', f);
    fetch('/api/import_detect.php', {method:'POST', body: fd}).then(r=>r.json()).then(resp=>{
      if (resp.error) return alert(resp.error);
      lastResponse = resp;
      renderMapping(resp);
      renderSample(resp);
      preview.style.display='block';
    }).catch(e=>{alert('Error: '+e)});
  });

  // load templates
  fetch('/api/import_template.php').then(r=>r.json()).then(js=>{
    const sel = document.getElementById('loadTemplate');
    if (!js.templates) return;
    Object.keys(js.templates).forEach(name=>{
      const o = document.createElement('option'); o.value = name; o.textContent = name; sel.appendChild(o);
    });
  }).catch(()=>{});

  function renderMapping(resp){
    mappingDiv.innerHTML='';
    const table = document.createElement('table');
    const tr = document.createElement('tr');
    tr.innerHTML = '<th>Source Column</th><th>Suggested</th><th>Map To</th>';
    table.appendChild(tr);
    const canonFields = ['skip','nama','nik','no_kk','jenis_kelamin','tgl_lahir','tempat_lahir','alamat','rt','rw','umur','pendidikan','pekerjaan','agama'];
    resp.suggestions.forEach((s, idx)=>{
      const row = document.createElement('tr');
      const src = document.createElement('td'); src.textContent = s.original;
      const sug = document.createElement('td'); sug.textContent = s.suggested + ' ('+s.score+')';
      const selTd = document.createElement('td');
      const sel = document.createElement('select');
      canonFields.forEach(f=>{const o=document.createElement('option');o.value=f; o.textContent=f; if(f===s.suggested) o.selected=true; sel.appendChild(o)});
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
    const h = document.createElement('div'); h.textContent='Sample rows (first 10)'; sampleDiv.appendChild(h);
    const tbl = document.createElement('table');
    const hdr = document.createElement('tr'); resp.headers.forEach(hd=>{const th=document.createElement('th');th.textContent=hd;hdr.appendChild(th)}); tbl.appendChild(hdr);
    resp.sample.forEach(r=>{const tr=document.createElement('tr'); resp.headers.forEach((_,i)=>{const td=document.createElement('td'); td.textContent = r[i] ?? ''; tr.appendChild(td)}); tbl.appendChild(tr)});
    sampleDiv.appendChild(tbl);
  }

  document.getElementById('importBtn').addEventListener('click', function(){
    if (!lastResponse) return alert('No preview loaded');
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
    fetch('/api/import_execute.php', {method:'POST', body: fd}).then(r=>r.json()).then(resp=>{
      if (resp.error) return alert(resp.error);
      alert('Inserted: '+resp.inserted+' Updated: '+resp.updated+' Failed: '+resp.failed+' Skipped: '+resp.skipped);
      if (resp.fail_rows && resp.fail_rows.length) console.log('Failed rows:', resp.fail_rows);
      window.location='/index.php?page=penduduk';
    }).catch(e=>{alert('Import failed: '+e)});
  });

  document.getElementById('saveTemplate').addEventListener('click', function(){
    const name = (document.getElementById('templateName').value || '').trim();
    if (!name) return alert('Provide template name');
    const selects = mappingDiv.querySelectorAll('select');
    const mapping = {};
    selects.forEach((sel, idx)=>{ mapping[idx] = sel.value; });
    const fd = new FormData();
    fd.append('action','save'); fd.append('name', name); fd.append('content', JSON.stringify(mapping));
    fetch('/api/import_template.php', {method:'POST', body: fd}).then(r=>r.json()).then(resp=>{ if (resp.ok) { alert('Template saved'); const opt = document.createElement('option'); opt.value=name; opt.textContent=name; document.getElementById('loadTemplate').appendChild(opt);} else alert('Save failed'); }).catch(e=>alert('Save error'));
  });

  document.getElementById('loadTemplate').addEventListener('change', function(){
    const name = this.value;
    if (!name) return;
    fetch('/data/import_mappings.json').then(r=>r.json()).then(js=>{
      if (js.templates && js.templates[name]) applyTemplate(js.templates[name]);
    }).catch(()=>alert('Failed to load template'));
  });
});
