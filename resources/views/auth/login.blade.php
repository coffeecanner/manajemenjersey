<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Admin Toko Jersey</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl p-8">
            <div class="flex items-center justify-center mb-6">
                <i class="fas fa-futbol text-3xl text-green-700 mr-2"></i>
                <h1 class="text-2xl font-bold text-gray-800">Admin Toko Jersey</h1>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-1">Masuk</h2>
            <p class="text-sm text-gray-500 mb-6">Kelola pesanan dan transaksi</p>

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-700 focus:outline-none">
                </div>
                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2">
                        Ingat saya
                    </label>
                    <a href="#" class="text-sm text-gray-400 cursor-not-allowed" title="Fitur belum tersedia">Lupa password?</a>
                </div>
                <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-xl font-semibold">
                    Masuk
                </button>
            </form>
        </div>
        <p class="text-center text-xs text-gray-500 mt-4">© 2025 Admin Toko Jersey</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" defer></script>
</body>
</html>

