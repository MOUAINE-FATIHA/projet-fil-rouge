<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect – @yield('titre', 'Bienvenue')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    },
                    fontFamily: {
                        display: ['Clash Display', 'sans-serif'],
                        body:    ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .glow {
            box-shadow: 0 0 40px rgba(37,99,235,0.25);
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
        .input-dark::placeholder { color: rgba(255,255,255,0.3); }
        .input-dark:focus {
            outline: none;
            border-color: #2563EB;
            background: rgba(37,99,235,0.08);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>

<body class="bg-dark min-h-screen dot-grid">

    {{-- Lueur d'ambiance --}}
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-96 h-96 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>

    {{-- Navbar --}}
    <nav class="glass border-b border-white/5 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
        <a href="{{ route('accueil') }}" class="text-white font-bold text-xl tracking-tight">
            Stage<span class="text-primary">Connect</span>
        </a>
        <div class="flex items-center gap-4">
            @yield('nav-action')
        </div>
    </nav>

    {{-- Contenu --}}
    <main class="flex items-center justify-center py-16 px-4 min-h-[calc(100vh-73px)]">
        @yield('contenu')
    </main>

</body>
</html>