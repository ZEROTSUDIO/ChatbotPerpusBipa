<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Users</span>
        </div>
    </header>
    <main class="p-6">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Detail Pengguna</h1>
                        <p class="text-gray-600 mt-2">Informasi lengkap dan riwayat chat pengguna</p>
                    </div>
                    <a href="<?php echo base_url('admin/users'); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Profile Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <div class="flex items-center mb-6">
                            <div class="bg-blue-500 text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mr-4">
                                <?php echo strtoupper(substr($user->nama, 0, 1)); ?>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($user->nama); ?></h2>
                                <p class="text-gray-600"><?php echo htmlspecialchars($user->email); ?></p>
                                <p class="text-sm text-gray-500">Bergabung: <?php echo date('d M Y', strtotime($user->date)); ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-comments text-blue-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Chat</p>
                                        <p class="text-2xl font-bold text-blue-600"><?php echo $user_stats['total_chats']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-percentage text-purple-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Akurasi</p>
                                        <p class="text-2xl font-bold text-purple-600">
                                            <?php
                                            $total = $ood_stats['ood'] + $ood_stats['non_ood'];
                                            $accuracy = $total > 0 ? round(($ood_stats['non_ood'] / $total) * 100, 1) : 0;
                                            echo $accuracy . '%';
                                            ?> 
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Non-OOD</p>
                                        <p class="text-2xl font-bold text-green-600"><?php echo $ood_stats['non_ood']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">OOD</p>
                                        <p class="text-2xl font-bold text-red-600"><?php echo $ood_stats['ood']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Distribusi Chat</h3>
                        <div class="relative h-64">
                            <canvas id="oodChart"></canvas>
                        </div>
                        <div class="mt-4 text-center">
                            <div class="flex justify-center space-x-4 text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span>Non-OOD (<?php echo $ood_stats['non_ood']; ?>)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span>OOD (<?php echo $ood_stats['ood']; ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chat History Table -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Riwayat Chat</h3>
                        <div class="flex items-center space-x-2">
                            <label for="intentFilter" class="text-sm text-gray-600">Filter Intent:</label>
                            <select id="intentFilter" class="border border-gray-300 rounded px-3 py-1 text-sm">
                                <option value="all">Semua Intent</option>
                                <?php foreach ($intents as $intent): ?>
                                    <option value="<?php echo htmlspecialchars($intent->intent); ?>">
                                        <?php echo htmlspecialchars($intent->intent); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="myTableId" class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Energy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="chatDetailsTable" class="bg-white divide-y divide-gray-200">
                            <?php $colors = ['blue', 'green', 'purple', 'yellow', 'indigo', 'pink', 'red', 'cyan', 'teal', 'orange']; ?>
                            <?php $i = 0; // Initialize a counter 
                            ?>
                            <?php foreach ($chat_details as $detail): ?>
                                <?php $color = $colors[$i % count($colors)]; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="<?php echo htmlspecialchars($detail->user_message); ?>">
                                            <?php echo htmlspecialchars(substr($detail->user_message, 0, 50)) . (strlen($detail->user_message) > 50 ? '...' : ''); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-<?= $color ?>-100 text-<?= $color ?>-800 rounded-full">
                                            <?php echo htmlspecialchars($detail->intent ?: 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-900">
                                                <?php echo $detail->confident_score ? round($detail->confident_score * 100, 1) . '%' : 'N/A'; ?>
                                            </div>
                                            <?php if ($detail->confident_score): ?>
                                                <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-<?php echo $detail->confident_score > 0.8 ? 'green' : ($detail->confident_score > 0.6 ? 'yellow' : 'red'); ?>-500 h-2 rounded-full"
                                                        style="width: <?php echo $detail->confident_score * 100; ?>%"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo $detail->energy ? round($detail->energy, 2) : 'N/A'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $detail->ood ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                            <?php echo $detail->ood ? 'True' : 'False'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('d/m/Y H:i', strtotime($detail->timestamp)); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button
                                            class="text-blue-600 hover:text-blue-900"
                                            data-id="<?= $detail->id ?>"
                                            data-user="<?= htmlspecialchars($detail->user_message, ENT_QUOTES, 'UTF-8') ?>"
                                            data-bot="<?= htmlspecialchars($detail->bot_response, ENT_QUOTES, 'UTF-8') ?>"
                                            onclick="handleChatButtonClick(this)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php $i++; // Increment the counter for the next iteration 
                                ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div id="myPaginationId" class="flex justify-center mt-4"></div>
                </div>
            </div>

        </div>
    </main>
</div>
<!-- Modal for Chat Detail -->
<div id="chatModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Percakapan</h3>
                <button onclick="closeChatModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="mt-2">
                <div class="mb-4">
                    <h4 class="font-medium text-gray-700 mb-2">Pesan Pengguna:</h4>
                    <div id="modalUserMessage" class="bg-blue-50 p-3 rounded-lg text-gray-800"></div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Respon Bot:</h4>
                    <div id="modalBotResponse" class="bg-gray-50 p-3 rounded-lg text-gray-800"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Initialize Chart
    document.addEventListener('DOMContentLoaded', function() {
        paginateTable('myTableId', 10, 'myPaginationId');
    });
    const ctx = document.getElementById('oodChart').getContext('2d');
    const oodChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Non-OOD', 'OOD'],
            datasets: [{
                data: [<?php echo $ood_stats['non_ood']; ?>, <?php echo $ood_stats['ood']; ?>],
                backgroundColor: ['#10B981', '#EF4444'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    // Filter functionality
    document.getElementById('intentFilter').addEventListener('change', function() {
        const intent = this.value;
        const userId = <?php echo $user_id; ?>;

        // Show loading
        document.getElementById('chatDetailsTable').innerHTML = '<tr><td colspan="7" class="text-center py-4">Loading...</td></tr>';

        // AJAX request
        fetch('<?php echo base_url("admin/get_chat_details_by_intent"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&intent=${intent}`
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(detail => {
                    const confidenceScore = detail.confident_score ? Math.round(detail.confident_score * 100 * 10) / 10 : 'N/A';
                    const confidenceColor = detail.confident_score > 0.8 ? 'green' : (detail.confident_score > 0.6 ? 'yellow' : 'red');
                    const userMessage = detail.user_message.length > 50 ? detail.user_message.substring(0, 50) + '...' : detail.user_message;
                    const statusClass = detail.ood == 1 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                    const statusText = detail.ood == 1 ? 'True' : 'False';
                    const date = new Date(detail.timestamp).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="${escapeHtml(detail.user_message)}">
                                    ${escapeHtml(userMessage)}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    ${escapeHtml(detail.intent || 'N/A')}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-900">
                                        ${confidenceScore}${confidenceScore !== 'N/A' ? '%' : ''}
                                    </div>
                                    ${detail.confident_score ? `
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-${confidenceColor}-500 h-2 rounded-full" style="width: ${detail.confident_score * 100}%"></div>
                                        </div>
                                    ` : ''}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${detail.energy ? Math.round(detail.energy * 100) / 100 : 'N/A'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                    ${statusText}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${date}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button
                                    class="text-blue-600 hover:text-blue-900"
                                    data-id="${detail.id}"
                                    data-user="${escapeHtml(detail.user_message)}"
                                    data-bot="${escapeHtml(detail.bot_response)}"
                                    onclick="handleChatButtonClick(this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                if (html === '') {
                    html = '<tr><td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data ditemukan</td></tr>';
                }

                document.getElementById('chatDetailsTable').innerHTML = html;
            })
            .catch(async (error) => {
                const text = await error.text?.();
                console.error('Fetch error:', error, text);
                document.getElementById('chatDetailsTable').innerHTML = '<tr><td colspan="7" class="text-center py-4 text-red-500">Error loading data</td></tr>';
            });

    });

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Modal functions
    function handleChatButtonClick(button) {
        const id = button.getAttribute('data-id');
        const userMessage = button.getAttribute('data-user');
        const botResponse = button.getAttribute('data-bot');

        showChatDetail(id, userMessage, botResponse);
    }

    function showChatDetail(id, userMessage, botResponse) {
        document.getElementById('modalUserMessage').textContent = userMessage;
        document.getElementById('modalBotResponse').innerHTML = botResponse;
        document.getElementById('chatModal').classList.remove('hidden');
    }

    function closeChatModal() {
        document.getElementById('chatModal').classList.add('hidden');
    }

    // Optional: Tutup modal jika klik di luar kotak konten
    document.getElementById('chatModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeChatModal();
        }
    });

    function paginateTable(tableId, rowsPerPage, paginationId) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const pagination = document.getElementById(paginationId);
        let currentPage = 1;

        function displayRows(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
        }

        function updatePagination() {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = 'px-3 py-1 mx-1 border rounded hover:bg-gray-300';
                if (i === currentPage) btn.classList.add('bg-blue-500', 'text-white');

                btn.addEventListener('click', () => {
                    currentPage = i;
                    displayRows(currentPage);
                    updatePagination();
                });

                pagination.appendChild(btn);
            }
        }

        // Initialize
        displayRows(currentPage);
        updatePagination();
    }
</script>