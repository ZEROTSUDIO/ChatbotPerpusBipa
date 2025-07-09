<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Dashboard'; ?> - Perpus Bipa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/main2.css" rel="stylesheet">
    <style>
        .handwriting { font-family: 'Patrick Hand', cursive; }
        .sidebar { position: fixed; top: 0; left: 0; width: 250px; height: 100vh; background: white; border-right: 2px solid black; z-index: 40; transition: transform 0.3s ease; }
        .sidebar.hidden { transform: translateX(-100%); }
        .main-wrapper { margin-left: 250px; transition: margin-left 0.3s ease; }
        .main-wrapper.full { margin-left: 0; }
        .sidebar-toggle { position: fixed; top: 20px; right: 0; background: white; border: 2px solid black; padding: 10px; border-radius: 5px; display: none; }
        .card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card.green { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .card.orange { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .card.blue { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; }
        .table-wrapper { background: white; border: 2px solid black; border-radius: 10px; overflow: hidden; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-online { background: #dcfce7; color: #166534; }
        .status-offline { background: #fee2e2; color: #991b1b; }        
        #myPaginationId button {
            transition: background-color 0.2s;
        }
        #myPaginationId button:hover {
            background-color: #eee;
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">