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
                        <p class="text-white text-3xl font-bold handwriting">1,247</p>
                    </div>
                    <i class="fas fa-users text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4">
                    <span class="text-green-200 text-sm">↑ 12% from last month</span>
                </div>
            </div>

            <div class="card green p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Active Chats</p>
                        <p class="text-white text-3xl font-bold handwriting">89</p>
                    </div>
                    <i class="fas fa-comments text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4">
                    <span class="text-green-200 text-sm">↑ 8% from yesterday</span>
                </div>
            </div>

            <div class="card orange p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Messages Today</p>
                        <p class="text-white text-3xl font-bold handwriting">3,421</p>
                    </div>
                    <i class="fas fa-envelope text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4">
                    <span class="text-red-200 text-sm">↓ 3% from yesterday</span>
                </div>
            </div>

            <div class="card blue p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm opacity-80">Bot Accuracy</p>
                        <p class="text-white text-3xl font-bold handwriting">94.2%</p>
                    </div>
                    <i class="fas fa-robot text-white text-2xl opacity-80"></i>
                </div>
                <div class="mt-4">
                    <span class="text-green-200 text-sm">↑ 2% from last week</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="table-wrapper">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="handwriting text-xl font-bold">Recent Activity</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                        JD
                                    </div>
                                    <span class="font-medium">John Doe</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Started new chat session</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 minutes ago</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge status-online">Active</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                        SM
                                    </div>
                                    <span class="font-medium">Sarah Miller</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Asked about book recommendations</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 minutes ago</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge status-online">Active</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                        RJ
                                    </div>
                                    <span class="font-medium">Robert Johnson</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Completed feedback survey</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10 minutes ago</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge status-offline">Offline</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>