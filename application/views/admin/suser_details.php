?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - BIPA Chatbot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h2 class="card-title mb-0">Detail Statistik User #<?= $user_id ?></h2>
                        <p class="card-text"><?= $user->nama ?> | <?= $user->email ?></p>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-light btn-sm">
                            ← Kembali ke Daftar User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= number_format($user_stats['total_chats']) ?></h3>
                        <p class="card-text">Total Chat</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= $user_stats['avg_confidence'] ?>%</h3>
                        <p class="card-text">Avg Confidence</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= $user_stats['unique_intents'] ?></h3>
                        <p class="card-text">Intent Berbeda</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="card-title"><?= $user_stats['ood_count'] ?></h3>
                        <p class="card-text">Out of Domain</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row mb-4">
            <!-- Chart 1: Daily Activity -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Aktivitas Chat Harian (30 Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyActivityChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Top Intent -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Top 5 Intent</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="topIntentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <!-- Chart 3: Confidence Distribution -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Distribusi Confidence Score</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="confidenceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 4: Energy Distribution -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Distribusi Energy Level</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="energyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 3 -->
        <div class="row mb-4">
            <!-- Chart 5: OOD Analysis -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Out of Domain Analysis</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="oodChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 6: Hourly Pattern -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Pola Chat per Jam</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="hourlyPatternChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data from PHP
        const dailyActivityData = <?= json_encode($daily_activity) ?>;
        const topIntentsData = <?= json_encode($top_intents) ?>;
        const confidenceData = <?= json_encode($confidence_distribution) ?>;
        const energyData = <?= json_encode($energy_distribution) ?>;
        const oodData = <?= json_encode($ood_analysis) ?>;
        const hourlyData = <?= json_encode($hourly_pattern) ?>;

        // Chart 1: Daily Activity
        const dailyCtx = document.getElementById('dailyActivityChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyActivityData.map(item => item.date),
                datasets: [{
                    label: 'Jumlah Chat',
                    data: dailyActivityData.map(item => item.count),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Chart 2: Top Intent
        const intentCtx = document.getElementById('topIntentChart').getContext('2d');
        new Chart(intentCtx, {
            type: 'doughnut',
            data: {
                labels: topIntentsData.map(item => item.intent),
                datasets: [{
                    data: topIntentsData.map(item => item.count),
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Chart 3: Confidence Distribution
        const confidenceCtx = document.getElementById('confidenceChart').getContext('2d');
        new Chart(confidenceCtx, {
            type: 'bar',
            data: {
                labels: confidenceData.map(item => item.range_label),
                datasets: [{
                    label: 'Jumlah Chat',
                    data: confidenceData.map(item => item.count),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart 4: Energy Distribution
        const energyCtx = document.getElementById('energyChart').getContext('2d');
        new Chart(energyCtx, {
            type: 'bar',
            data: {
                labels: energyData.map(item => item.energy_level),
                datasets: [{
                    label: 'Jumlah Chat',
                    data: energyData.map(item => item.count),
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart 5: OOD Analysis
        const oodCtx = document.getElementById('oodChart').getContext('2d');
        new Chart(oodCtx, {
            type: 'pie',
            data: {
                labels: oodData.map(item => item.ood == 1 ? 'Out of Domain' : 'In Domain'),
                datasets: [{
                    data: oodData.map(item => item.count),
                    backgroundColor: ['#10B981', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Chart 6: Hourly Pattern
        const hourlyCtx = document.getElementById('hourlyPatternChart').getContext('2d');
        // Create array for all 24 hours with 0 count for missing hours
        const hourlyChartData = [];
        for (let i = 0; i < 24; i++) {
            const hourData = hourlyData.find(item => parseInt(item.hour) === i);
            hourlyChartData.push(hourData ? hourData.count : 0);
        }

        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0')),
                datasets: [{
                    label: 'Jumlah Chat',
                    data: hourlyChartData,
                    backgroundColor: 'rgba(139, 92, 246, 0.8)',
                    borderColor: 'rgb(139, 92, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>