<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Toko Jersey</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soccer-green': '#113F67',
                        'soccer-dark': '#EB5B00',
                        'soccer-accent': '#FEA405',
                        'warm-gray': '#f5f5f4',
                        'club-navy': '#0A2146',
                        'club-maroon': '#7A0026',
                        'field-green': '#1B5E20'
                    }
                }
            }
        }
    </script>
    <!-- Flowbite (Tailwind UI components) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.1/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-field-green/5 to-club-navy/10 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-center space-x-3">
                <i class="fas fa-futbol text-3xl text-field-green"></i>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Admin Toko Jersey</h1>
                    <p class="text-sm text-gray-600">Manajemen pesanan dan pencatatan transaksi</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="container mx-auto px-4 py-8 max-w-4xl">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">© 2025 Admin Toko Jersey. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.1/flowbite.min.js"></script>
</body>
</html>
