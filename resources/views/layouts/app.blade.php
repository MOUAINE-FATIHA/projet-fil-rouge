<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect – @yield('titre', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark:    '#0A0F1E',
                        navy:    '#0D1B2A',
                        card:    '#111827',
                        primary: '#2563EB',
                        gold:    '#F59E0B',
                        muted:   '#6B7280',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }

        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .card-dark {
            background: #111827;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,0.5);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .sidebar-link:hover {
            color: white;
            background: rgba(255,255,255,0.06);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #2563EB22, #2563EB11);
            color: #60a5fa;
            border: 1px solid rgba(37,99,235,0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563EB, #1d4ed8);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(37,99,235,0.4);
        }

        .input-dark {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-dark::placeholder { color: rgba(255,255,255,0.25); }
        .input-dark:focus {
            outline: none;
            border-color: #2563EB;
            background: rgba(37,99,235,0.08);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        select.input-dark option { background: #111827; color: white; }

        .badge-pending  { background: rgba(245,158,11,0.15);  color: #F59E0B;  border: 1px solid rgba(245,158,11,0.3); }
        .badge-accepted { background: rgba(16,185,129,0.15);  color: #10B981;  border: 1px solid rgba(16,185,129,0.3); }
        .badge-rejected { background: rgba(239,68,68,0.15);   color: #EF4444;  border: 1px solid rgba(239,68,68,0.3); }
        .badge-blue     { background: rgba(37,99,235,0.15);   color: #60a5fa;  border: 1px solid rgba(37,99,235,0.3); }
        .badge-gray     { background: rgba(107,114,128,0.15); color: #9CA3AF;  border: 1px solid rgba(107,114,128,0.3); }
        .badge-green    { background: rgba(16,185,129,0.15);  color: #10B981;  border: 1px solid rgba(16,185,129,0.3); }
    </style>
</head>
<body class="bg-dark min-h-screen text-white">

    {{-- Navbar fixe --}}
    <nav class="glass border-b border-white/5 px-6 py-3 flex items-center justify-between fixed w-full top-0 z-30">
        <a href="{{ route('accueil') }}" class="text-white font-bold text-lg tracking-tight">
            Stage<span class="text-primary">Connect</span>
        </a>
        <div class="flex items-center gap-5">
            @yield('nav-links')
            <button class="text-white/40 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </nav>

    <div class="flex pt-14 min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-60 border-r border-white/5 fixed top-14 bottom-0 flex flex-col py-6 px-4"
               style="background: #0D1B2A;">

            {{-- Profil --}}
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-white text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/40 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex flex-col gap-1 flex-1">
                @yield('sidebar-links')
            </nav>

            {{-- Déconnexion --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/40 hover:text-red-400 hover:bg-red-400/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Déconnexion
                </button>
            </form>
        </aside>

        {{-- Contenu principal --}}
        <main class="ml-60 flex-1 p-8">

            @if(session('succes'))
                <div class="mb-6 badge-accepted px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('succes') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 badge-rejected px-4 py-3 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('contenu')
        </main>
    </div>
    {{-- Cloche notifications --}}
<div class="relative" x-data="{ ouvert: false }">
    <button @click="ouvert = !ouvert"
            class="relative text-white/40 hover:text-white transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @php $nb = auth()->user()->unreadNotifications->count(); @endphp
        @if($nb > 0)
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold">
                {{ $nb > 9 ? '9+' : $nb }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="ouvert" @click.outside="ouvert = false"
         class="absolute right-0 top-10 w-80 rounded-2xl shadow-2xl z-50 overflow-hidden"
         style="background:#111827; border:1px solid rgba(255,255,255,0.08);">

        <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
            <p class="font-semibold text-white text-sm">Notifications</p>
            @if($nb > 0)
                <form method="POST" action="{{ route('notifications.marquer-lues') }}">
                    @csrf
                    <button type="submit" class="text-xs text-white/30 hover:text-white transition">
                        Tout marquer lu
                    </button>
                </form>
            @endif
        </div>

        <div class="max-h-72 overflow-y-auto">
            @forelse(auth()->user()->notifications->take(8) as $notif)
                <div class="px-4 py-3 border-b border-white/5 hover:bg-white/5 transition
                            {{ $notif->read_at ? 'opacity-50' : '' }}">
                    <p class="text-sm font-semibold text-white">{{ $notif->data['titre'] ?? '' }}</p>
                    <p class="text-xs text-white/40 mt-0.5">{{ $notif->data['message'] ?? '' }}</p>
                    <p class="text-xs text-white/20 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="px-4 py-6 text-center text-white/30 text-sm">
                    Aucune notification
                </div>
            @endforelse
        </div>
    </div>
</div>

</body>
</html>