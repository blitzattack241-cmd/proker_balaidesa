Panduan impor data penduduk

Gambaran umum
- Gunakan `/pages/import_upload.php` untuk mengunggah file dan melihat pratinjau pemetaan kolom.
- API yang digunakan:
  - `/api/import_detect.php` : mendeteksi header dan memberi saran pemetaan
  - `/api/import_execute.php` : menjalankan impor berdasarkan pemetaan yang dipilih
  - `/api/import_template.php` : menampilkan, menyimpan, dan menghapus template pemetaan

Catatan
- Mode default adalah `insert_only` sehingga data NIK yang sudah ada tidak akan ditimpa.
- Jika ingin memperbarui data lama, pilih mode `Tambah atau perbarui data yang sudah ada`.
- Sinonim pemetaan dan template yang disimpan berada di `data/import_mappings.json`.

Keamanan dan kehati-hatian
- Akses ke halaman impor sebaiknya dibatasi hanya untuk pengguna yang berwenang.
- File yang diunggah dibaca dari folder sementara dan tidak disimpan permanen secara bawaan.

Pemecahan masalah
- Jika deteksi header gagal, pastikan baris header pada file memuat variasi kata seperti `Nama` dan `NIK`.
- Jika file CSV bermasalah karena encoding, simpan ulang file dalam format UTF-8.
