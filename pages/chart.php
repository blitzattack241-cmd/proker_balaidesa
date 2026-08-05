<?php
function simdes_extract_tempat_lahir($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return 'Tidak Diketahui';
    }

    if (preg_match('/^([^,]+),/u', $value, $matches)) {
        return trim($matches[1]);
    }

    if (preg_match('/^([A-Za-zÀ-ÿ\s\.\-]+)(?:\s|$)/u', $value, $matches)) {
        $place = trim($matches[1]);
        if ($place !== '') {
            return $place;
        }
    }

    return 'Tidak Diketahui';
}

function simdes_extract_bulan_lahir($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return 'Tidak Diketahui';
    }

    $monthMap = [
        'januari' => 'Januari',
        'jan' => 'Januari',
        'februari' => 'Februari',
        'feb' => 'Februari',
        'maret' => 'Maret',
        'mar' => 'Maret',
        'april' => 'April',
        'apr' => 'April',
        'mei' => 'Mei',
        'may' => 'Mei',
        'juni' => 'Juni',
        'jun' => 'Juni',
        'juli' => 'Juli',
        'jul' => 'Juli',
        'agustus' => 'Agustus',
        'agu' => 'Agustus',
        'aug' => 'Agustus',
        'september' => 'September',
        'sept' => 'September',
        'sep' => 'September',
        'oktober' => 'Oktober',
        'oct' => 'Oktober',
        'november' => 'November',
        'nov' => 'November',
        'desember' => 'Desember',
        'dec' => 'Desember',
    ];

    foreach ($monthMap as $key => $label) {
        if (stripos($value, $key) !== false) {
            return $label;
        }
    }

    if (preg_match('/(?:^|[^0-9])(0?[1-9]|1[0-2])(?:[^0-9]|$)/', $value, $matches)) {
        $num = (int) $matches[1];
        $names = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $names[$num - 1] ?? 'Tidak Diketahui';
    }

    return 'Tidak Diketahui';
}

// Koneksi ke Database (Sesuaikan dengan file koneksi Anda, misal: include 'koneksi.php';)
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 1. Data Persebaran Penduduk Per RT (Line / Area Chart)
$q_rt = mysqli_query($koneksi, "SELECT rt, COUNT(*) as total FROM tb_penduduk WHERE rt IS NOT NULL AND rt != '' GROUP BY rt ORDER BY rt ASC");
$label_rt = [];
$data_rt = [];
while ($r = mysqli_fetch_assoc($q_rt)) {
    $label_rt[] = 'RT ' . str_pad($r['rt'], 3, '0', STR_PAD_LEFT);
    $data_rt[] = (int) $r['total'];
}

// 2. Data Statistik Pekerjaan (Bar Chart)
$q_kerja = mysqli_query($koneksi, "SELECT pekerjaan, COUNT(*) as total FROM tb_penduduk WHERE pekerjaan IS NOT NULL AND pekerjaan != '' GROUP BY pekerjaan ORDER BY total DESC LIMIT 10");
$label_kerja = [];
$data_kerja = [];
while ($r = mysqli_fetch_assoc($q_kerja)) {
    $label_kerja[] = strtoupper($r['pekerjaan']);
    $data_kerja[] = (int) $r['total'];
}

// 3. Data Perbandingan Jenis Kelamin (Doughnut / Pie Chart)
$q_jk = mysqli_query($koneksi, "SELECT jenis_kelamin, COUNT(*) as total FROM tb_penduduk WHERE jenis_kelamin IS NOT NULL GROUP BY jenis_kelamin");
$label_jk = [];
$data_jk = [];
while ($r = mysqli_fetch_assoc($q_jk)) {
    $jk = strtoupper($r['jenis_kelamin']);
    $label_jk[] = ($jk == 'L' || $jk == 'LAKI-LAKI') ? 'Laki-laki' : (($jk == 'P' || $jk == 'PEREMPUAN') ? 'Perempuan' : $jk);
    $data_jk[] = (int) $r['total'];
}

// 4. Data Statistik Agama (Pie / Doughnut Chart)
$q_agama = mysqli_query($koneksi, "SELECT agama, COUNT(*) as total FROM tb_penduduk WHERE agama IS NOT NULL AND agama != '' GROUP BY agama ORDER BY total DESC");
$label_agama = [];
$data_agama = [];
while ($r = mysqli_fetch_assoc($q_agama)) {
    $label_agama[] = strtoupper($r['agama']);
    $data_agama[] = (int) $r['total'];
}

// 5. Data Statistik Tempat Lahir dan Bulan Lahir
$q_lahir = mysqli_query($koneksi, "SELECT tempat_tgl_lahir FROM tb_penduduk WHERE tempat_tgl_lahir IS NOT NULL AND TRIM(tempat_tgl_lahir) != ''");
$stat_tempat_lahir = [];
$stat_bulan_lahir = [];
while ($r = mysqli_fetch_assoc($q_lahir)) {
    $tempat = simdes_extract_tempat_lahir($r['tempat_tgl_lahir']);
    $bulan = simdes_extract_bulan_lahir($r['tempat_tgl_lahir']);
    $stat_tempat_lahir[$tempat] = ($stat_tempat_lahir[$tempat] ?? 0) + 1;
    $stat_bulan_lahir[$bulan] = ($stat_bulan_lahir[$bulan] ?? 0) + 1;
}

ksort($stat_tempat_lahir);
ksort($stat_bulan_lahir);

$label_tempat_lahir = array_keys($stat_tempat_lahir);
$data_tempat_lahir = array_values($stat_tempat_lahir);
$label_bulan_lahir = array_keys($stat_bulan_lahir);
$data_bulan_lahir = array_values($stat_bulan_lahir);

if (empty($label_rt) && empty($label_kerja) && empty($label_jk) && empty($label_agama)) {
    $label_rt = ['RT 001'];
    $data_rt = [0];
    $label_kerja = ['Belum Ada Data'];
    $data_kerja = [0];
    $label_jk = ['Belum Ada Data'];
    $data_jk = [0];
    $label_agama = ['Belum Ada Data'];
    $data_agama = [0];
    $label_tempat_lahir = ['Tidak Diketahui'];
    $data_tempat_lahir = [0];
    $label_bulan_lahir = ['Tidak Diketahui'];
    $data_bulan_lahir = [0];
}
// 6. Data Statistik Kelompok Usia (berdasarkan kolom umur yang tersedia)
$q_usia = mysqli_query($koneksi, "
    SELECT
        SUM(CASE WHEN umur IS NOT NULL AND umur BETWEEN 0 AND 5 THEN 1 ELSE 0 END) AS balita,
        SUM(CASE WHEN umur IS NOT NULL AND umur BETWEEN 6 AND 17 THEN 1 ELSE 0 END) AS anak_anak,
        SUM(CASE WHEN umur IS NOT NULL AND umur BETWEEN 18 AND 59 THEN 1 ELSE 0 END) AS produktif,
        SUM(CASE WHEN umur IS NOT NULL AND umur >= 60 THEN 1 ELSE 0 END) AS lansia
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
?>

<!-- Load CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .chart-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        height: 100%;
    }

    .chart-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Header Halaman -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Statistik & Grafik Penduduk</h4>
            <p class="text-muted small mb-0">Visualisasi data kependudukan desa secara real-time</p>
        </div>
        <button onclick="window.location.reload();" class="btn btn-sm btn-outline-success">
            <i class="fas fa-sync-alt me-1"></i> Refresh Data
        </button>
    </div>

    <!-- Grid Grafik -->
    <div class="row g-4">

        <!-- 1. Chart Persebaran Penduduk Per RT -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Persebaran Penduduk Per RT</div>
                <div class="chart-container">
                    <canvas id="chartRT"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. Chart Statistik Pekerjaan / Pendidikan -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Statistik Pekerjaan Penduduk</div>
                <div class="chart-container">
                    <canvas id="chartPekerjaan"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. Chart Perbandingan Jenis Kelamin -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Perbandingan Jenis Kelamin</div>
                <div class="chart-container">
                    <canvas id="chartJK"></canvas>
                </div>
            </div>
        </div>

        <!-- 4. Chart Statistik Agama -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Statistik Agama</div>
                <div class="chart-container">
                    <canvas id="chartAgama"></canvas>
                </div>
            </div>
        </div>

        <!-- 5. Chart Tempat Lahir -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Statistik Tempat Lahir</div>
                <div class="chart-container">
                    <canvas id="chartTempatLahir"></canvas>
                </div>
            </div>
        </div>

        <!-- 6. Chart Bulan Lahir -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Statistik Bulan Lahir</div>
                <div class="chart-container">
                    <canvas id="chartBulanLahir"></canvas>
                </div>
            </div>
        </div>

        <!-- 7. Chart Kelompok Usia -->
        <div class="col-md-6">
            <div class="chart-card">
                <div class="chart-title">Statistik Kelompok Usia</div>
                <div class="chart-container">
                    <canvas id="chartUsia"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Format Data dari PHP ke JavaScript
    const labelRT = <?= json_encode($label_rt) ?>;
    const dataRT = <?= json_encode($data_rt) ?>;

    const labelKerja = <?= json_encode($label_kerja) ?>;
    const dataKerja = <?= json_encode($data_kerja) ?>;

    const labelJK = <?= json_encode($label_jk) ?>;
    const dataJK = <?= json_encode($data_jk) ?>;

    const labelAgama = <?= json_encode($label_agama) ?>;
    const dataAgama = <?= json_encode($data_agama) ?>;
    const labelTempatLahir = <?= json_encode($label_tempat_lahir) ?>;
    const dataTempatLahir = <?= json_encode($data_tempat_lahir) ?>;
    const labelBulanLahir = <?= json_encode($label_bulan_lahir) ?>;
    const dataBulanLahir = <?= json_encode($data_bulan_lahir) ?>;

    // --- 1. Render Line Chart (Persebaran Per RT) ---
    new Chart(document.getElementById('chartRT'), {
        type: 'line',
        data: {
            labels: labelRT,
            datasets: [{
                label: 'Jiwa',
                data: dataRT,
                borderColor: '#0284c7',
                backgroundColor: 'rgba(56, 189, 248, 0.3)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#0284c7',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        color: '#f1f5f9'
                    }
                }
            }
        }
    });

    // --- 2. Render Bar Chart (Statistik Pekerjaan) ---
    new Chart(document.getElementById('chartPekerjaan'), {
        type: 'bar',
        data: {
            labels: labelKerja,
            datasets: [{
                label: 'Jumlah',
                data: dataKerja,
                backgroundColor: ['#0284c7', '#16a34a', '#f59e0b', '#8b5cf6', '#ef4444', '#0f766e',
                    '#2563eb', '#dc2626', '#14b8a6', '#84cc16'
                ],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
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

    // --- 3. Render Pie/Doughnut Chart (Jenis Kelamin) ---
    new Chart(document.getElementById('chartJK'), {
        type: 'pie',
        data: {
            labels: labelJK,
            datasets: [{
                data: dataJK,
                backgroundColor: ['#0284c7', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // --- 4. Render Pie Chart (Agama) ---
    new Chart(document.getElementById('chartAgama'), {
        type: 'pie',
        data: {
            labels: labelAgama,
            datasets: [{
                data: dataAgama,
                backgroundColor: ['#0284c7', '#16a34a', '#eab308', '#a855f7', '#f97316', '#06b6d4',
                    '#6366f1', '#ec4899'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // --- 5. Render Bar Chart (Tempat Lahir) ---
    new Chart(document.getElementById('chartTempatLahir'), {
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
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
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

    // --- 6. Render Bar Chart (Bulan Lahir) ---
    new Chart(document.getElementById('chartBulanLahir'), {
        type: 'bar',
        data: {
            labels: labelBulanLahir,
            datasets: [{
                label: 'Jumlah Penduduk',
                data: dataBulanLahir,
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#8b5cf6', '#14b8a6',
                    '#fb7185', '#84cc16', '#6366f1', '#f97316', '#0ea5e9', '#64748b'
                ],
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
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Data Usia dari PHP ke JS
    const labelUsia = <?= json_encode($label_usia) ?>;
    const dataUsia = <?= json_encode($data_usia) ?>;

    // --- Render Bar/Doughnut Chart (Kelompok Usia) ---
    new Chart(document.getElementById('chartUsia'), {
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
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>