<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'ChatBot BIPA' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#6B7280'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">ChatBot BIPA</h1>
                    </div>
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="<?= base_url() ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="<?= base_url('intent-analytics') ?>" class="text-gray-900 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Intent Analytics
                        </a>
                        <a href="<?= base_url('chat') ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Chat History
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        <?= date('Y-m-d H:i:s') ?>
                    </div>
                    <div class="relative">
                        <button class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user mr-2"></i>Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>