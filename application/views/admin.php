<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - Chatbot BIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .text-truncate-custom {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container-fluid p-4">
        <!-- Header Info User -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-primary mb-3">Detail User #<?= $user->id ?></h2>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($user->nama) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tanggal Daftar:</strong> <?= date('d/m/Y', strtotime($user->date)) ?></p>
                                <p><strong>Status:</strong> <span class="badge bg-success">Aktif</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Period -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-2">
                        <form method="GET" class="d-flex align-items-center">
                            <label class="me-2">Filter Periode:</label>
                            <select name="period" class="form-select form-select-sm me-2" style="width: auto;" onchange="this.form.submit()">
                                <option value="7" <?= $period == 7 ? 'selected' : '' ?>>7 Hari Terakhir</option>
                                <option value="30" <?= $period == 30 ? 'selected' : '' ?>>30 Hari Terakhir</option>
                                <option value="90" <?= $period == 90 ? 'selected' : '' ?>>3 Bulan Terakhir</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card text-white">
                    <div class="card-body text-center">
                        <h3><?= number_format($stats['total_chats']) ?></h3>
                        <p class="mb-0">Total Chat</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['avg_confidence'] ?>%</h3>
                        <p class="mb-0">Rata-rata Confidence</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?= number_format($stats['ood_count']) ?></h3>
                        <p class="mb-0">Out of Domain</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3><?= number_format($stats['today_chats']) ?></h3>
                        <p class="mb-0">Chat Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Aktivitas Chat (<?= $period ?> Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="chatActivityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Distribusi Intent</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="intentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confidence Trend -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Trend Confidence Score (<?= $period ?> Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="confidenceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Chat History -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Riwayat Chat (<?= $period ?> Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Pesan User</th>
                                        <th>Respons Bot</th>
                                        <th>Intent</th>
                                        <th>Confidence</th>
                                        <th>OOD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_chats)): ?>
                                        <?php foreach ($recent_chats as $chat): ?>
                                            <tr>
                                                <td><?= date('d/m/Y H:i', strtotime($chat->timestamp)) ?></td>
                                                <td class="text-truncate-custom" title="<?= htmlspecialchars($chat->user_message) ?>">
                                                    <?= htmlspecialchars($chat->user_message) ?>
                                                </td>
                                                <td class="text-truncate-custom" title="<?= htmlspecialchars($chat->bot_response) ?>">
                                                    <?= htmlspecialchars($chat->bot_response) ?>
                                                </td>
                                                <td>
                                                    <?php if ($chat->intent): ?>
                                                        <span class="badge bg-primary"><?= htmlspecialchars($chat->intent) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($chat->confident_score !== null): ?>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: <?= ($chat->confident_score * 100) ?>%">
                                                                <?= number_format($chat->confident_score * 100, 1) ?>%
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $chat->ood ? 'bg-warning' : 'bg-success' ?>">
                                                        <?= $chat->ood ? 'Ya' : 'Tidak' ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data chat</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?= $pagination ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data dari PHP
        const chatActivityData = <?= json_encode($chat_activity) ?>;
        const intentData = <?= json_encode($intent_distribution) ?>;
        const confidenceTrendData = <?= json_encode($confidence_trend) ?>;

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        function initializeCharts() {
            // Chat Activity Chart
            const activityCtx = document.getElementById('chatActivityChart').getContext('2d');
            new Chart(activityCtx, {
                type: 'line',
                data: {
                    labels: chatActivityData.map(item => item.date),
                    datasets: [{
                        label: 'Jumlah Chat',
                        data: chatActivityData.map(item => parseInt(item.count)),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Intent Distribution Chart
            if (intentData.length > 0) {
                const intentCtx = document.getElementById('intentChart').getContext('2d');
                new Chart(intentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: intentData.map(item => item.intent),
                        datasets: [{
                            data: intentData.map(item => parseInt(item.count)),
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
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

            // Confidence Trend Chart
            if (confidenceTrendData.length > 0) {
                const confidenceCtx = document.getElementById('confidenceChart').getContext('2d');
                new Chart(confidenceCtx, {
                    type: 'bar',
                    data: {
                        labels: confidenceTrendData.map(item => item.date),
                        datasets: [{
                            label: 'Rata-rata Confidence Score (%)',
                            data: confidenceTrendData.map(item => parseFloat(item.avg_confidence).toFixed(2)),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>