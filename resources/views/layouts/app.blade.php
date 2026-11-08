<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StudentHousing - Logements Étudiants Clés en Main')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex flex-col min-h-full text-slate-800 antialiased">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('properties.index') }}" class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                    <i class="fa-solid fa-graduation-cap text-lg"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">StudentHousing</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="{{ route('properties.index') }}" class="hover:text-indigo-600 transition">Explorer les logements</a>
                <a href="{{ route('bookings.index') }}" class="hover:text-indigo-600 transition">Mes Réservations</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('properties.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Rechercher</span>
                </a>

                @auth
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">
                            {{ Auth::user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-slate-500 hover:text-rose-600 font-medium transition" title="Se déconnecter">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="hidden sm:inline">Quitter</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                        <i class="fa-solid fa-user"></i>
                        <span>Connexion</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-white border-t border-slate-200 py-8 text-center text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} StudentHousing Platform. Propulsé par Laravel 11 & Elasticsearch.</p>
        </div>
    </footer>

</body>
</html>
