<?php
// --- FUNGSI HELPER PARSER TEMPAT & BULAN LAHIR ---
function simdes_extract_tempat_lahir($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return 'Tidak Diketahui';
    }

    // 1. Format dengan koma: "Kudus, 12 Mei 2000" -> "Kudus"
    if (preg_match('/^([^,]+),/u', $value, $matches)) {
        $place = trim($matches[1]);
        if ($place !== '' && !is_numeric($place)) {
            return ucwords(strtolower($place));
        }
    }

    // 2. Format tanpa koma: "Kudus 12-05-2000" atau "Kudus 2000-05-12" -> "Kudus"
    // Menghapus pola tanggal/angka di bagian akhir string
    $cleanPlace = preg_replace('/\s+(\d{1,4}[-\/\s]|\d{1,2}\s+[A-Za-z]+).*$/u', '', $value);
    $cleanPlace = trim($cleanPlace);

    // Pastikan hasil ekstraksi bukan murni angka atau format tanggal
    if ($cleanPlace !== '' && !preg_match('/^\d+$/', $cleanPlace)) {
        return ucwords(strtolower($cleanPlace));
    }

    return 'Tidak Diketahui';
}

function simdes_extract_bulan_lahir($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return 'Tidak Diketahui';
    }

    $names = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // 1. Cek format YYYY-MM-DD atau YYYY/MM/DD
    if (preg_match('/\b(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})\b/', $value, $m)) {
        $bulanNum = (int) $m[2];
        if ($bulanNum >= 1 && $bulanNum <= 12) {
            return $names[$bulanNum - 1];
        }
    }

    // 2. Cek format DD-MM-YYYY atau DD/MM/YYYY
    if (preg_match('/\b(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})\b/', $value, $m)) {
        $bulanNum = (int) $m[2];
        if ($bulanNum >= 1 && $bulanNum <= 12) {
            return $names[$bulanNum - 1];
        }
    }

    // 3. Cek format nama bulan teks (Januari, Feb, dsb)
    $monthMap = [
        'jan' => 'Januari',
        'feb' => 'Februari',
        'mar' => 'Maret',
        'apr' => 'April',
        'mei' => 'Mei',
        'may' => 'Mei',
        'jun' => 'Juni',
        'jul' => 'Juli',
        'agu' => 'Agustus',
        'aug' => 'Agustus',
        'sep' => 'September',
        'okt' => 'Oktober',
        'oct' => 'Oktober',
        'nov' => 'November',
        'des' => 'Desember',
        'dec' => 'Desember'
    ];

    foreach ($monthMap as $key => $label) {
        if (stripos($value, $key) !== false) {
            return $label;
        }
    }

    return 'Tidak Diketahui';
}

// --- KONEKSI DATABASE ---
require_once __DIR__ . '/../koneksi.php';

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 1. Data Persebaran Penduduk Per RT & RW (DIPERBARUI)
$q_rt = mysqli_query($koneksi, "
    SELECT rt, rw, COUNT(*) as total 
    FROM tb_penduduk 
    WHERE rt IS NOT NULL AND rt != '' AND rt != '0' 
      AND rw IS NOT NULL AND rw != '' AND rw != '0'
    GROUP BY CAST(rw AS UNSIGNED), CAST(rt AS UNSIGNED) 
    ORDER BY CAST(rw AS UNSIGNED) ASC, CAST(rt AS UNSIGNED) ASC
");

$label_rt = [];
$data_rt = [];
while ($r = mysqli_fetch_assoc($q_rt)) {
    // Menghasilkan format: "RT 001 / RW 001"
    $rt_formatted = 'RT ' . str_pad($r['rt'], 3, '0', STR_PAD_LEFT);
    $rw_formatted = 'RW ' . str_pad($r['rw'], 3, '0', STR_PAD_LEFT);

    $label_rt[] = $rt_formatted . ' / ' . $rw_formatted;
    $data_rt[] = (int) $r['total'];
}

// 2. Data Statistik Pekerjaan (Top 10)
$q_kerja = mysqli_query($koneksi, "SELECT pekerjaan, COUNT(*) as total FROM tb_penduduk WHERE pekerjaan IS NOT NULL AND pekerjaan != '' GROUP BY pekerjaan ORDER BY total DESC LIMIT 10");
$label_kerja = [];
$data_kerja = [];
while ($r = mysqli_fetch_assoc($q_kerja)) {
    $label_kerja[] = strtoupper($r['pekerjaan']);
    $data_kerja[] = (int) $r['total'];
}

// 3. Data Perbandingan Jenis Kelamin
$q_jk = mysqli_query($koneksi, "SELECT jenis_kelamin, COUNT(*) as total FROM tb_penduduk WHERE jenis_kelamin IS NOT NULL GROUP BY jenis_kelamin");
$label_jk = [];
$data_jk = [];
while ($r = mysqli_fetch_assoc($q_jk)) {
    $jk = strtoupper(trim($r['jenis_kelamin']));
    $label_jk[] = ($jk == 'L' || $jk == 'LAKI-LAKI') ? 'Laki-laki' : (($jk == 'P' || $jk == 'PEREMPUAN') ? 'Perempuan' : $jk);
    $data_jk[] = (int) $r['total'];
}

// 4. Data Statistik Agama
$q_agama = mysqli_query($koneksi, "SELECT agama, COUNT(*) as total FROM tb_penduduk WHERE agama IS NOT NULL AND agama != '' GROUP BY agama ORDER BY total DESC");
$label_agama = [];
$data_agama = [];
while ($r = mysqli_fetch_assoc($q_agama)) {
    $label_agama[] = strtoupper($r['agama']);
    $data_agama[] = (int) $r['total'];
}

// 5. Data Tempat Lahir dan Bulan Lahir
$q_lahir = mysqli_query($koneksi, "SELECT * FROM tb_penduduk");

$stat_tempat_lahir = [];
$stat_bulan_lahir = [
    'Januari' => 0,
    'Februari' => 0,
    'Maret' => 0,
    'April' => 0,
    'Mei' => 0,
    'Juni' => 0,
    'Juli' => 0,
    'Agustus' => 0,
    'September' => 0,
    'Oktober' => 0,
    'November' => 0,
    'Desember' => 0,
    'Tidak Diketahui' => 0
];

while ($r = mysqli_fetch_assoc($q_lahir)) {
    $str_tempat = !empty($r['tempat_lahir']) ? $r['tempat_lahir'] : ($r['tempat_tgl_lahir'] ?? '');
    $str_bulan = !empty($r['tgl_lahir']) ? $r['tgl_lahir'] : ($r['tempat_tgl_lahir'] ?? '');

    if (trim($str_tempat) !== '') {
        $tempat = simdes_extract_tempat_lahir($str_tempat);
        $stat_tempat_lahir[$tempat] = ($stat_tempat_lahir[$tempat] ?? 0) + 1;
    }

    if (trim($str_bulan) !== '') {
        $bulan = simdes_extract_bulan_lahir($str_bulan);
        $stat_bulan_lahir[$bulan] = ($stat_bulan_lahir[$bulan] ?? 0) + 1;
    }
}

ksort($stat_tempat_lahir);

if (isset($stat_bulan_lahir['Tidak Diketahui']) && $stat_bulan_lahir['Tidak Diketahui'] === 0) {
    unset($stat_bulan_lahir['Tidak Diketahui']);
}

$label_tempat_lahir = array_keys($stat_tempat_lahir);
$data_tempat_lahir = array_values($stat_tempat_lahir);

$label_bulan_lahir = array_keys($stat_bulan_lahir);
$data_bulan_lahir = array_values($stat_bulan_lahir);

// 6. Data Kelompok Usia
$q_usia = mysqli_query($koneksi, "
    SELECT
        SUM(CASE 
            WHEN tgl_lahir IS NOT NULL AND tgl_lahir != '0000-00-00' THEN TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 0 AND 5
            WHEN umur IS NOT NULL THEN umur BETWEEN 0 AND 5 
            ELSE 0 
        END) AS balita,
        SUM(CASE 
            WHEN tgl_lahir IS NOT NULL AND tgl_lahir != '0000-00-00' THEN TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 6 AND 17
            WHEN umur IS NOT NULL THEN umur BETWEEN 6 AND 17 
            ELSE 0 
        END) AS anak_anak,
        SUM(CASE 
            WHEN tgl_lahir IS NOT NULL AND tgl_lahir != '0000-00-00' THEN TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN 18 AND 59
            WHEN umur IS NOT NULL THEN umur BETWEEN 18 AND 59 
            ELSE 0 
        END) AS produktif,
        SUM(CASE 
            WHEN tgl_lahir IS NOT NULL AND tgl_lahir != '0000-00-00' THEN TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) >= 60
            WHEN umur IS NOT NULL THEN umur >= 60 
            ELSE 0 
        END) AS lansia
    FROM tb_penduduk
");

$d_usia = mysqli_fetch_assoc($q_usia);

$label_usia = ['Balita (0-5 thn)', 'Anak-anak (6-17 thn)', 'Usia Produktif (18-59 thn)', 'Lansia (60+ thn)'];
$data_usia = [
    (int) ($d_usia['balita'] ?? 0),
    (int) ($d_usia['anak_anak'] ?? 0),
    (int) ($d_usia['produktif'] ?? 0),
    (int) ($d_usia['lansia'] ?? 0)
];

// Fallback tampilan awal jika database belum ada data
if (empty($label_rt)) {
    $label_rt = ['RT 001 / RW 001'];
    $data_rt = [0];
}
if (empty($label_kerja)) {
    $label_kerja = ['Belum Ada Data'];
    $data_kerja = [0];
}
if (empty($label_jk)) {
    $label_jk = ['Belum Ada Data'];
    $data_jk = [0];
}
if (empty($label_agama)) {
    $label_agama = ['Belum Ada Data'];
    $data_agama = [0];
}
if (empty($label_tempat_lahir)) {
    $label_tempat_lahir = ['Tidak Diketahui'];
    $data_tempat_lahir = [0];
}
?>

<!-- CDN Chart.js & FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .chart-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .chart-title {
        font-weight: 700;
        font-size: 1rem;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Statistik & Grafik Penduduk</h4>
            <p class="text-muted small mb-0">Visualisasi data kependudukan desa secara real-time</p>
        </div>
        <button onclick="window.location.reload();" class="btn btn-sm btn-outline-success">
            <i class="fas fa-sync-alt me-1"></i> Refresh Data
        </button>
    </div>

    <div class="row g-4">
        <!-- 1. RT Chart (Persebaran Per RT & RW) -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-map-marker-alt text-primary"></i> Persebaran Penduduk Per RT & RW
                </div>
                <div class="chart-container">
                    <canvas id="chartRT"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. Pekerjaan Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-briefcase text-success"></i> Top 10 Pekerjaan Penduduk</div>
                <div class="chart-container">
                    <canvas id="chartPekerjaan"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. Jenis Kelamin Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-venus-mars text-danger"></i> Perbandingan Jenis Kelamin</div>
                <div class="chart-container">
                    <canvas id="chartJK"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Agama Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-pray text-warning"></i> Statistik Agama</div>
                <div class="chart-container">
                    <canvas id="chartAgama"></canvas>
                </div>
            </div>
        </div>

        <!-- 5. Kelompok Usia Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-users text-info"></i> Statistik Kelompok Usia</div>
                <div class="chart-container">
                    <canvas id="chartUsia"></canvas>
                </div>
            </div>
        </div>

        <!-- 6. Bulan Lahir Chart -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-calendar-alt text-secondary"></i> Statistik Bulan Lahir</div>
                <div class="chart-container">
                    <canvas id="chartBulanLahir"></canvas>
                </div>
            </div>
        </div>

        <!-- 7. Tempat Lahir Chart -->
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-title"><i class="fas fa-city text-dark"></i> Statistik Tempat Lahir</div>
                <div class="chart-container" style="height: 320px;">
                    <canvas id="chartTempatLahir"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const yAxisConfig = {
        beginAtZero: true,
        ticks: {
            precision: 0
        },
        grid: {
            color: '#f1f5f9'
        }
    };

    const labelRT = <?= json_encode($label_rt) ?>;
    const dataRT = <?= json_encode($data_rt) ?>;

    const labelKerja = <?= json_encode($label_kerja) ?>;
    const dataKerja = <?= json_encode($data_kerja) ?>;

    const labelJK = <?= json_encode($label_jk) ?>;
    const dataJK = <?= json_encode($data_jk) ?>;
    const colorJK = labelJK.map(label => {
        const key = String(label).toLowerCase();
        if (key.includes('laki')) return '#0284c7';
        if (key.includes('perempuan')) return '#ec4899';
        return '#94a3b8';
    });

    const labelAgama = <?= json_encode($label_agama) ?>;
    const dataAgama = <?= json_encode($data_agama) ?>;

    const labelUsia = <?= json_encode($label_usia) ?>;
    const dataUsia = <?= json_encode($data_usia) ?>;

    const labelBulanLahir = <?= json_encode($label_bulan_lahir) ?>;
    const dataBulanLahir = <?= json_encode($data_bulan_lahir) ?>;

    const labelTempatLahir = <?= json_encode($label_tempat_lahir) ?>;
    const dataTempatLahir = <?= json_encode($data_tempat_lahir) ?>;

    // 1. Chart RT (Diubah Rotasi Label agar pas saat dibaca)
    var el_chartRT = document.getElementById('chartRT');
    if (el_chartRT) {
        new Chart(el_chartRT, {
            type: 'line',
            data: {
                labels: labelRT,
                datasets: [{
                    label: 'Jumlah Jiwa',
                    data: dataRT,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(56, 189, 248, 0.15)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 5,
                    pointBackgroundColor: '#0284c7',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: yAxisConfig,
                    x: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // 2. Chart Pekerjaan
    var el_chartPekerjaan = document.getElementById('chartPekerjaan');
    if (el_chartPekerjaan) {
        new Chart(el_chartPekerjaan, {
            type: 'bar',
            data: {
                labels: labelKerja,
                datasets: [{
                    label: 'Jumlah',
                    data: dataKerja,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: yAxisConfig,
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // 3. Chart Jenis Kelamin
    var el_chartJK = document.getElementById('chartJK');
    if (el_chartJK) {
        new Chart(el_chartJK, {
            type: 'doughnut',
            data: {
                labels: labelJK,
                datasets: [{
                    data: dataJK,
                    backgroundColor: colorJK,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // 4. Chart Agama
    var el_chartAgama = document.getElementById('chartAgama');
    if (el_chartAgama) {
        new Chart(el_chartAgama, {
            type: 'pie',
            data: {
                labels: labelAgama,
                datasets: [{
                    data: dataAgama,
                    backgroundColor: ['#0284c7', '#16a34a', '#eab308', '#a855f7', '#f97316', '#06b6d4',
                        '#6366f1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // 5. Chart Kelompok Usia
    var el_chartUsia = document.getElementById('chartUsia');
    if (el_chartUsia) {
        new Chart(el_chartUsia, {
            type: 'bar',
            data: {
                labels: labelUsia,
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: dataUsia,
                    backgroundColor: ['#38bdf8', '#4ade80', '#facc15', '#f87171'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: yAxisConfig,
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // 6. Chart Bulan Lahir
    var el_chartBulanLahir = document.getElementById('chartBulanLahir');
    if (el_chartBulanLahir) {
        new Chart(el_chartBulanLahir, {
            type: 'bar',
            data: {
                labels: labelBulanLahir,
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: dataBulanLahir,
                    backgroundColor: '#6366f1',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: yAxisConfig,
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }

    // 7. Chart Tempat Lahir
    var el_chartTempatLahir = document.getElementById('chartTempatLahir');
    if (el_chartTempatLahir) {
        new Chart(el_chartTempatLahir, {
            type: 'bar',
            data: {
                labels: labelTempatLahir,
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: dataTempatLahir,
                    backgroundColor: '#0f766e',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: yAxisConfig,
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
</script>