<!-- Mobile backdrop -->
<div id="mobile-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="p-4">
        <!-- Logo -->
        <div class="flex items-center mb-8">
            <div class="w-10 h-10 rounded-full mr-3 flex items-center justify-center border-2 border-black">
                <i class="fas fa-book text-lg"></i>
            </div>
            <h1 class="text-xl font-bold handwriting">Perpus Bipa</h1>
        </div>

        <!-- Navigation -->
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="<?php echo base_url('admin/dashboard'); ?>"
                        class="sidebar-item <?php echo $this->uri->segment(2) == 'dashboard' ? 'bg-blue-100 border-l-4 border-blue-500' : ''; ?> flex items-center px-3 py-3 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/users'); ?>"
                        class="sidebar-item <?php echo $this->uri->segment(2) == 'users' ? 'bg-blue-100 border-l-4 border-blue-500' : ''; ?> flex items-center px-3 py-3 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-users mr-3 text-lg"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/intents'); ?>"
                        class="sidebar-item <?php echo $this->uri->segment(2) == 'intents' ? 'bg-blue-100 border-l-4 border-blue-500' : ''; ?> flex items-center px-3 py-3 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-robot mr-3 text-lg"></i>
                        <span>Intent Responses</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('admin/chats'); ?>"
                        class="sidebar-item <?php echo $this->uri->segment(2) == 'chats' ? 'bg-blue-100 border-l-4 border-blue-500' : ''; ?> flex items-center px-3 py-3 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-comments mr-3 text-lg"></i>
                        <span>Chat Test</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Profile Section -->
        <div class="bg-gray-100 border-2 border-black rounded-lg p-4 mt-6 fixed bottom-4">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-lg mr-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p class="font-bold handwriting text-lg"><?= $user->nama; ?></p>
                    <p class="text-xs text-gray-500"><?= $user->email; ?></p>
                </div>
            </div>
            <div class="space-y-1">
                <!--a href="#" class="flex items-center px-2 py-1 text-sm hover:bg-gray-200 rounded">
                    <i class="fas fa-user-edit mr-2 text-xs"></i> Edit Profile
                </a>
                <a href="#" class="flex items-center px-2 py-1 text-sm hover:bg-gray-200 rounded">
                    <i class="fas fa-cog mr-2 text-xs"></i> Settings
                </a-->
                <a href="<?php echo base_url('admin/intents'); ?>" class="flex items-center px-2 py-1 text-sm hover:bg-gray-200 rounded text-red-600">
                    <i class="fas fa-sign-out-alt mr-2 text-xs"></i> Logout
                </a>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar Toggle Button -->
<button id="sidebar-toggle" class="sidebar-toggle">
    <i class="fas fa-bars"></i>
</button>