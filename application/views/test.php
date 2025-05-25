<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Perpus Bipa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
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
        }

        .sidebar-item {
            transition: all 0.2s ease;
            border-bottom: 1px dotted #e0e0e0;
        }

        .sidebar-item:hover {
            background-color: #f8f8f8;
            transform: translateX(5px);
            box-shadow: 2px 2px 0 rgba(0,0,0,0.1);
        }

        .sidebar-item.active {
            background-color: #f0f0f0;
            border-left: 4px solid #333;
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

        .mobile-menu-btn {
            transition: all 0.2s ease;
        }

        .mobile-menu-btn:hover {
            transform: translateY(-2px);
            box-shadow: 2px 2px 0 rgba(0,0,0,0.2);
        }

        .sidebar-mobile {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar-mobile.open {
            transform: translateX(0);
        }

        @media (max-width: 768px) {
            .dashboard-wrapper:after {
                display: none;
            }
        }

        .footer {
            background-color: white;
            border-top: 2px solid #000;
        }

        .breadcrumb {
            font-family: 'Caveat', cursive;
            font-size: 1.1rem;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb a:hover {
            color: #333;
        }
    </style>
</head>

<body class="min-h-screen">
    <!-- Mobile menu backdrop -->
    <div id="mobile-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>

    <div class="dashboard-wrapper bg-white min-h-screen border-2 border-black flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b-2 border-black p-4 flex justify-between items-center relative z-30">
            <!-- Mobile menu button -->
            <button id="mobile-menu-btn" class="md:hidden bg-white border-2 border-black rounded-lg p-2 mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo and title -->
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full mr-3 flex items-center justify-center border-2 border-black">
                    <i class="fas fa-book text-lg"></i>
                </div>
                <h1 class="text-2xl font-bold handwriting">Perpus Bina Patria</h1>
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
                            <p class="font-bold handwriting text-lg">John Doe</p>
                            <p class="text-sm">john@example.com</p>
                            <p class="text-xs text-gray-500">ID: 12345</p>
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

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside id="sidebar" class="sidebar sidebar-mobile md:sidebar-desktop w-64 border-r-2 border-black md:relative fixed left-0 top-0 h-full z-50 md:translate-x-0">
                <div class="p-4">
                    <!-- Mobile close button -->
                    <button id="close-sidebar" class="md:hidden absolute top-4 right-4 text-xl">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Navigation -->
                    <nav class="mt-8 md:mt-0">
                        <div class="mb-6">
                            <h3 class="handwriting text-lg font-bold mb-3 px-3">Menu Utama</h3>
                            <ul class="space-y-1">
                                <li>
                                    <a href="#" class="sidebar-item active flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-tachometer-alt mr-3"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-book mr-3"></i>
                                        Koleksi Buku
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-users mr-3"></i>
                                        Anggota
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-handshake mr-3"></i>
                                        Peminjaman
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-undo mr-3"></i>
                                        Pengembalian
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="mb-6">
                            <h3 class="handwriting text-lg font-bold mb-3 px-3">AI Assistant</h3>
                            <ul class="space-y-1">
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-robot mr-3"></i>
                                        Chat Bot
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-lightbulb mr-3"></i>
                                        Rekomendasi
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="handwriting text-lg font-bold mb-3 px-3">Laporan</h3>
                            <ul class="space-y-1">
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-chart-bar mr-3"></i>
                                        Statistik
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sidebar-item flex items-center px-3 py-3 rounded-lg">
                                        <i class="fas fa-file-alt mr-3"></i>
                                        Report
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 main-content">
                <!-- Breadcrumb -->
                <div class="p-4 border-b border-gray-200">
                    <nav class="breadcrumb">
                        <a href="#">Home</a> / <a href="#">Dashboard</a> / <span class="text-black font-bold">Overview</span>
                    </nav>
                </div>

                <!-- Page Content -->
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="handwriting text-3xl font-bold mb-2">Dashboard Overview</h2>
                        <p class="text-gray-600">Selamat datang di sistem perpustakaan Bina Patria</p>
                    </div>

                    <!-- Sample cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="card p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="handwriting text-xl font-bold">Total Buku</h3>
                                    <p class="text-3xl font-bold text-blue-600">1,234</p>
                                </div>
                                <i class="fas fa-book text-3xl text-blue-500"></i>
                            </div>
                        </div>

                        <div class="card p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="handwriting text-xl font-bold">Anggota Aktif</h3>
                                    <p class="text-3xl font-bold text-green-600">567</p>
                                </div>
                                <i class="fas fa-users text-3xl text-green-500"></i>
                            </div>
                        </div>

                        <div class="card p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="handwriting text-xl font-bold">Dipinjam</h3>
                                    <p class="text-3xl font-bold text-orange-600">89</p>
                                </div>
                                <i class="fas fa-handshake text-3xl text-orange-500"></i>
                            </div>
                        </div>

                        <div class="card p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="handwriting text-xl font-bold">Terlambat</h3>
                                    <p class="text-3xl font-bold text-red-600">12</p>
                                </div>
                                <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Content placeholder -->
                    <div class="card p-6 rounded-lg">
                        <h3 class="handwriting text-2xl font-bold mb-4">Recent Activities</h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-book-open mr-3 text-blue-500"></i>
                                <div>
                                    <p class="font-semibold">Buku "Harry Potter" dipinjam oleh John Doe</p>
                                    <p class="text-sm text-gray-500">2 jam yang lalu</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-user-plus mr-3 text-green-500"></i>
                                <div>
                                    <p class="font-semibold">Anggota baru: Jane Smith terdaftar</p>
                                    <p class="text-sm text-gray-500">5 jam yang lalu</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-undo mr-3 text-purple-500"></i>
                                <div>
                                    <p class="font-semibold">Buku "The Great Gatsby" dikembalikan</p>
                                    <p class="text-sm text-gray-500">1 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer p-4 mt-auto">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                <div class="handwriting text-lg">
                    © 2024 Perpus Bina Patria. Semua hak dilindungi.
                </div>
                <div class="flex space-x-4 mt-2 md:mt-0">
                    <a href="#" class="hover:text-black transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-black transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-black transition-colors">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('close-sidebar');
        const mobileBackdrop = document.getElementById('mobile-backdrop');

        function openSidebar() {
            sidebar.classList.add('open');
            mobileBackdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFunc() {
            sidebar.classList.remove('open');
            mobileBackdrop.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        mobileMenuBtn.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebarFunc);
        mobileBackdrop.addEventListener('click', closeSidebarFunc);

        // Sidebar item active state
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove active class from all items
                document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
                
                // Close mobile sidebar after selection
                if (window.innerWidth < 768) {
                    closeSidebarFunc();
                }
            });
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeSidebarFunc();
            }
        });
    </script>
</body>

</html>