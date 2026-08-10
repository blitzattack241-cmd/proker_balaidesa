from pathlib import Path
root = Path(__file__).resolve().parent
changed = []
for p in root.rglob('*.php'):
    if p.name == 'koneksi.php' or 'vendor' in p.parts:
        continue
    text = p.read_text('utf-8', errors='ignore')
    if 'mysqli_connect(' not in text:
        continue
    lines = text.splitlines()
    new_lines = []
    modified = False
    for i,line in enumerate(lines):
        if 'mysqli_connect(' in line:
            # determine include
            rel = p.relative_to(root)
            if 'koneksi.php' in text and 'require_once' in text:
                # already has include; remove the line
                include_line = None
            else:
                if rel.parent == Path('.'):
                    include_line = "require_once __DIR__ . '/koneksi.php';"
                else:
                    include_path = '/'.join(['..'] * len(rel.parent.parts) + ['koneksi.php'])
                    include_line = f"require_once __DIR__ . '/{include_path}';"
            if include_line:
                new_lines.append(include_line)
            # mark modified
            modified = True
        else:
            new_lines.append(line)
    if modified:
        new_text = '\n'.join(new_lines) + '\n'
        # collapse multiple blank lines
        import re
        new_text = re.sub(r"\n{3,}", "\n\n", new_text)
        p.write_text(new_text, encoding='utf-8')
        changed.append(str(p.relative_to(root)))
print('CHANGED=' + ','.join(changed))
