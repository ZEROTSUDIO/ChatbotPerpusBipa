<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Statistics Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- Statistics Dashboard Section -->
<div class="p-6 space-y-6">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Chats</p>
                    <p id="totalChats" class="text-3xl font-bold text-gray-900">0</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="chatGrowth" class="text-sm text-green-600">+12% from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p id="activeUsers" class="text-3xl font-bold text-gray-900">0</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.239"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="userGrowth" class="text-sm text-green-600">+8% from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg Confidence</p>
                    <p id="avgConfidence" class="text-3xl font-bold text-gray-900">0%</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="confidenceChange" class="text-sm text-red-600">-2% from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">OOD Rate</p>
                    <p id="oodRate" class="text-3xl font-bold text-gray-900">0%</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span id="oodChange" class="text-sm text-green-600">-5% from last month</span>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chat Volume Trend -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Chat Volume Trend</h3>
                <select id="volumePeriod" class="text-sm border border-gray-300 rounded-md px-3 py-1">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 3 months</option>
                </select>
            </div>
            <canvas id="chatVolumeChart"></canvas>
        </div>

        <!-- Intent Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Intents</h3>
            <canvas id="intentChart"></canvas>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Confidence Score Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confidence Distribution</h3>
            <canvas id="confidenceChart"></canvas>
        </div>

        <!-- Peak Hours Heatmap -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Peak Hours</h3>
            <div id="peakHoursGrid" class="grid grid-cols-4 gap-1">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <!-- Energy vs Confidence -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Energy vs Confidence</h3>
            <canvas id="energyConfidenceChart"></canvas>
        </div>
    </div>

    <!-- Detailed Statistics Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Intent Performance</h3>
            <button id="refreshData" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                Refresh Data
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Confidence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Energy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OOD Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                    </tr>
                </thead>
                <tbody id="intentTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Real-time Activity Feed -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div id="activityFeed" class="space-y-3 max-h-64 overflow-y-auto">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
// Dashboard JavaScript Implementation
class ChatStatsDashboard {
    constructor() {
        this.charts = {};
        this.refreshInterval = null;
        this.init();
    }

    async init() {
        await this.loadInitialData();
        this.setupCharts();
        this.setupEventListeners();
        this.startAutoRefresh();
    }

    async loadInitialData() {
        try {
            // Load overview stats
            const overviewResponse = await fetch('/chatbot/api/stats/overview');
            const overviewData = await overviewResponse.json();
            this.updateOverviewCards(overviewData);

            // Load chart data
            const chartsResponse = await fetch('/chatbot/api/stats/charts');
            const chartsData = await chartsResponse.json();
            this.updateCharts(chartsData);

            // Load intent performance
            const intentResponse = await fetch('/chatbot/api/stats/intent-performance');
            const intentData = await intentResponse.json();
            this.updateIntentTable(intentData);

            // Load activity feed
            const activityResponse = await fetch('/chatbot/api/stats/recent-activity');
            const activityData = await activityResponse.json();
            this.updateActivityFeed(activityData);

        } catch (error) {
            console.error('Error loading dashboard data:', error);
            this.showError('Failed to load dashboard data');
        }
    }

    updateOverviewCards(data) {
        document.getElementById('totalChats').textContent = data.totalChats.toLocaleString();
        document.getElementById('activeUsers').textContent = data.activeUsers.toLocaleString();
        document.getElementById('avgConfidence').textContent = `${data.avgConfidence.toFixed(1)}%`;
        document.getElementById('oodRate').textContent = `${data.oodRate.toFixed(1)}%`;

        // Update growth indicators
        document.getElementById('chatGrowth').textContent = `${data.chatGrowth > 0 ? '+' : ''}${data.chatGrowth.toFixed(1)}% from last month`;
        document.getElementById('userGrowth').textContent = `${data.userGrowth > 0 ? '+' : ''}${data.userGrowth.toFixed(1)}% from last month`;
        document.getElementById('confidenceChange').textContent = `${data.confidenceChange > 0 ? '+' : ''}${data.confidenceChange.toFixed(1)}% from last month`;
        document.getElementById('oodChange').textContent = `${data.oodChange > 0 ? '+' : ''}${data.oodChange.toFixed(1)}% from last month`;
    }

    setupCharts() {
        // Chat Volume Trend Chart
        this.charts.volume = new Chart(document.getElementById('chatVolumeChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Chat Volume',
                    data: [],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Intent Distribution Chart
        this.charts.intent = new Chart(document.getElementById('intentChart'), {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Confidence Distribution Chart
        this.charts.confidence = new Chart(document.getElementById('confidenceChart'), {
            type: 'bar',
            data: {
                labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'],
                datasets: [{
                    label: 'Messages',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Energy vs Confidence Scatter Plot
        this.charts.energyConfidence = new Chart(document.getElementById('energyConfidenceChart'), {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Messages',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.6)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Energy'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Confidence'
                        }
                    }
                }
            }
        });
    }

    updateCharts(data) {
        // Update volume chart
        this.charts.volume.data.labels = data.volume.labels;
        this.charts.volume.data.datasets[0].data = data.volume.data;
        this.charts.volume.update();

        // Update intent chart
        this.charts.intent.data.labels = data.intent.labels;
        this.charts.intent.data.datasets[0].data = data.intent.data;
        this.charts.intent.update();

        // Update confidence chart
        this.charts.confidence.data.datasets[0].data = data.confidence.data;
        this.charts.confidence.update();

        // Update energy vs confidence
        this.charts.energyConfidence.data.datasets[0].data = data.energyConfidence;
        this.charts.energyConfidence.update();

        // Update peak hours heatmap
        this.updatePeakHours(data.peakHours);
    }

    updatePeakHours(data) {
        const grid = document.getElementById('peakHoursGrid');
        grid.innerHTML = '';
        
        const hours = ['00-06', '06-12', '12-18', '18-24'];
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        
        data.forEach((dayData, dayIndex) => {
            dayData.forEach((hourValue, hourIndex) => {
                const intensity = Math.min(hourValue / Math.max(...data.flat()), 1);
                const cell = document.createElement('div');
                cell.className = `w-8 h-8 rounded text-xs flex items-center justify-center text-white`;
                cell.style.backgroundColor = `rgba(59, 130, 246, ${intensity})`;
                cell.textContent = hourValue;
                cell.title = `${days[dayIndex]} ${hours[hourIndex]}: ${hourValue} messages`;
                grid.appendChild(cell);
            });
        });
    }

    updateIntentTable(data) {
        const tbody = document.getElementById('intentTableBody');
        tbody.innerHTML = '';
        
        data.forEach(intent => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${intent.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${intent.count.toLocaleString()}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${intent.avgConfidence.toFixed(2)}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${intent.avgEnergy.toFixed(2)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${intent.oodRate.toFixed(1)}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        intent.trend > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }">
                        ${intent.trend > 0 ? '↑' : '↓'} ${Math.abs(intent.trend).toFixed(1)}%
                    </span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    updateActivityFeed(data) {
        const feed = document.getElementById('activityFeed');
        feed.innerHTML = '';
        
        data.forEach(activity => {
            const item = document.createElement('div');
            item.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded-lg';
            item.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-2 h-2 bg-${activity.type === 'chat' ? 'blue' : activity.type === 'user' ? 'green' : 'yellow'}-500 rounded-full"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-900">${activity.message}</p>
                    <p class="text-xs text-gray-500">${activity.time}</p>
                </div>
            `;
            feed.appendChild(item);
        });
    }

    setupEventListeners() {
        // Volume period selector
        document.getElementById('volumePeriod').addEventListener('change', async (e) => {
            const period = e.target.value;
            const response = await fetch(`/chatbot/api/stats/volume?period=${period}`);
            const data = await response.json();
            this.charts.volume.data.labels = data.labels;
            this.charts.volume.data.datasets[0].data = data.data;
            this.charts.volume.update();
        });

        // Refresh button
        document.getElementById('refreshData').addEventListener('click', () => {
            this.loadInitialData();
        });
    }

    startAutoRefresh() {
        // Refresh every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.loadInitialData();
        }, 30000);
    }

    showError(message) {
        // Create error notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        Object.values(this.charts).forEach(chart => chart.destroy());
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.chatDashboard = new ChatStatsDashboard();
});
</script>

</body>
</html>