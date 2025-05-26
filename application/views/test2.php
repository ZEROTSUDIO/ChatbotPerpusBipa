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
    <style>
        /* Dashboard Custom CSS - consistent with chat interface */
        body {
            font-family: "Patrick Hand", cursive;
            background-color: #f5f5f0;
            color: #333;
            line-height: 1.6;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1h98v98H1V1zm1 1v96h96V2H2z' fill='%23e0e0e0' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .handwriting {
            font-family: 'Caveat', cursive;
        }

        .dashboard-wrapper {
            box-shadow: 8px 8px 0 rgba(0,0,0,0.2);
            position: relative;
        }

        /* Page curl effect */
        .dashboard-wrapper:after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 0;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, transparent 50%, #e0e0e0 50%);
            border-radius: 0 0 5px 0;
        }

        .sidebar {
            background-color: white;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23e0e0e0' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 4rem;
        }

        .sidebar:not(.collapsed) {
            width: 16rem;
        }

        .sidebar-item {
            transition: all 0.2s ease;
            border-bottom: 1px dotted #e0e0e0;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-item:hover {
            background-color: #f8f8f8;
            transform: translateX(2px);
            box-shadow: 2px 2px 0 rgba(0,0,0,0.1);
        }

        .sidebar-item.active {
            background-color: #f0f0f0;
            border-left: 4px solid #333;
        }

        .sidebar-text {
            transition: opacity 0.2s ease;
        }

        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar.collapsed .sidebar-item {
            justify-content: center;
        }

        .sidebar-toggle {
            position: fixed;
            bottom: 2rem;
            left: 1rem;
            z-index: 1001;
            background: white;
            border: 2px solid #333;
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 4px 4px 0 rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 0 rgba(0,0,0,0.2);
        }

        .main-wrapper {
            transition: margin-left 0.3s ease;
        }

        .main-wrapper.sidebar-open {
            margin-left: 16rem;
        }

        .main-wrapper.sidebar-collapsed {
            margin-left: 4rem;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            background-color: #fff;
            min-width: 200px;
            box-shadow: 5px 5px 0 rgba(0,0,0,0.2);
            z-index: 1000;
            border: 2px solid #000;
        }

        .dropdown-content a {
            color: #000;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            transition: all 0.2s ease;
            border-bottom: 1px dotted #eee;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background-color: #f8f8f8;
        }

        .profile-dropdown:hover .dropdown-content {
            display: block;
        }

        .main-content {
            background-color: white;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23e0e0e0' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .card {
            background-color: white;
            border: 2px solid #000;
            box-shadow: 4px 4px 0 rgba(0,0,0,0.2);
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 0 rgba(0,0,0,0.2);
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card.green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .table-wrapper {
            background: white;
            border: 2px solid #000;
            box-shadow: 4px 4px 0 rgba(0,0,0,0.2);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #000;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            border: 1px solid;
        }

        .status-online {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #10b981;
        }

        .status-offline {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #ef4444;
        }

        .status-away {
            background-color: #fef3c7;
            color: #92400e;
            border-color: #f59e0b;
        }

        .chart-container {
            position: relative;
            height: 300px;
            background: white;
            border: 2px solid #000;
            box-shadow: 4px 4px 0 rgba(0,0,0,0.2);
            border-radius: 0.5rem;
            padding: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0 !important;
            }
            
            .sidebar-toggle {
                left: 1rem;
                bottom: 1rem;
            }
        }

        .mobile-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .mobile-backdrop.show {
            display: block;
        }
    </style>
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
                                <span class="sidebar-text">Chat History</span>
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

                <div class="mb-6">
                    <h3 class="handwriting text-lg font-bold mb-3 px-3 sidebar-text">AI Assistant</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="chatbot">
                                <i class="fas fa-robot mr-3 text-lg"></i>
                                <span class="sidebar-text">Chat Bot</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg" data-section="recommendations">
                                <i class="fas fa-lightbulb mr-3 text-lg"></i>
                                <span class="sidebar-text">Recommendations</span>
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
                                <tr>
                                    <td class="px-4 py-3">#USR002</td>
                                    <td class="px-4 py-3">Sarah Miller</td>
                                    <td class="px-4 py-3">sarah@example.com</td>
                                    <td class="px-4 py-3">2024-02-20</td>
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

            <!-- Chat History Section -->
            <div id="chats-content" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="handwriting text-3xl font-bold mb-4">Chat History</h2>
                    <div class="flex space-x-4 mb-4">
                        <input type="text" placeholder="Search chats..." class="px-4 py-2 border-2 border-black rounded-lg">
                        <select class="px-4 py-2 border-2 border-black rounded-lg">
                            <option>All Chats</option>
                            <option>Active</option>
                            <option>Completed</option>
                            <option>Archived</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid gap-4">
                    <div class="card p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-bold">Chat with John Doe</h4>
                                <p class="text-sm text-gray-600">Started: 2024-05-25 10:30 AM</p>
                            </div>
                            <span class="status-badge status-online">Active</span>
                        </div>
                        <p class="text-sm mb-2">Last message: "Can you recommend some science fiction books?"</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Duration: 15 minutes</span>
                            <span>Messages: 12</span>
                        </div>
                    </div>
                    
                    <div class="card p-4 rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-bold">Chat with Sarah Miller</h4>
                                <p class="text-sm text-gray-600">Started: 2024-05-25 09:15 AM</p>
                            </div>
                            <span class="status-badge status-offline">Completed</span>
                        </div>
                        <p class="text-sm mb-2">Last message: "Thank you for the help!"</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Duration: 8 minutes</span>
                            <span>Messages: 6</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intents Section -->
            <div id="intents-content" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="handwriting text-3xl font-bold mb-4">Intent Management</h2>
                    <button class="bg-green-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-green-600 mb-4">
                        <i class="fas fa-plus mr-2"></i>Add New Intent
                    </button>
                </div>
                
                <div class="table-wrapper">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 text-left">Intent Name</th>
                                    <th class="px-4 py-3 text-left">Category</th>
                                    <th class="px-4 py-3 text-left">Training Phrases</th>
                                    <th class="px-4 py-3 text-left">Confidence</th>
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-3">Book Recommendation</td>
                                    <td class="px-4 py-3">Library Services</td>
                                    <td class="px-4 py-3">25 phrases</td>
                                    <td class="px-4 py-3">94.2%</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3">Library Hours</td>
                                    <td class="px-4 py-3">Information</td>
                                    <td class="px-4 py-3">18 phrases</td>
                                    <td class="px-4 py-3">91.7%</td>
                                    <td class="px-4 py-3">
                                        <button class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
				<div class="container mt-5 p-4 bg-white shadow-md rounded-lg">
				  <h2 class="text-2xl font-semibold mb-4">Edit Response for Intent: <span class="text-blue-500"></span></h2>

				  <form action="<?= base_url('admin/save_response') ?>" method="post">
					<input type="hidden" name="intent" value="">
					
					<div class="mb-4">
					  <label for="response" class="form-label font-medium">Response Text</label>
					  <textarea id="response" name="response" class="form-control"></textarea>
					</div>

					<div class="flex space-x-4">
					  <button type="submit" class="btn btn-primary">Save</button>
					  <a href="<?= base_url('admin/responses') ?>" class="btn btn-secondary">Cancel</a>
					</div>
				  </form>
				</div>				
			</div>

            <!-- Chatbot Section -->
            <div id="chatbot-content" class="content-section hidden">
                <div class="mb-6">
                    <h2 class="handwriting text-3xl font-bold mb-4">AI Chatbot Configuration</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card p-6 rounded-lg">
                        <h3 class="handwriting text-xl font-bold mb-4">Bot Settings</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Bot Name</label>
                                <input type="text" value="Bipa Assistant" class="w-full px-3 py-2 border-2 border-black rounded">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Response Delay (ms)</label>
                                <input type="number" value="1000" class="w-full px-3 py-2 border-2 border-black rounded">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Confidence Threshold</label>
                                <input type="range" min="0" max="100" value="80" class="w-full">
                                <span class="text-sm text-gray-600">80%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6 rounded-lg">
                        <h3 class="handwriting text-xl font-bold mb-4">Quick Test</h3>
                        <div class="bg-gray-100 p-4 rounded-lg mb-4 h-48 overflow-y-auto">
                            <div class="mb-2">
                                <div class="bg-blue-500 text-white p-2 rounded-lg inline-block">
                                    Hello! How can I help you today?
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <input type="text" placeholder="Type a test message..." class="flex-1 px-3 py-2 border-2 border-black rounded-l">
                            <button class="bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-r hover:bg-blue-600">
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other sections (recommendations, statistics, reports) would follow similar patterns -->
            <div id="recommendations-content" class="content-section hidden">
                <h2 class="handwriting text-3xl font-bold mb-4">AI Recommendations</h2>
                <p class="text-gray-600">Recommendation system configuration coming soon...</p>
            </div>

            <div id="statistics-content" class="content-section hidden">
                <h2 class="handwriting text-3xl font-bold mb-4">Detailed Statistics</h2>
                <p class="text-gray-600">Advanced analytics dashboard coming soon...</p>
            </div>

            <div id="reports-content" class="content-section hidden">
                <h2 class="handwriting text-3xl font-bold mb-4">Reports</h2>
                <p class="text-gray-600">Report generation tools coming soon...</p>
            </div>
        </main>
    </div>

    <script>
        // Dashboard JavaScript
        $(document).ready(function() {
            // Sidebar toggle functionality
            $('#sidebar-toggle').click(function() {
                const sidebar = $('#sidebar');
                const mainWrapper = $('#main-wrapper');
                
                if (window.innerWidth <= 768) {
                    // Mobile behavior
                    sidebar.toggleClass('mobile-open');
                    $('#mobile-backdrop').toggleClass('show');
                } else {
                    // Desktop behavior
                    sidebar.toggleClass('collapsed');
                    if (sidebar.hasClass('collapsed')) {
                        mainWrapper.removeClass('sidebar-open').addClass('sidebar-collapsed');
                    } else {
                        mainWrapper.removeClass('sidebar-collapsed').addClass('sidebar-open');
                    }
                }
            });

            // Mobile backdrop click
            $('#mobile-backdrop').click(function() {
                $('#sidebar').removeClass('mobile-open');
                $(this).removeClass('show');
            });

            // Sidebar navigation
            $('.sidebar-item').click(function(e) {
                e.preventDefault();
                
                // Remove active class from all items
                $('.sidebar-item').removeClass('active');
                // Add active class to clicked item
                $(this).addClass('active');
                
                // Hide all content sections
                $('.content-section').addClass('hidden');
                
                // Show selected section
                const section = $(this).data('section');
                $(`#${section}-content`).removeClass('hidden');
                
                // Update breadcrumb
                const sectionName = $(this).find('.sidebar-text').text();
                $('#current-section').text(sectionName);
                
                // Close mobile sidebar
                if (window.innerWidth <= 768) {
                    $('#sidebar').removeClass('mobile-open');
                    $('#mobile-backdrop').removeClass('show');
                }
            });

            // Initialize charts
            initializeCharts();
        });

        function initializeCharts() {
            // Chat Activity Chart
            const chatCtx = document.getElementById('chatActivityChart').getContext('2d');
            new Chart(chatCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Chat Sessions',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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

            // User Engagement Chart
            const engagementCtx = document.getElementById('userEngagementChart').getContext('2d');
            new Chart(engagementCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Active Users', 'Returning Users', 'New Users'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: ['#11998e', '#f5576c', '#4facfe'],
                        borderWidth: 2,
                        borderColor: '#fff'
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

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = $('#sidebar');
            const mainWrapper = $('#main-wrapper');
            
            if (window.innerWidth > 768) {
                sidebar.removeClass('mobile-open');
                $('#mobile-backdrop').removeClass('show');
                
                if (sidebar.hasClass('collapsed')) {
                    mainWrapper.removeClass('sidebar-open').addClass('sidebar-collapsed');
                } else {
                    mainWrapper.removeClass('sidebar-collapsed').addClass('sidebar-open');
                }
            } else {
                mainWrapper.removeClass('sidebar-open sidebar-collapsed');
            }
        });
    </script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>	
	<script>
	  $(document).ready(function() {
		$('#response').summernote({
		  height: 300,
		  tabsize: 2,
		  placeholder: 'Type your dynamic response here...',
		  toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['para', ['ul', 'ol', 'paragraph']],
			['insert', ['link']],
			['view', ['codeview']]
		  ]
		});
	  });
	</script>
</body>
</html>							