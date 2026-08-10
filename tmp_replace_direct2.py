from pathlib import Path
import re
root = Path(__file__).resolve().parent
pattern = re.compile(r"\$koneksi\s*=\s*mysqli_connect\([^;]*\);", re.IGNORECASE)
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    matches = list(pattern.finditer(text))
    if not matches:
        continue
    rel = p.relative_to(root)
    depth = len(rel.parent.parts)
    if depth == 0:
        include_line = "require_once __DIR__ . '/koneksi.php';"
    else:
        include_path = '/'.join(['..'] * depth + ['koneksi.php'])
        include_line = f"require_once __DIR__ . '/{include_path}';"
    # If file already has include, remove connect lines
    if 'koneksi.php' in text and 'require_once' in text:
        new_text = pattern.sub('', text)
    else:
        # replace first occurrence with include, remove others
        new_text = pattern.sub('', text)
        # insert include after <?php or session_start
        lines = new_text.splitlines()
        insert_idx = 0
        for i in range(min(20, len(lines))):
            if 'session_start()' in lines[i]:
                insert_idx = i+1
                break
            if lines[i].strip().startswith('<?php'):
                insert_idx = i+1
                break
        if not any('koneksi.php' in ln for ln in lines):
            lines.insert(insert_idx, include_line)
        new_text = '\n'.join(lines) + '\n'
    new_text = re.sub(r"\n{3,}", "\n\n", new_text)
    if new_text != text:
        p.write_text(new_text, encoding='utf-8')
        changed.append(str(rel))
print('CHANGED=' + ','.join(changed))
