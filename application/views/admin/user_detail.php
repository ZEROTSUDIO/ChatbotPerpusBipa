<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">User Profile</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6 space-y-10">

        <!-- Profile Section -->
        <section>
            <h1 class="handwriting text-4xl font-bold mb-4">Profil Pengguna</h1>
            <div class="bg-white border-2 border-black rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="gap-6">
                        <p class="flex text-gray-500 font-semibold">
                            <span class="w-40">Nama</span>
                            <span>:<strong class="text-xl text-black font-bold"><?= htmlentities($user->nama) ?></strong></span>
                        </p>
                        <p class="flex text-gray-500 font-semibold">
                            <span class="w-40">Email</span>
                            <span>:<strong class="text-xl text-black font-bold"><?= htmlentities($user->email) ?></strong></span>
                        </p>
                        <p class="flex text-gray-500 font-semibold">
                            <span class="w-40">Tanggal Registrasi</span>
                            <span>:<strong class="text-xl text-black font-bold"><?= $user->date ?? '-' ?></strong></span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chat History Table -->
        <section>
            <h2 class="handwriting text-3xl font-bold mb-4">Riwayat Chat</h2>
            <div class="table-wrapper border-2 border-black rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="chatTable" class="w-full text-sm table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">User Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Bot Response</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($chats as $i => $chat): ?>
                                <tr>
                                    <td class="px-6 py-4"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 whitespace-normal break-words"><?= htmlentities($chat['user_message']) ?></td>
                                    <td class="px-6 py-4 whitespace-normal break-words"><?= $chat['bot_response'] ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?= $chat['timestamp'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($chats)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Tidak ada riwayat chat untuk pengguna ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginationControls" class="flex justify-center items-center gap-2 mt-4"></div>
        </section>

        <section>
            <h2 class="handwriting text-3xl font-bold mb-4">Riwayat Chat</h2>
            <div class="table-wrapper border-2 border-black rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="chatDetailTable" class="w-full text-sm table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Intent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Energy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">OOD</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($chat_detail as $i => $row): ?>
                                <tr>
                                    <td class="px-6 py-4"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4"><?= $row['intent'] ?></td>
                                    <td class="px-6 py-4"><?= number_format($row['confident_score'], 2) ?></td>
                                    <td class="px-6 py-4"><?= number_format($row['energy'], 2) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($row['ood']): ?>
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Yes</span>
                                        <?php else: ?>
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500"><?= $row['timestamp'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($chat_detail)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">Belum ada data chat_detail.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginationControls" class="flex justify-center items-center gap-2 mt-4"></div>
        </section>

        <section>
            <h2 class="handwriting text-3xl font-bold mb-4">statistik</h2>
            <div class="table-wrapper border-2 border-black rounded-lg overflow-hidden">
                <div class="bg-white border-2 border-black rounded-lg p-4">
                    <h3 class="text-xl font-bold mb-4">📊 Distribusi Intent</h3>

                    <canvas id="intentChart" class="w-full max-w-lg mx-auto"></canvas>

                </div>
            </div>
            <div class="table-wrapper border-2 border-black rounded-lg overflow-hidden" hidden>
                <div class="bg-white border-2 border-black rounded-lg p-4">
                    <h3 class="text-xl font-bold mb-4">📈 Confidence Score Over Time</h3>

                    <canvas id="confidenceChart" class="w-full max-w-4xl mx-auto"></canvas>

                </div>
            </div>
        </section>

    </main>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rowsPerPage = 5;
        const table = document.getElementById("chatTable");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr")).filter(row => !row.querySelector("td[colspan]"));
        const pagination = document.getElementById("paginationControls");

        function showPage(pageNumber) {
            const start = (pageNumber - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? "" : "none";
            });

            renderPaginationButtons(pageNumber);
        }

        function renderPaginationButtons(activePage) {
            const totalPages = Math.ceil(rows.length / rowsPerPage);
            pagination.innerHTML = "";

            for (let i = 1; i <= totalPages; i++) {
                const button = document.createElement("button");
                button.textContent = i;
                button.className = `px-3 py-1 border-2 rounded-lg text-sm ${i === activePage ? 'bg-black text-white border-black' : 'border-gray-400 text-gray-700 hover:bg-gray-100'}`;
                button.addEventListener("click", () => showPage(i));
                pagination.appendChild(button);
            }
        }

        // Only paginate if needed
        if (rows.length > rowsPerPage) {
            showPage(1);
        }
    });
</script>

<script>
    const intentLabels = <?= json_encode($intent_labels) ?>;
    const intentData = <?= json_encode($intent_values) ?>;
    const confidenceLabels = <?= json_encode($confidence_timestamps) ?>;
    const confidenceScores = <?= json_encode($confidence_scores) ?>;
    const confidenceCtx = document.getElementById('confidenceChart').getContext('2d');
    const ctx = document.getElementById('intentChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: intentLabels,
            datasets: [{
                label: 'Intent Count',
                data: intentData,
                backgroundColor: [
                    '#4F46E5', '#10B981', '#F59E0B', '#EF4444',
                    '#6366F1', '#F43F5E', '#3B82F6', '#8B5CF6',
                    '#EC4899', '#22D3EE'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: false
                }
            }
        }
    });
    new Chart(confidenceCtx, {
        type: 'line',
        data: {
            labels: confidenceLabels,
            datasets: [{
                label: 'Confidence Score',
                data: confidenceScores,
                fill: false,
                borderColor: '#4F46E5',
                tension: 0.3,
                pointBackgroundColor: '#4F46E5'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Confidence: ' + parseFloat(context.raw).toFixed(3);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1,
                    title: {
                        display: true,
                        text: 'Confidence (0–1)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Waktu'
                    }
                }
            }
        }
    });
</script>