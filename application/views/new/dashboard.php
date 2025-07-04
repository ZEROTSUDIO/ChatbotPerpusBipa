<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Intent Analytics Dashboard</h1>
        <p class="text-gray-600">Monitor and analyze chatbot intent performance</p>
    </div>

    <!-- Performance Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-comments text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Conversations</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalConversations">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 mr-4">
                    <i class="fas fa-bullseye text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Overall Accuracy</p>
                    <p class="text-2xl font-bold text-gray-800" id="overallAccuracy">-%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3 mr-4">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Avg Confidence</p>
                    <p class="text-2xl font-bold text-gray-800" id="avgConfidence">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-red-100 rounded-full p-3 mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">OOD Rate</p>
                    <p class="text-2xl font-bold text-gray-800" id="oodRate">-%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6">
                <button class="tab-button py-4 border-b-2 border-blue-500 text-blue-600 font-medium" data-tab="performance">
                    Intent Performance
                </button>
                <button class="tab-button py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="chat-details">
                    Chat Details
                </button>
                <button class="tab-button py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-tab="prediction-analysis">
                    Prediction Analysis
                </button>
            </nav>
        </div>

        <!-- Intent Performance Tab -->
        <div id="performance-tab" class="tab-content p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Intent Performance Overview</h2>
                <button onclick="exportPerformanceData()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Occurrences</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Confidence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Energy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OOD %</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                        </tr>
                    </thead>
                    <tbody id="performanceTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chat Details Tab -->
        <div id="chat-details-tab" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Chat Details</h2>
                <div class="flex space-x-4">
                    <select id="intentFilter" class="border border-gray-300 rounded-md px-3 py-2">
                        <option value="">All Intents</option>
                    </select>
                    <button onclick="refreshChatDetails()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Refresh
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Energy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OOD</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="chatDetailsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-6">
                <div class="text-sm text-gray-700">
                    Showing <span id="showingStart">1</span> to <span id="showingEnd">10</span> of <span id="totalRecords">0</span> results
                </div>
                <div class="flex space-x-2">
                    <button id="prevPage" onclick="changePage(-1)" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Previous
                    </button>
                    <span id="currentPage" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium">1</span>
                    <button id="nextPage" onclick="changePage(1)" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                    </button>
                </div>
            </div>
        </div>

        <!-- Prediction Analysis Tab -->
        <div id="prediction-analysis-tab" class="tab-content p-6 hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Prediction Analysis</h2>

            <!-- Accuracy Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Per-Intent Accuracy</h3>
                    <canvas id="accuracyChart"></canvas>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Confusion Matrix</h3>
                    <div id="confusionMatrix" class="overflow-auto max-h-96"></div>
                </div>
            </div>

            <!-- Detailed Metrics -->
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detailed Metrics</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correct</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accuracy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Confidence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Prediction Prob</th>
                            </tr>
                        </thead>
                        <tbody id="metricsTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Dynamic content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Probability Modal -->
<div id="probabilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Class Probabilities</h3>
                    <button onclick="closeProbabilityModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="probabilityContent">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Global variables
    let currentPage = 1;
    let totalPages = 1;
    let currentIntent = '';
    let accuracyChart = null;

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboard();
        setupEventListeners();
    });

    function setupEventListeners() {
        // Tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                switchTab(tabName);
            });
        });

        // Intent filter
        document.getElementById('intentFilter').addEventListener('change', function() {
            currentIntent = this.value;
            currentPage = 1;
            loadChatDetails();
        });
    }

    function switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-blue-500', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        document.querySelector(`[data-tab="${tabName}"]`).classList.remove('border-transparent', 'text-gray-500');
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('border-blue-500', 'text-blue-600');

        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.getElementById(`${tabName}-tab`).classList.remove('hidden');

        // Load specific tab data
        if (tabName === 'performance') {
            loadPerformanceData();
        } else if (tabName === 'chat-details') {
            loadChatDetails();
            loadIntentFilter();
        } else if (tabName === 'prediction-analysis') {
            loadPredictionAnalysis();
        }
    }

    async function loadDashboard() {
        try {
            const response = await fetch('<?= base_url("intent_analytics/get_accuracy_metrics") ?>');
            const data = await response.json();

            // Update summary cards
            document.getElementById('totalConversations').textContent = data.overall.total_predictions;
            document.getElementById('overallAccuracy').textContent = data.overall.overall_accuracy + '%';
            document.getElementById('avgConfidence').textContent = data.overall.avg_confidence_score;
            document.getElementById('oodRate').textContent = data.overall.ood_percentage + '%';

            // Load default tab
            loadPerformanceData();
        } catch (error) {
            console.error('Error loading dashboard:', error);
        }
    }

    async function loadPerformanceData() {
        try {
            const response = await fetch('<?= base_url("intent_analytics/get_intent_performance") ?>');
            const data = await response.json();

            const tbody = document.getElementById('performanceTableBody');
            tbody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';

                const successRateColor = item.success_rate >= 80 ? 'text-green-600' :
                    item.success_rate >= 60 ? 'text-yellow-600' : 'text-red-600';

                const oodColor = item.ood_percentage <= 10 ? 'text-green-600' :
                    item.ood_percentage <= 25 ? 'text-yellow-600' : 'text-red-600';

                row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.intent}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.total_occurrences}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.avg_confidence_score}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.avg_energy}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ${oodColor}">${item.ood_percentage}%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ${successRateColor}">${item.success_rate}%</td>
                    `;

                tbody.appendChild(row);
            });

        } catch (error) {
            console.error('Error loading performance data:', error);
        }
    }

    async function loadChatDetails() {
        try {
            const url = `<?= base_url("intent_analytics/get_chat_details") ?>?page=${currentPage}&intent=${currentIntent}`;
            const response = await fetch(url);
            const result = await response.json();

            const tbody = document.getElementById('chatDetailsTableBody');
            tbody.innerHTML = '';

            result.data.forEach(item => {
                console.log(`Intent: ${item.intent_class}, Probability:`, item.probability, typeof item.probability);

                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';

                const oodBadge = item.ood ?
                    '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">OOD</span>' :
                    '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">In-Domain</span>';

                const confidenceColor = item.confident_score >= 0.8 ? 'text-green-600' :
                    item.confident_score >= 0.6 ? 'text-yellow-600' : 'text-red-600';

                row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.user_name || 'Unknown'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.intent}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ${confidenceColor}">${item.confident_score}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.energy}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${oodBadge}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(item.timestamp).toLocaleString()}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="showProbabilities(${item.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-chart-pie mr-1"></i>View Probabilities
                            </button>
                        </td>

                    `;

                tbody.appendChild(row);
            });

            // Update pagination
            updatePagination(result.pagination);

        } catch (error) {
            console.error('Error loading chat details:', error);
        }
    }

    async function loadIntentFilter() {
        try {
            const response = await fetch('<?= base_url("intent_analytics/get_intent_performance") ?>');
            const data = await response.json();

            const select = document.getElementById('intentFilter');
            select.innerHTML = '<option value="">All Intents</option>';

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.intent;
                option.textContent = item.intent;
                select.appendChild(option);
            });

        } catch (error) {
            console.error('Error loading intent filter:', error);
        }
    }

    async function loadPredictionAnalysis() {
        try {
            const response = await fetch('<?= base_url("intent_analytics/get_accuracy_metrics") ?>');
            const data = await response.json();

            // Load accuracy chart
            loadAccuracyChart(data.per_intent);

            // Load confusion matrix
            loadConfusionMatrix();

            // Load detailed metrics table
            loadMetricsTable(data.per_intent);

        } catch (error) {
            console.error('Error loading prediction analysis:', error);
        }
    }

    function loadAccuracyChart(data) {
        const ctx = document.getElementById('accuracyChart').getContext('2d');

        if (accuracyChart) {
            accuracyChart.destroy();
        }

        accuracyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.intent),
                datasets: [{
                    label: 'Accuracy (%)',
                    data: data.map(item => item.accuracy),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    async function loadConfusionMatrix() {
        try {
            const response = await fetch('<?= base_url("intent_analytics/get_confusion_matrix") ?>');
            const data = await response.json();

            const container = document.getElementById('confusionMatrix');
            container.innerHTML = '';

            if (data.intents && data.intents.length > 0) {
                const table = document.createElement('table');
                table.className = 'min-w-full text-sm';

                // Header
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                headerRow.innerHTML = '<th class="p-2 border bg-gray-50">Actual \\ Predicted</th>';

                data.intents.forEach(intent => {
                    const th = document.createElement('th');
                    th.className = 'p-2 border bg-gray-50 text-xs';
                    th.textContent = intent;
                    headerRow.appendChild(th);
                });

                thead.appendChild(headerRow);
                table.appendChild(thead);

                // Body
                const tbody = document.createElement('tbody');

                data.intents.forEach(actualIntent => {
                    const row = document.createElement('tr');

                    const th = document.createElement('th');
                    th.className = 'p-2 border bg-gray-50 text-xs text-left';
                    th.textContent = actualIntent;
                    row.appendChild(th);

                    data.intents.forEach(predictedIntent => {
                        const td = document.createElement('td');
                        td.className = 'p-2 border text-center';

                        const count = data.matrix[actualIntent] && data.matrix[actualIntent][predictedIntent] ?
                            data.matrix[actualIntent][predictedIntent] : 0;

                        td.textContent = count;

                        // Color coding
                        if (actualIntent === predictedIntent && count > 0) {
                            td.className += ' bg-green-100 text-green-800 font-semibold';
                        } else if (count > 0) {
                            td.className += ' bg-red-100 text-red-800';
                        }

                        row.appendChild(td);
                    });

                    tbody.appendChild(row);
                });

                table.appendChild(tbody);
                container.appendChild(table);
            } else {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No data available for confusion matrix</p>';
            }

        } catch (error) {
            console.error('Error loading confusion matrix:', error);
        }
    }

    function loadMetricsTable(data) {
        const tbody = document.getElementById('metricsTableBody');
        tbody.innerHTML = '';

        data.forEach(item => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';

            const accuracyColor = item.accuracy >= 80 ? 'text-green-600' :
                item.accuracy >= 60 ? 'text-yellow-600' : 'text-red-600';

            row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.intent}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.total}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.correct}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm ${accuracyColor}">${item.accuracy}%</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.avg_confidence}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.avg_prediction_prob}</td>
                `;

            tbody.appendChild(row);
        });
    }

    async function showProbabilities(chatId) {
        try {
            const response = await fetch(`<?= base_url("intent_analytics/get_class_probabilities/") ?>${chatId}`);
            const data = await response.json();

            const content = document.getElementById('probabilityContent');
            content.innerHTML = '';

            if (data.length > 0) {
                const table = document.createElement('table');
                table.className = 'min-w-full border border-gray-200 rounded-lg';

                table.innerHTML = `
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Intent Class</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Probability</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Confidence</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        </tbody>
                    `;

                const tbody = table.querySelector('tbody');

                data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = index === 0 ? 'bg-blue-50' : 'hover:bg-gray-50';

                    const confidenceBar = Math.round(item.probability * 100);

                    row.innerHTML = `
                            <td class="px-4 py-2 text-sm font-medium text-gray-900">${item.intent_class}</td>
                            <td class="px-4 py-2 text-sm text-gray-500">${item.probability.toFixed(4)}</td>
                            <td class="px-4 py-2 text-sm">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: ${confidenceBar}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">${confidenceBar}%</span>
                                </div>
                            </td>
                        `;

                    tbody.appendChild(row);
                });

                content.appendChild(table);
            } else {
                content.innerHTML = '<p class="text-gray-500 text-center py-4">No probability data available</p>';
            }

            document.getElementById('probabilityModal').classList.remove('hidden');

        } catch (error) {
            console.error('Error loading probabilities:', error);
        }
    }

    function closeProbabilityModal() {
        document.getElementById('probabilityModal').classList.add('hidden');
    }

    function updatePagination(pagination) {
        document.getElementById('showingStart').textContent = ((pagination.current_page - 1) * pagination.per_page) + 1;
        document.getElementById('showingEnd').textContent = Math.min(pagination.current_page * pagination.per_page, pagination.total_records);
        document.getElementById('totalRecords').textContent = pagination.total_records;
        document.getElementById('currentPage').textContent = pagination.current_page;

        currentPage = pagination.current_page;
        totalPages = pagination.total_pages;

        // Update button states
        document.getElementById('prevPage').disabled = currentPage <= 1;
        document.getElementById('nextPage').disabled = currentPage >= totalPages;
    }

    function changePage(direction) {
        const newPage = currentPage + direction;
        if (newPage >= 1 && newPage <= totalPages) {
            currentPage = newPage;
            loadChatDetails();
        }
    }

    function refreshChatDetails() {
        currentPage = 1;
        loadChatDetails();
    }

    function exportPerformanceData() {
        window.location.href = '<?= base_url("intent_analytics/export_intent_performance") ?>';
    }

    // Close modal when clicking outside
    document.getElementById('probabilityModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProbabilityModal();
        }
    });
</script>