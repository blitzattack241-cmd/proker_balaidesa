<?php
// ============================================================
// Halaman Profil Desa
// ============================================================
$desa = [
    'nama'      => 'Berugenjang',
    'kecamatan' => 'Undaan',
    'kabupaten' => 'Kudus',
    'provinsi'  => 'Jawa Tengah',
    'penduduk'  => '±1.432',
    'dusun'     => '7 / 2',
    'luas'      => '±2,26 km²',
    'berdiri'   => '2005',
    'sejarah'   => [
        'Adanya ketimpangan pembagian alokasi dana berbanding terbalik dengan potensi yang dimiliki kedua dukuh tersebut. Karena itulah, warga memilih untuk memperjuangkan haknya dengan mendirikan desa sendiri, terpisah dari Desa Lambangan.',

        'Tentu saja hal ini mendapat respon dari warga dan aparat Desa Lambangan. Banyak dari mereka yang tidak rela melepas kedua dukuh tersebut. Sebuah proses panjang dilalui warga Dukuh Beru dan Genjang untuk menyiapkan wilayahnya menjadi sebuah desa mandiri. Lobi dengan anggota dewan hingga melakukan aksi unjuk rasa pun digelar demi suksesi tersebut.',

        'Tak sedikit dana yang mereka keluarkan. Dari pengakuan Supar, hampir 400 juta rupiah lebih telah mereka keluarkan. Dana sebanyak ini digunakan untuk membiayai berbagai hal, antara lain kegiatan unjuk rasa, akomodasi wakil masyarakat, hingga memberi “bingkisan” kepada anggota dewan.',

        'Puncaknya, sempat terjadi bentrokan massal yang melibatkan warga Desa Lambangan dengan warga Beru dan Genjang. Ini terjadi manakala kedua belah pihak bertemu dalam unjuk rasa di halaman DPRD Kudus pada medio 2004. Korban jiwa pun tak terelakkan di kedua belah pihak.',

        'Setelah melalui proses yang menyita banyak tenaga, pikiran, biaya, bahkan nyawa tersebut, pada Juli 2004 diangkatlah Supar sebagai Pejabat Sementara (PJs) Kepala Desa Berugenjang. Ini merupakan hasil pertemuan kedua belah pihak yang difasilitasi oleh Bupati Kudus saat itu, Tamsil, yang mendesak kedua belah pihak untuk menghentikan segala tindakan anarkis dan segera mencari titik temu.',

        'Setelah diangkat sebagai PJs, masalah tak lantas berhenti. Supar mengaku jika pihaknya sempat merasa diabaikan haknya. Baru setelah bulan September 2005, ia diangkat menjadi pejabat desa definitif. Selama proses tersebut, banyak pihak internal yang menginginkan kekuasaan desa, yang menjadi halangan tersendiri. Hingga akhirnya, pada bulan November 2005, Supar diangkat menjadi Kepala Desa Berugenjang secara resmi.',

        'Desa Berugenjang merupakan salah satu desa di Kecamatan Undaan, Kabupaten Kudus, Jawa Tengah. Nama Berugenjang dipercaya berasal dari penggabungan dua wilayah yakni Dukuh Beru dan Dukuh Genjang yang memisahkan diri dari desa induk.'
    ],
    'visi'      => 'Mewujudkan Desa Berugenjang yang maju, mandiri, sejahtera, dan berbudaya.'
        . ' Desa Berugenjang yang sejahtera, terpenuhinya di segala bidang aspek fisik dan non-fisik serta pelayanan yang baik dan prima kepada masyarakat dengan didukung tertib administrasi serta menciptakan suasana yang aman dan religius.',
    'misi'      => [
        'Meningkatkan disiplin aparat pemerintahan desa.',
        'Menyelenggarakan tertib administrasi pemerintahan desa.',
        'Meningkatkan penggalian sumber pendapatan asli desa.',
        'Menciptakan hubungan baik dengan lembaga-lembaga desa.',
        'Memberikan pelayanan yang cepat dan tepat kepada masyarakat desa.',
        'Mengembangkan ekonomi warga berbasis pertanian dan UMKM.'
    ],
    'struktur'  => [
        ['jabatan' => 'Kepala Desa', 'nama' => 'Kiswo, SE', 'foto' => 'assets/img/kades.jpg'],
        ['jabatan' => 'Kaur Pembangunan & Kesra', 'nama' => 'Sulikan', 'foto' => 'assets/img/sulikan.jpg'],
        ['jabatan' => 'Kaur Keuangan & Umum', 'nama' => 'Jamiatun', 'foto' => 'assets/img/jamiatun.jpg'],
        ['jabatan' => 'Pembantu Kaur Keuangan & Umum', 'nama' => 'Harto', 'foto' => 'assets/img/harto.jpg'],
        ['jabatan' => 'Kaur Pemerintahan', 'nama' => 'Sutikno', 'foto' => 'assets/img/sutikno.jpg'],
        ['jabatan' => 'Pembantu Kaur Pemerintahan', 'nama' => 'Sukarin', 'foto' => 'assets/img/sukarin.jpg'],
        ['jabatan' => 'Kepala Dusun', 'nama' => 'Ngadirun', 'foto' => 'assets/img/ngadirun.jpg'],
        ['jabatan' => 'Modin', 'nama' => 'Subur', 'foto' => 'assets/img/subur.jpg'],
    ],
    'batas'     => [
        ['arah' => 'Utara', 'desa' => 'Desa Glagahwaru.'],
        ['arah' => 'Timur', 'desa' => 'Desa Prawoto'],
        ['arah' => 'Selatan', 'desa' => 'Desa Wonosoco.'],
        ['arah' => 'Barat', 'desa' => 'Desa Lambangan, Kalirejo'],
    ],
];
?>

<!-- Pembungkus Utama agar tidak mepet ke pinggir -->
<div class="container-fluid px-4 py-4">

    <!-- HERO PROFIL DESA -->
    <div class="dash-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
        <div>
            <div class="dash-date-badge d-inline-block mb-3">Profil Desa</div>
            <h2>Desa <?= htmlspecialchars($desa['nama']); ?></h2>
            <p>Kecamatan <?= htmlspecialchars($desa['kecamatan']); ?>, Kabupaten
                <?= htmlspecialchars($desa['kabupaten']); ?>,
                Provinsi <?= htmlspecialchars($desa['provinsi']); ?></p>
        </div>
    </div>

    <!-- STATISTIK UTAMA -->
    <div class="row g-3 mt-1 mb-2">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="stat-icon-box" style="background:#007f3e;"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($desa['penduduk']); ?></div>
                    <div class="stat-label">Penduduk</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="stat-icon-box" style="background:#2563eb;"><i class="fas fa-building"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($desa['dusun']); ?></div>
                    <div class="stat-label">RT / RW</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="stat-icon-box" style="background:#d97706;"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($desa['luas']); ?></div>
                    <div class="stat-label">Luas Wilayah</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern">
                <div class="stat-icon-box" style="background:#dc2626;"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($desa['berdiri']); ?></div>
                    <div class="stat-label">Berdiri</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEJARAH DESA -->
    <div class="card card-modern-soft mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Sejarah Desa</h5>
            <div class="text-muted" style="line-height:1.8;">
                <?php foreach ($desa['sejarah'] as $paragraf): ?>
                <p class="mb-3"><?= htmlspecialchars($paragraf); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- VISI & MISI -->
    <div class="row g-4 mt-1">
        <div class="col-md-6">
            <div class="card card-modern-soft h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Visi</h5>
                    <p class="text-muted mb-0" style="line-height:1.7;"><?= nl2br(htmlspecialchars($desa['visi'])); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-modern-soft h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Misi</h5>
                    <ul class="text-muted mb-0 ps-3" style="line-height:1.8;">
                        <?php foreach ($desa['misi'] as $poin): ?>
                        <li><?= htmlspecialchars($poin); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- STRUKTUR PEMERINTAHAN DESA (FOTO DIPERBESAR 80px) -->
    <div class="card card-modern-soft mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Struktur Pemerintahan Desa</h5>
            <div class="row g-3">
                <?php foreach ($desa['struktur'] as $pejabat): ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="menu-tile d-flex align-items-center gap-3 p-3 rounded-3" style="cursor:default;">

                        <!-- FOTO PROFIL / AVATAR (80px x 80px) -->
                        <div class="tile-icon-avatar flex-shrink-0" style="width: 80px; height: 80px;">
                            <?php if (!empty($pejabat['foto']) && file_exists($pejabat['foto'])): ?>
                            <img src="<?= $pejabat['foto']; ?>" alt="<?= htmlspecialchars($pejabat['nama']); ?>"
                                class="rounded-circle w-100 h-100 shadow-sm"
                                style="object-fit: cover; border: 3px solid #007f3e;">
                            <?php else: ?>
                            <!-- Fallback Icon jika foto belum diisi/file tidak ditemukan -->
                            <div class="rounded-circle w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                style="background:#007f3e; font-size: 2rem; border: 3px solid #005a2c;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- JABATAN & NAMA -->
                        <div>
                            <span class="tile-count text-uppercase d-block text-muted fw-semibold"
                                style="letter-spacing:.4px; font-size:.75rem;"><?= htmlspecialchars($pejabat['jabatan']); ?></span>
                            <span class="tile-title d-block fw-bold text-dark mt-1"
                                style="font-size:1.05rem;"><?= htmlspecialchars($pejabat['nama']); ?></span>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- BATAS WILAYAH -->
    <div class="card card-modern-soft mt-4 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Batas Wilayah</h5>
            <div class="row g-3">
                <?php foreach ($desa['batas'] as $b): ?>
                <div class="col-6 col-lg-3">
                    <div class="menu-tile" style="cursor:default;">
                        <div class="tile-icon" style="background:#64748b;"><i class="fas fa-compass"></i></div>
                        <div>
                            <span class="tile-count text-uppercase"
                                style="letter-spacing:.4px;font-size:.72rem;">Sebelah
                                <?= htmlspecialchars($b['arah']); ?></span>
                            <span class="tile-title d-block"><?= htmlspecialchars($b['desa']); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div> <!-- Akhir dari pembungkus utama -->