<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Restoran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 text-white p-6 shadow-md">
            <h1 class="text-3xl font-bold text-center">🍴 Sistem Informasi Restoran</h1>
        </header>

        <!-- Navbar -->
        <nav class="bg-gray-700 text-white py-3 shadow">
            <div class="flex justify-center space-x-6">
                <a href="/TP7DPBO/view/menus.php" class="hover:underline font-semibold">📋 Data Menu</a>
                <a href="/TP7DPBO/view/users.php" class="hover:underline font-semibold">👤 Data User</a>
                <a href="/TP7DPBO/view/orders.php" class="hover:underline font-semibold">🧾 Data Order</a>
                <a href="/TP7DPBO/view/order_items.php" class="hover:underline font-semibold">🍽️ Item Order</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
