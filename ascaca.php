<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Perpus Bipa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/main2.css" rel="stylesheet">
</head>

<body class="min-h-screen">
    <!-- Mobile backdrop -->
    <div id="mobile-backdrop" class="mobile-backdrop"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="p-4">
            <!-- Logo -->
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 rounded-full mr-3 flex items-center justify-center border-2 border-black">
                    <i class="fas fa-book text-lg"></i>
                </div>
                <h1 class="text-xl font-bold handwriting sidebar-text">Perpus Bipa</h1>
            </div>

            <!-- Navigation -->            
            <nav>
                <div class="mb-6">
                    <h3 class="handwriting text-lg font-bold mb-3 px-3 sidebar-text">Menu Utama</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="sidebar-item active flex items-center px-3 py-3 rounded-lg" data-section="dashboard">
                                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                                <span class="sidebar-text">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="users">
                                <i class="fas fa-users mr-3 text-lg"></i>
                                <span class="sidebar-text">Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="chats">
                                <i class="fas fa-comments mr-3 text-lg"></i>
                                <span class="sidebar-text">Chats Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="intents">
                                <i class="fas fa-handshake mr-3 text-lg"></i>
                                <span class="sidebar-text">Intents</span>
                            </a>
                        </li>
                    </ul>
                </div>                
                <div>
                    <h3 class="handwriting text-lg font-bold mb-3 px-3 sidebar-text">Reports</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="statistics">
                                <i class="fas fa-chart-bar mr-3 text-lg"></i>
                                <span class="sidebar-text">Statistics</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="reports">
                                <i class="fas fa-file-alt mr-3 text-lg"></i>
                                <span class="sidebar-text">Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Sidebar Toggle Button -->
    <button id="sidebar-toggle" class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Main Wrapper -->
    <div id="main-wrapper" class="main-wrapper sidebar-open">
        <!-- Header -->
        <header class="bg-white border-b-2 border-black p-4 flex justify-between items-center relative z-30">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <span class="text-gray-500">Dashboard</span>
                <span class="mx-2">/</span>
                <span id="current-section">Overview</span>
            </div>

            <!-- Header actions -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="bg-white border-2 border-black rounded-full p-2 hover:bg-gray-100 transition-colors relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                </button>

                <!-- Search -->
                <button class="bg-white border-2 border-black rounded-full p-2 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Profile dropdown -->
                <div class="profile-dropdown">
                    <button class="bg-white border-2 border-black rounded-full p-2 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="dropdown-content rounded-lg">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="font-bold handwriting text-lg">Admin User</p>
                            <p class="text-sm">admin@perpusbipa.com</p>
                            <p class="text-xs text-gray-500">ID: ADM001</p>
                        </div>
                        <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-100 rounded-b-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content p-6">
            <!-- Dashboard Overview -->
            <div id="dashboard-content" class="content-section">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="card stat-card p-6 rounded-lg">
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

                    <div class="card stat-card green p-6 rounded-lg">
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

                    <div class="card stat-card orange p-6 rounded-lg">
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

                    <div class="card stat-card blue p-6 rounded-lg">
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

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="chart-container">
                        <h3 class="handwriting text-xl font-bold mb-4">Chat Activity (7 Days)</h3>
                        <canvas id="chatActivityChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3 class="handwriting text-xl font-bold mb-4">User Engagement</h3>
                        <canvas id="userEngagementChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="table-wrapper">
                    <div class="table-header p-4">
                        <h3 class="handwriting text-xl font-bold">Recent Activity</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left">User</th>
                                    <th class="px-4 py-3 text-left">Action</th>
                                    <th class="px-4 py-3 text-left">Time</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                                JD
                                            </div>
                                            <span>John Doe</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">Started new chat session</td>
                                    <td class="px-4 py-3">2 minutes ago</td>
                                    <td class="px-4 py-3">
                                        <span class="status-badge status-online">Active</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                                SM
                                            </div>
                                            <span>Sarah Miller</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">Asked about book recommendations</td>
                                    <td class="px-4 py-3">5 minutes ago</td>
                                    <td class="px-4 py-3">
                                        <span class="status-badge status-online">Active</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm mr-3">
                                                RJ
                                            </div>
                                            <span>Robert Johnson</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">Completed feedback survey</td>
                                    <td class="px-4 py-3">10 minutes ago</td>
                                    <td class="px-4 py-3">
                                        <span class="status-badge status-offline">Offline</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div id="users-content" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="handwriting text-3xl font-bold mb-4">User Management</h2>
                    <div class="flex justify-between items-center mb-4">
                        <input type="text" placeholder="Search users..." class="px-4 py-2 border-2 border-black rounded-lg">
                        <button class="bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-blue-600">
                            <i class="fas fa-plus mr-2"></i>Add User
                        </button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 text-left">User ID</th>
                                    <th class="px-4 py-3 text-left">Name</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Join Date</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-3">#USR001</td>
                                    <td class="px-4 py-3">John Doe</td>
                                    <td class="px-4 py-3">john@example.com</td>
                                    <td class="px-4 py-3">2024-01-15</td>
                                    <td class="px-4 py-3"><span class="status-badge status-online">Active</span></td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Chat detail History Section -->
            <div id="chats-content" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="handwriting text-3xl font-bold mb-4">Chat Reports</h2>
                    <div class="flex space-x-4 mb-4">
                        <input type="text" placeholder="Search chats..." class="px-4 py-2 border-2 border-black rounded-lg">
                        <select class="px-4 py-2 border-2 border-black rounded-lg">                           
                        </select>
                    </div>
                </div>
					<!--chats table with this coloumn id user_id chat_id intent confident_score energy ood timestamp
						 one statics dummy data					-->
            </div>

            <!-- Intents Section -->
            <div id="intents-content" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="handwriting text-3xl font-bold mb-4">Intent Responses Management</h2>
                    <button class="bg-green-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-green-600 mb-4">
                        <i class="fas fa-plus mr-2"></i>Add New Intent
                    </button>
                </div>

                <div class="table-wrapper">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 text-left">Intent</th>
                                    <th class="px-4 py-3 text-left">Responses</th>
                                    <th class="px-4 py-3 text-left">Updated at</th>                                    
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>                                
                            </tbody>
                        </table>
                    </div>
                </div>               
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="<?php echo base_url('assets/js/main2.js'); ?>"></script>
</body>

</html>