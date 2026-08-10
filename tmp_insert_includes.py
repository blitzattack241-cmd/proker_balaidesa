from pathlib import Path
root = Path(__file__).resolve().parent
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    if 'mysqli_connect(' in text and 'koneksi.php' not in text:
        lines = text.splitlines()
        insert_idx = None
        # try to place after session_start() if present near top
        for i in range(min(10, len(lines))):
            if 'session_start()' in lines[i]:
                insert_idx = i+1
                break
        if insert_idx is None:
            # after <?php line
            for i in range(min(3, len(lines))):
                if lines[i].strip().startswith('<?php'):
                    insert_idx = i+1
                    break
        if insert_idx is None:
            insert_idx = 0
        # compute include path relative to file
        rel = p.relative_to(root)
        depth = len(rel.parent.parts)
        if depth == 0:
            include_line = "require_once __DIR__ . '/koneksi.php';"
        else:
            include_path = '/'.join(['..'] * depth + ['koneksi.php'])
            include_line = f"require_once __DIR__ . '/{include_path}';"
        # avoid duplicate if next line already is require
        if insert_idx < len(lines) and 'koneksi.php' in lines[insert_idx]:
            pass
        else:
            lines.insert(insert_idx, include_line)
            new_text = '\n'.join(lines) + '\n'
            p.write_text(new_text, encoding='utf-8')
            changed.append(str(rel))
print('CHANGED=' + ','.join(changed))
