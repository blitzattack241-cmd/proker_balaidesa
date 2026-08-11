Importer design and usage

Overview
- Use `/pages/import_upload.php` to upload files and preview mappings.
- APIs:
  - `/api/import_detect.php` : detect headers and return suggestions
  - `/api/import_execute.php` : execute import with provided mapping
  - `/api/import_template.php` : list/save/delete mapping templates

Notes
- By default import mode is `insert_only` (will not overwrite existing NIK records).
- To update existing rows choose `Insert or Update existing` mode.
- Mapping synonyms and saved templates are stored in `data/import_mappings.json`.

Security and safety
- Access to import UI should be limited to authorized users.
- Uploaded files are read from temporary path and not persisted long-term by default.

Troubleshooting
- If header detection fails, open the file and ensure the header row contains `Nama` and `NIK` variants.
- For CSV encoding problems, re-save file as UTF-8.
