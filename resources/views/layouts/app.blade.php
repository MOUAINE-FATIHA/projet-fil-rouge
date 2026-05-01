<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect - @yield('titre', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#1B3B59',
                        primary: '#425E7B',
                        teal: '#FDD400',
                        soft: '#F7F9FA',
                        line: '#DDE5EA',
                        saffron: '#FDD400',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; letter-spacing: 0; }
        body { background: #F4F7F9; color: #1B3B59; }

        .glass, .card-dark {
            background: #ffffff;
            border: 1px solid #DDE5EA;
            box-shadow: 0 12px 28px rgba(27, 59, 89, 0.06);
        }
        .card-dark.rounded-2xl,
        .glass.rounded-2xl,
        .panel-dark.rounded-2xl,
        .rounded-2xl {
            border-radius: 12px !important;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: rgba(255,255,255,0.72);
            transition: all 0.2s ease;
            text-decoration: none;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover { color: #ffffff; background: rgba(255,255,255,0.08); }
        .sidebar-link.active {
            background: rgba(255,255,255,0.96);
            color: #1B3B59;
            border-left-color: #FDD400;
            box-shadow: 0 8px 18px rgba(0,0,0,0.10);
        }

        .btn-primary {
            background: #FDD400;
            color: #031E31 !important;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #E5BE00;
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(253, 212, 0, 0.25);
        }
        .btn-outline {
            border: 1px solid #C9D8E3;
            color: #1B3B59;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-outline:hover { border-color: #FDD400; color: #031E31; }
        .btn-yellow {
            background: #FDD400;
            color: #031E31 !important;
            transition: all 0.2s ease;
        }
        .btn-yellow:hover {
            background: #F4C900;
            transform: translateY(-1px);
        }

        .input-dark {
            background: #ffffff;
            border: 1px solid #D4DEE5;
            color: #1B3B59;
            transition: all 0.2s ease;
        }
        .input-dark::placeholder { color: #8B9BA8; }
        .input-dark:focus {
            outline: none;
            border-color: #425E7B;
            box-shadow: 0 0 0 3px rgba(66,94,123,0.12);
        }
        select.input-dark option { background: #ffffff; color: #1B3B59; }

        .badge-pending  { background: #FFF7D6; color: #7A5B00; border: 1px solid #F4D35E; }
        .badge-accepted { background: #E8F6EF; color: #166344; border: 1px solid #BFE5D0; }
        .badge-rejected { background: #FDECEC; color: #9B2C2C; border: 1px solid #F5C2C2; }
        .badge-blue     { background: #EAF1F6; color: #425E7B; border: 1px solid #C9D8E3; }
        .badge-gray     { background: #F1F4F6; color: #708090; border: 1px solid #DDE5EA; }
        .badge-green    { background: #E8F6EF; color: #166344; border: 1px solid #BFE5D0; }

        .text-white { color: #1B3B59 !important; }
        [class~="text-white/70"], [class~="text-white/60"], [class~="text-white/50"] { color: #425E7B !important; }
        [class~="text-white/40"], [class~="text-white/30"], [class~="text-white/20"] { color: #708090 !important; }
        [class~="border-white/5"], [class~="border-white/10"] { border-color: #DDE5EA !important; }
        [class~="bg-dark"], [class~="bg-navy"], [class~="bg-card"] { background-color: #F7F9FA !important; }

        .app-shell {
            background:
                linear-gradient(180deg, rgba(253,212,0,0.08) 0, rgba(253,212,0,0) 230px),
                #F4F7F9;
        }
        .app-sidebar {
            background: #031E31;
            box-shadow: 10px 0 30px rgba(3, 30, 49, 0.10);
        }
        .app-sidebar .text-white,
        .app-sidebar [class~="text-white/70"],
        .app-sidebar [class~="text-white/60"],
        .app-sidebar [class~="text-white/50"],
        .app-sidebar [class~="text-white/40"],
        .app-sidebar [class~="text-white/30"] { color: #ffffff !important; }
        .app-sidebar [class~="text-white/40"] { opacity: 0.62; }
        .brand-mark { color: #FDD400 !important; }
        .topbar {
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(10px);
        }
        .page-kicker {
            color: #FDD400;
            font-weight: 800;
            letter-spacing: 0;
        }
        .soft-box {
            background: #F7F9FA;
            border: 1px solid #DDE5EA;
        }
        .btn-primary, .btn-primary.text-white,
        .bg-teal.text-white { color: #031E31 !important; }
        .bg-red-500.text-white { color: #ffffff !important; }
        .app-sidebar .sidebar-link.active { color: #1B3B59 !important; }
        .panel-dark { background: #031E31; }
        .panel-dark .text-white,
        .panel-dark [class~="text-white/70"],
        .panel-dark [class~="text-white/60"],
        .panel-dark [class~="text-white/50"],
        .panel-dark [class~="text-white/40"],
        .panel-dark [class~="text-white/30"] { color: #ffffff !important; }
        .panel-dark [class~="text-white/70"] { opacity: 0.72; }
    </style>
</head>
<body class="app-shell min-h-screen">

    <nav class="topbar border-b border-line px-6 py-3 flex items-center justify-between fixed w-full top-0 z-30">
        <a href="{{ route('accueil') }}" class="font-bold text-lg tracking-tight text-ink">
            Stage<span class="brand-mark">Connect</span>
        </a>

        <div class="flex items-center gap-5">
            @yield('nav-links')

            <div class="relative"
                 x-data="{
                    ouvert: false,
                    nb: {{ auth()->user()->unreadNotifications()->count() }},
                    notifications: [],
                    charger() {
                        fetch('{{ route('notifications.liste') }}')
                            .then(reponse => reponse.json())
                            .then(data => {
                                this.nb = data.non_lues;
                                this.notifications = data.notifications;
                            });
                    }
                 }"
                 x-init="charger(); setInterval(() => charger(), 20000)">
                <button @click="ouvert = !ouvert; charger()"
                        class="relative text-primary hover:text-teal transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-show="nb > 0"
                          x-text="nb > 9 ? '9+' : nb"
                          class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold"></span>
                </button>

                <div x-show="ouvert" @click.outside="ouvert = false"
                     class="absolute right-0 top-10 w-80 rounded-xl shadow-2xl z-50 overflow-hidden bg-white border border-line"
                     style="display:none;">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-line">
                        <p class="font-semibold text-ink text-sm">Notifications</p>
                        <form method="POST" action="{{ route('notifications.marquer-lues') }}" x-show="nb > 0">
                            @csrf
                            <button type="submit" class="text-xs text-primary hover:text-teal transition">
                                Tout marquer lu
                            </button>
                        </form>
                    </div>

                    <div class="max-h-72 overflow-y-auto">
                        <template x-for="notification in notifications" :key="notification.id">
                            <a :href="notification.url"
                               class="block px-4 py-3 border-b border-line hover:bg-soft transition"
                               :class="notification.lue ? 'opacity-60' : ''">
                                <p class="text-sm font-semibold text-ink" x-text="notification.titre"></p>
                                <p class="text-xs text-slate-500 mt-0.5" x-text="notification.message"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="notification.date"></p>
                            </a>
                        </template>
                        <div x-show="notifications.length === 0" class="px-4 py-6 text-center text-slate-500 text-sm">
                            Aucune notification
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-8 h-8 rounded-full bg-teal flex items-center justify-center text-white text-sm font-bold ring-2 ring-[#FDD400]/40">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </nav>

    <div class="flex pt-14 min-h-screen">
        <aside class="app-sidebar w-60 border-r border-white/10 fixed top-14 bottom-0 flex flex-col py-6 px-4">
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-10 h-10 rounded-lg bg-white/15 border border-white/10 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-white text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/40 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>

            <nav class="flex flex-col gap-1 flex-1">
                @yield('sidebar-links')
            </nav>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }} mb-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A8.966 8.966 0 0112 15c2.21 0 4.232.8 5.879 2.129M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Mon profil
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Déconnexion
                </button>
            </form>
        </aside>

        <main class="ml-60 flex-1 p-8">
            @if(session('succes'))
                <div class="mb-6 badge-accepted px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('succes') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 badge-rejected px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('contenu')
        </main>
    </div>
</body>
</html>
