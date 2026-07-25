<?php
// Koneksi database (sesuaikan dengan file koneksi kamu)
include 'koneksi.php'; 

function sync_agenda_surat($koneksi) {
    // 1. Kosongkan tabel agenda_surat terlebih dahulu (agar tidak duplikat saat dipanggil)
    mysqli_query($koneksi, "TRUNCATE TABLE agenda_surat");

    // 2. Daftar query insert dari masing-masing tabel asal
    $queries = [
        // Surat Garapan Sawah
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Garapan Sawah', IFNULL(peruntukan, 'Pengurusan Garapan Sawah'), nama_pemohon, nomor_surat, tanggal_surat, IFNULL(peruntukan, '-'), IFNULL(keterangan, '-'), 'tb_surat_garapan'
         FROM tb_surat_garapan",

        // Surat Ahli Waris
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Ahli Waris', 'Pengurusan Ahli Waris', nama_pemohon, nomor_surat, tanggal_surat, 'Ahli Waris / Pewarisan', IFNULL(keterangan, '-'), 'tb_surat_waris'
         FROM tb_surat_waris",

        // Surat Undangan
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Undangan', IFNULL(tujuan, 'Surat Undangan'), nama_pemohon, nomor_surat, tanggal_surat, IFNULL(tujuan, '-'), IFNULL(keterangan, '-'), 'tb_surat_undangan'
         FROM tb_surat_undangan",

        // Surat Kelahiran
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Kelahiran', 'Pelaporan Kelahiran', nama_pelapor, nomor_surat, tanggal_surat, 'Dinas Dukcapil', IFNULL(keterangan, '-'), 'tb_surat_kelahiran'
         FROM tb_surat_kelahiran",

        // Surat Kematian
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Kematian', 'Pelaporan Kematian', nama_pelapor, nomor_surat, tanggal_surat, 'Dinas Dukcapil', IFNULL(keterangan, '-'), 'tb_surat_kematian'
         FROM tb_surat_kematian",

        // Surat Pengantar
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Pengantar', IFNULL(keperluan, 'Surat Pengantar'), nama_warga, nomor_surat, tanggal_surat, IFNULL(keperluan, '-'), IFNULL(keterangan, '-'), 'tb_surat_pengantar'
         FROM tb_surat_pengantar",

        // Surat Domisili
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Surat Domisili', IFNULL(keperluan, 'Keterangan Domisili'), nama_warga, nomor_surat, tanggal_surat, IFNULL(keperluan, '-'), IFNULL(keterangan, '-'), 'tb_surat_domisili'
         FROM tb_surat_domisili",

        // Pengantar Dukcapil (Diambil dari tb_surat_dukcapil)
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'Pengantar Dukcapil', IFNULL(jenis_dikirim, 'Laporan Dukcapil'), IFNULL(created_by, '-'), nomor_surat, tanggal_surat, 'Dinas Dukcapil', IFNULL(keterangan, '-'), 'tb_surat_dukcapil'
         FROM tb_surat_dukcapil",

        // SKTM Bumil
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'SKTM Bumil', 'SKTM Ibu Hamil', nama_warga, nomor_surat, tanggal_surat, 'Puskesmas / Dinsos', IFNULL(keterangan, '-'), 'tb_sktm_bumil'
         FROM tb_sktm_bumil",

        // SKTM Rawat Inap/Jalan
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'SKTM Rawat Inap/Jalan', 'SKTM Pengobatan/Rawat', nama_warga, nomor_surat, tanggal_surat, IFNULL(rumah_sakit, 'Rumah Sakit'), IFNULL(keterangan, '-'), 'tb_sktm_rawat'
         FROM tb_sktm_rawat",

        // SKTM KIS
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'SKTM KIS', 'SKTM Kartu Indonesia Sehat', nama_warga, nomor_surat, tanggal_surat, 'Dinsos / BPJS', IFNULL(keterangan, '-'), 'tb_sktm_kis'
         FROM tb_sktm_kis",

        // SKTM KIP
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'SKTM KIP', 'SKTM Kartu Indonesia Pintar', nama_warga, nomor_surat, tanggal_surat, IFNULL(sekolah, 'Sekolah / Kampus'), IFNULL(keterangan, '-'), 'tb_sktm_kip'
         FROM tb_sktm_kip",

        // SKTM Stunting
        "INSERT INTO agenda_surat (id_surat, jenis_surat, isi_singkat, nama_pemohon, nomor_surat, tanggal_surat, tujuan, keterangan, tabel_asal)
         SELECT id_surat, 'SKTM Stunting', 'SKTM Penanganan Stunting', nama_warga, nomor_surat, tanggal_surat, 'Puskesmas / Dinsos', IFNULL(keterangan, '-'), 'tb_sktm_stunting'
         FROM tb_sktm_stunting"
    ];

    // Eksekusi semua query
    foreach ($queries as $q) {
        mysqli_query($koneksi, $q);
    }
}