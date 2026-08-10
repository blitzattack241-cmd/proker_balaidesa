from pathlib import Path
import re

root = Path(__file__).resolve().parent
pattern = re.compile(r'^[ \t]*\$koneksi\s*=\s*mysqli_connect\([^;]*\);\s*$', re.MULTILINE)
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    orig = text
    rel = p.relative_to(root)
    # If mysqli_connect line exists
    if pattern.search(text):
        # Skip if already has koneksi include
        if 'koneksi.php' in text and 'require_once' in text:
            text = pattern.sub('', text)
        else:
            # Build include path
            if rel.parent == Path('.'):
                include_line = "require_once __DIR__ . '/koneksi.php';"
            else:
                include_path = '/'.join(['..'] * len(rel.parent.parts) + ['koneksi.php'])
                include_line = f"require_once __DIR__ . '/{include_path}';"
            text = pattern.sub(include_line, text)
    # Normalize known route issues
    text = text.replace('page=data-penduduk', 'page=penduduk')
    text = text.replace('href="index.php?page=surat-garapan"', 'href="index.php?page=surat-garapan-sawah"')
    text = text.replace("href='index.php?page=surat-garapan'", "href='index.php?page=surat-garapan-sawah'")
    text = text.replace("window.location.href = 'index.php?page=surat-garapan'", "window.location.href = 'index.php?page=surat-garapan-sawah'")
    # Remove accidental duplicate blank lines left by substitution
    text = re.sub(r"\n{3,}", "\n\n", text)
    if text != orig:
        p.write_text(text, encoding='utf-8')
        changed.append(str(rel))

print('CHANGED=' + ','.join(changed))
