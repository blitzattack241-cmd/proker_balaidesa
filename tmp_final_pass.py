from pathlib import Path
import re
root = Path(__file__).resolve().parent
pattern_connect = re.compile(r"\$koneksi\s*=\s*mysqli_connect\([^;]*\);", re.IGNORECASE)
include_patterns = [r"require_once\s+__DIR__\s*\.\s*'/.*/koneksi.php'", r"require_once\s+__DIR__\s*\.\s*'/koneksi.php'", r"require_once\s+'koneksi.php'", r"include\s+'koneksi.php'", r"include_once\s+'koneksi.php'"]
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    orig = text
    has_connect = bool(pattern_connect.search(text))
    if not has_connect:
        continue
    # check if file already includes koneksi
    has_include = any(re.search(ip, text) for ip in include_patterns)
    lines = text.splitlines()
    # remove all connect lines
    new_lines = [ln for ln in lines if not pattern_connect.search(ln)]
    # ensure include present
    if not has_include:
        # find insert position
        insert_idx = 0
        for i in range(min(20, len(new_lines))):
            if 'session_start()' in new_lines[i]:
                insert_idx = i+1
                break
            if new_lines[i].strip().startswith('<?php'):
                insert_idx = i+1
                break
        rel = p.relative_to(root)
        depth = len(rel.parent.parts)
        if depth == 0:
            include_line = "require_once __DIR__ . '/koneksi.php';"
        else:
            include_path = '/'.join(['..'] * depth + ['koneksi.php'])
            include_line = f"require_once __DIR__ . '/{include_path}';"
        # avoid inserting duplicate
        if insert_idx < len(new_lines) and 'koneksi.php' in new_lines[insert_idx]:
            pass
        else:
            new_lines.insert(insert_idx, include_line)
    new_text = '\n'.join(new_lines) + '\n'
    # normalize multiple blank lines
    new_text = re.sub(r"\n{3,}", "\n\n", new_text)
    if new_text != orig:
        p.write_text(new_text, encoding='utf-8')
        changed.append(str(p.relative_to(root)))
print('CHANGED=' + ','.join(changed))
