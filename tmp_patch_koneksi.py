from pathlib import Path
import re

root = Path(__file__).resolve().parent
pattern = re.compile(r'^[ \t]*\$koneksi\s*=\s*mysqli_connect\([^;]*\);\s*$', re.MULTILINE)
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php':
        continue
    text = p.read_text('utf-8', errors='ignore')
    orig = text
    rel = p.relative_to(root)
    if pattern.search(text):
        has_include = 'koneksi.php' in text and 'require_once' in text
        if has_include:
            text = pattern.sub('', text)
        else:
            if rel.parent == Path('.'):
                include_line = "require_once __DIR__ . '/koneksi.php';"
            else:
                include_path = '/'.join(['..'] * len(rel.parent.parts) + ['koneksi.php'])
                include_line = f"require_once __DIR__ . '/{include_path}';"
            text = pattern.sub(include_line, text)
    text = text.replace('href="index.php?page=surat-garapan"', 'href="index.php?page=surat-garapan-sawah"')
    text = text.replace("href='index.php?page=surat-garapan'", "href='index.php?page=surat-garapan-sawah'")
    text = text.replace("window.location.href = 'index.php?page=surat-garapan'", "window.location.href = 'index.php?page=surat-garapan-sawah'")
    text = text.replace('page=data-penduduk', 'page=penduduk')
    if text != orig:
        p.write_text(text, encoding='utf-8')
        changed.append(str(rel))

index = root / 'index.php'
text = index.read_text('utf-8', errors='ignore')
if "case 'surat-garapan':" not in text and "case 'surat-garapan-sawah':" in text:
    text = text.replace("case 'surat-garapan-sawah':", "case 'surat-garapan':\n                        case 'surat-garapan-sawah':")
    index.write_text(text, encoding='utf-8')
    changed.append('index.php (alias case added)')

print('CHANGED=' + ','.join(changed))
