from pathlib import Path
root = Path(__file__).resolve().parent
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    if 'mysqli_connect(' in text and 'koneksi.php' in text and 'require_once' in text:
        lines = text.splitlines()
        new_lines = [ln for ln in lines if 'mysqli_connect(' not in ln]
        new_text = '\n'.join(new_lines) + '\n'
        if new_text != text:
            p.write_text(new_text, encoding='utf-8')
            changed.append(str(p.relative_to(root)))
print('CHANGED=' + ','.join(changed))
