<?php
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

if (empty($label_rt) && empty($label_kerja) && empty($label_jk) && empty($label_agama)) {
    $label_rt = ['RT 001'];
    $data_rt = [0];
    $label_kerja = ['Belum Ada Data'];
    $data_kerja = [0];
    $label_jk = ['Belum Ada Data'];
    $data_jk = [0];
    $label_agama = ['Belum Ada Data'];
    $data_agama = [0];
}
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
</script>