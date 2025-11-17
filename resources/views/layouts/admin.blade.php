<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Jersey Family Order')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.1/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        /* Tailwind @apply does not work with the CDN; use plain CSS instead */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem; /* px-4 py-2 */
            font-size: 0.875rem; /* text-sm */
            border-radius: 0.5rem; /* rounded-lg */
            color: #D1D5DB; /* text-gray-300 */
            transition: color .2s ease, background-color .2s ease; /* transition-colors */
        }
        .sidebar-link:hover { color: #FFFFFF; background-color: rgba(255,255,255,0.10); }
        .sidebar-link.active { color: #FFFFFF; background-color: rgba(255,255,255,0.15); }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.625rem; /* px-2.5 py-0.5 */
            border-radius: 9999px; /* rounded-full */
            font-size: 0.75rem; /* text-xs */
            font-weight: 500; /* font-medium */
        }

        .card {
            background: #FFFFFF; /* bg-white */
            border-radius: 1rem; /* rounded-2xl */
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1); /* shadow-xl */
        }
        .card-header {
            padding: 1rem 1.5rem; /* px-6 py-4 */
            border-bottom: 1px solid #E5E7EB; /* border-b */
            background: #F9FAFB; /* bg-gray-50 */
            border-top-left-radius: 1rem; /* rounded-t-2xl */
            border-top-right-radius: 1rem;
        }
        .card-body { padding: 1.5rem; /* p-6 */ }
    </style>
</head>
<body class="bg-gradient-to-br from-field-green/5 to-club-navy/10 min-h-screen">
<div id="appShell" class="min-h-screen flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-72 bg-club-navy text-gray-200 flex-shrink-0 overflow-hidden">
        <div class="h-16 flex items-center px-6 border-b border-white/10">
            <i class="fas fa-futbol text-2xl text-soccer-accent mr-3"></i>
            <div>
                <div class="text-white font-bold leading-tight">Jersey Admin</div>
                <div class="text-xs text-gray-400">Order Management</div>
            </div>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line w-5 mr-3"></i> Dashboard
            </a>
            <a href="{{ route('pesanan.list') }}" class="sidebar-link {{ request()->routeIs('pesanan.list') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt w-5 mr-3"></i> Daftar Pesanan
            </a>
            <a href="{{ route('pesanan.index') }}" class="sidebar-link {{ request()->routeIs('pesanan.index') ? 'active' : '' }}">
                <i class="fa-solid fa-plus w-5 mr-3"></i> Tambah Pesanan
            </a>
        </nav>
        <div class="mt-auto p-4 text-xs text-gray-400">© 2025 Jersey Family</div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-b sticky top-0 z-40">
            <div class="h-full flex items-center justify-between px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="inline-flex items-center justify-center w-10 h-10 rounded-lg border hover:bg-gray-50">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="hidden lg:block">
                        <div class="text-sm text-gray-500">Admin Panel</div>
                        <div class="font-semibold text-gray-800">@yield('page-title', 'Dashboard')</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @yield('header-actions')
                    <div class="relative">
                        <button id="userMenuBtn" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border hover:bg-gray-50">
                            <i class="fa-regular fa-user"></i>
                            <span class="text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border py-1">
                            <div class="px-3 py-2 text-xs text-gray-500">Signed in as {{ auth()->user()->email ?? 'admin' }}</div>
                            <a class="block px-3 py-2 text-sm hover:bg-gray-50" href="{{ route('dashboard') }}">
                                <i class="fa-solid fa-house mr-2 text-gray-500"></i> Dashboard
                            </a>
                            <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left block px-3 py-2 text-sm hover:bg-gray-50">
                                    <i class="fa-solid fa-right-from-bracket mr-2 text-gray-500"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 lg:p-6">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.1/flowbite.min.js"></script>
<script>
    const root = document.getElementById('appShell');
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const userBtn = document.getElementById('userMenuBtn');
    const userMenu = document.getElementById('userMenu');
    const STORAGE_KEY = 'admin.sidebar.collapsed';

    // Inject CSS for sliding sidebar
    (function addStyles(){
        const style = document.createElement('style');
        style.innerHTML = `
            #sidebar { width: 18rem; transition: width .2s ease; }
            #appShell.sidebar-collapsed #sidebar { width: 0 !important; }
        `;
        document.head.appendChild(style);
    })();

    function applyInitialSidebarState() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            const collapsed = saved === '1' || (!saved && window.innerWidth < 1024);
            root.classList.toggle('sidebar-collapsed', collapsed);
        } catch(e) {}
    }
    applyInitialSidebarState();

    if (toggle) {
        toggle.addEventListener('click', () => {
            const collapsed = !root.classList.contains('sidebar-collapsed');
            root.classList.toggle('sidebar-collapsed', collapsed);
            try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch(e) {}
        });
    }

    if (userBtn) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', () => userMenu.classList.add('hidden'));
    }
</script>
@stack('scripts')
</body>
</html>
