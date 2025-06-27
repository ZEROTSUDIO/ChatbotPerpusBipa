<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Chat Reports</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <div class="mb-6">
            <h1 class="handwriting text-4xl font-bold mb-4">Chat Reports</h1>
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <input type="text" placeholder="Search chats..." class="px-4 py-2 border-2 border-black rounded-lg w-full sm:w-auto">
                <select class="px-4 py-2 border-2 border-black rounded-lg">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chat ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Energy</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OOD</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#CHT001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#USR001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">CHAT_20240115_001</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">book_recommendation</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0.92</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">0.85</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">No</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-15 10:30:45</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>