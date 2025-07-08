<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Intent Responses</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <div class="mb-6">
            <h1 class="handwriting text-4xl font-bold mb-4">Intent reports</h1>
            <!--summary-->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 border-black">
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

                <div class="bg-white rounded-lg shadow-md p-6 border-black">
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

                <div class="bg-white rounded-lg shadow-md p-6 border-black">
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

                <div class="bg-white rounded-lg shadow-md p-6 border-black">
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

            <!--table-->

            <div class="bg-white border-2 border-black rounded-lg w-full">
                <!-- Intent Performance Tab -->
                <div id="performance-tab" class="tab-content p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Intent Performance Overview</h2>                        
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
    </main>
</div>

<script>
    let accuracyChart = null;
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboard();        
    })

    async function loadDashboard() {
        try {
            const response = await fetch('<?= base_url("admin/get_accuracy_metrics") ?>');
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
            const response = await fetch('<?= base_url("admin/get_intent_performance") ?>');
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

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatNumber(number, decimals = 2) {
        return parseFloat(number).toFixed(decimals);
    }
</script>