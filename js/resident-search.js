document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('residentSearchInput');
  const suggestionsBox = document.getElementById('residentSearchSuggestions');
  const form = input ? input.closest('form') : null;
  if (!input || !suggestionsBox || !form) return;

  let debounceTimer;
  let activeIndex = -1;
  let results = [];
  let requestController;

  function hideSuggestions() {
    activeIndex = -1;
    suggestionsBox.innerHTML = '';
    suggestionsBox.classList.add('d-none');
    input.setAttribute('aria-expanded', 'false');
  }

  function selectResult(index) {
    const resident = results[index];
    if (!resident) return;

    input.value = resident.nik || resident.nama || '';
    hideSuggestions();
    if (typeof form.requestSubmit === 'function') {
      form.requestSubmit();
    } else {
      form.submit();
    }
  }

  function setActiveIndex(index) {
    const options = suggestionsBox.querySelectorAll('[role="option"]');
    if (!options.length) return;

    activeIndex = (index + options.length) % options.length;
    options.forEach(function (option, optionIndex) {
      option.setAttribute('aria-selected', optionIndex === activeIndex ? 'true' : 'false');
    });
    options[activeIndex].scrollIntoView({ block: 'nearest' });
  }

  function renderResults(data) {
    results = Array.isArray(data) ? data : [];
    suggestionsBox.innerHTML = '';
    activeIndex = -1;

    if (!results.length) {
      const empty = document.createElement('div');
      empty.className = 'px-3 py-2 small text-muted';
      empty.textContent = 'Data penduduk tidak ditemukan.';
      suggestionsBox.appendChild(empty);
    } else {
      results.forEach(function (resident, index) {
        const option = document.createElement('button');
        option.type = 'button';
        option.className = 'resident-search-suggestion';
        option.setAttribute('role', 'option');
        option.setAttribute('aria-selected', 'false');

        const name = document.createElement('strong');
        name.textContent = resident.nama || '-';
        const meta = document.createElement('span');
        meta.className = 'resident-search-suggestion-meta';
        meta.textContent = 'NIK: ' + (resident.nik || '-') + ' · KK: ' + (resident.no_kk || '-');
        option.append(name, meta);
        option.addEventListener('click', function () { selectResult(index); });
        suggestionsBox.appendChild(option);
      });
    }

    suggestionsBox.classList.remove('d-none');
    input.setAttribute('aria-expanded', 'true');
  }

  async function loadSuggestions(term) {
    if (requestController) requestController.abort();
    requestController = new AbortController();

    try {
      const response = await fetch('/api/get_penduduk.php?q=' + encodeURIComponent(term), {
        signal: requestController.signal,
      });
      if (!response.ok) throw new Error('Suggestion request failed');
      const payload = await response.json();
      if (input.value.trim() !== term) return;
      renderResults(payload.results);
    } catch (error) {
      if (error.name !== 'AbortError') hideSuggestions();
    }
  }

  input.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const term = input.value.trim();
    if (term.length < 2) {
      hideSuggestions();
      return;
    }

    debounceTimer = setTimeout(function () { loadSuggestions(term); }, 250);
  });

  input.addEventListener('keydown', function (event) {
    if (suggestionsBox.classList.contains('d-none')) return;
    if (event.key === 'ArrowDown') {
      event.preventDefault();
      setActiveIndex(activeIndex + 1);
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      setActiveIndex(activeIndex - 1);
    } else if (event.key === 'Enter' && activeIndex >= 0) {
      event.preventDefault();
      selectResult(activeIndex);
    } else if (event.key === 'Escape') {
      hideSuggestions();
    }
  });

  input.addEventListener('blur', function () {
    setTimeout(hideSuggestions, 150);
  });
});
