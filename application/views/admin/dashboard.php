<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Overview</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <h1 class="handwriting text-4xl font-bold mb-8">Dashboard Overview</h1>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Total Users</p>
                        <p class="text-white text-3xl font-bold handwriting"><?php echo $total_users['total_users']; ?></p>
                    </div>
                    <i class="fas fa-users text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4" hidden>
                    <span class="text-green-200 text-sm">↑ 12% from last month</span>
                </div>
            </div>

            <div class="card green p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Total Chats</p>
                        <p class="text-white text-3xl font-bold handwriting"><?php echo $the_stats['total_chats']; ?></p>
                    </div>
                    <i class="fas fa-comments text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4" hidden>
                    <span class="text-green-200 text-sm">↑ 8% from yesterday</span>
                </div>
            </div>

            <div class="card orange p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Presisi</p>
                        <p class="text-white text-3xl font-bold handwriting">
                            <?php
                            $total = $ood_stats['ood'] + $ood_stats['non_ood'];
                            $accuracy = $total > 0 ? round(($ood_stats['non_ood'] / $total) * 100, 1) : 0;
                            echo $accuracy . '%';
                            ?>
                        </p>
                    </div>
                    <i class="fas fa-envelope text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4" hidden>
                    <span class="text-red-200 text-sm">↓ 3% from yesterday</span>
                </div>
            </div>

            <div class="card blue p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Bot Accuracy</p>
                        <p class="text-white text-3xl font-bold handwriting">
                            <?php
                            $average = 0;
                            $totalSum = 0;
                            $totalCount = 0;
                            foreach ($chat_details as $detail) {
                                if (!empty($detail->confident_score)) {
                                    $score = floatval($detail->confident_score);
                                    $totalSum += $score;
                                    $totalCount++;
                                }
                            }
                            $average = $totalCount > 0 ? $totalSum / $totalCount : 0;
							$average = $average*100;
                            echo round($average, 1) . "%";
                            ?>
                        </p>
                    </div>
                    <i class="fas fa-robot text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4" hidden>
                    <span class="text-green-200 text-sm">↑ 2% from last week</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="table-wrapper">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Recent Activity</h3>
                    <!-- Add this near your intent filter -->
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
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
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="<?php echo htmlspecialchars($detail->nama); ?>">
                                        <?php echo $detail->nama; ?>
                                    </div>
                                </td>
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
    </main>
</div>
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
    let paginationInstance = null;

    document.addEventListener('DOMContentLoaded', function() {
        paginationInstance = new TablePagination('myTableId', 10, 'myPaginationId');
    });

    document.getElementById('intentFilter').addEventListener('change', function() {
        const intent = this.value;

        // Show loading
        document.getElementById('chatDetailsTable').innerHTML = '<tr><td colspan="8" class="text-center py-4">Loading...</td></tr>';

        // Clear pagination while loading
        document.getElementById('myPaginationId').innerHTML = '';

        // AJAX request
        fetch('<?php echo base_url("admin/get_chat_details_by_intent2"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `intent=${intent}`
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(detail => {
                    const nama = detail.nama;
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
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="${escapeHtml(nama)}">
                                    ${escapeHtml(nama)}
                                </div>
                            </td>
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
                    html = '<tr><td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data ditemukan</td></tr>';
                }

                // Update table content
                document.getElementById('chatDetailsTable').innerHTML = html;

                // Reinitialize pagination with new data
                paginationInstance = new TablePagination('myTableId', 10, 'myPaginationId');
            })
            .catch(async (error) => {
                const text = await error.text?.();
                console.error('Fetch error:', error, text);
                document.getElementById('chatDetailsTable').innerHTML = '<tr><td colspan="8" class="text-center py-4 text-red-500">Error loading data</td></tr>';
                // Clear pagination on error
                document.getElementById('myPaginationId').innerHTML = '';
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

    // Improved pagination class
    class TablePagination {
        constructor(tableId, rowsPerPage, paginationId) {
            this.table = document.getElementById(tableId);
            this.tbody = this.table.querySelector('tbody');
            this.pagination = document.getElementById(paginationId);
            this.rowsPerPage = rowsPerPage;
            this.currentPage = 1;

            this.init();
        }

        init() {
            this.rows = Array.from(this.tbody.querySelectorAll('tr'));
            this.totalPages = Math.ceil(this.rows.length / this.rowsPerPage);

            // Only show pagination if there are more rows than rowsPerPage
            if (this.rows.length > this.rowsPerPage) {
                this.displayRows(this.currentPage);
                this.updatePagination();
            } else {
                // Clear pagination if not needed
                this.pagination.innerHTML = '';
                // Show all rows
                this.rows.forEach(row => row.style.display = '');
            }
        }

        displayRows(page) {
            const start = (page - 1) * this.rowsPerPage;
            const end = start + this.rowsPerPage;
            this.rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
        }

        updatePagination() {
            this.pagination.innerHTML = '';

            // Previous button
            if (this.currentPage > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.textContent = '← Previous';
                prevBtn.className = 'px-3 py-1 mx-1 border rounded hover:bg-gray-300';
                prevBtn.addEventListener('click', () => {
                    this.currentPage--;
                    this.displayRows(this.currentPage);
                    this.updatePagination();
                });
                this.pagination.appendChild(prevBtn);
            }

            // Page numbers
            for (let i = 1; i <= this.totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = 'px-3 py-1 mx-1 border rounded hover:bg-gray-300';
                if (i === this.currentPage) {
                    btn.classList.add('bg-blue-500', 'text-white');
                    btn.classList.remove('hover:bg-gray-300');
                }

                btn.addEventListener('click', () => {
                    this.currentPage = i;
                    this.displayRows(this.currentPage);
                    this.updatePagination();
                });

                this.pagination.appendChild(btn);
            }

            // Next button
            if (this.currentPage < this.totalPages) {
                const nextBtn = document.createElement('button');
                nextBtn.textContent = 'Next →';
                nextBtn.className = 'px-3 py-1 mx-1 border rounded hover:bg-gray-300';
                nextBtn.addEventListener('click', () => {
                    this.currentPage++;
                    this.displayRows(this.currentPage);
                    this.updatePagination();
                });
                this.pagination.appendChild(nextBtn);
            }

            // Show page info
            const pageInfo = document.createElement('span');
            pageInfo.textContent = ` Page ${this.currentPage} of ${this.totalPages} `;
            pageInfo.className = 'px-3 py-1 text-sm text-gray-600';
            this.pagination.appendChild(pageInfo);
        }
    }

    /* Legacy support - keep the old function name for compatibility
    function paginateTable(tableId, rowsPerPage, paginationId) {
        return new TablePagination(tableId, rowsPerPage, paginationId);
    }*/
</script>