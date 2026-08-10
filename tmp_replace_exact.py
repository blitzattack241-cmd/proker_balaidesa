from pathlib import Path
root = Path(__file__).resolve().parent
variants = [
    '$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");',
    "$koneksi = mysqli_connect('localhost', 'root', '', 'db_balaidesa');",
]
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    orig = text
    rel = p.relative_to(root)
    for v in variants:
        if v in text:
            # determine include
            if 'require_once' in text and 'koneksi.php' in text:
                text = text.replace(v, '')
            else:
                if rel.parent == Path('.'):
                    inc = "require_once __DIR__ . '/koneksi.php';"
                else:
                    include_path = '/'.join(['..'] * len(rel.parent.parts) + ['koneksi.php'])
                    inc = f"require_once __DIR__ . '/{include_path}';"
                text = text.replace(v, inc)
    if text != orig:
        p.write_text(text, encoding='utf-8')
        changed.append(str(rel))
print('CHANGED=' + ','.join(changed))
