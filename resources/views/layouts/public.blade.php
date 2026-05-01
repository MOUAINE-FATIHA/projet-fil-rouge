<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect - @yield('titre', 'Plateforme de stages')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; letter-spacing: 0; }
        body { background: #F7F9FA; color: #1B3B59; }
        .card-dark, .card {
            background: #ffffff;
            border: 1px solid #DDE5EA;
            box-shadow: 0 14px 34px rgba(27,59,89,0.06);
        }
        .btn-primary {
            background: #FDD400;
            color: #031E31 !important;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: #E5BE00;
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(253,212,0,0.25);
        }
        .btn-outline {
            border: 1px solid #C9D8E3;
            color: #1B3B59;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-outline:hover { border-color: #FDD400; color: #031E31; }
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
        .badge-blue { background: #EAF1F6; color: #425E7B; border: 1px solid #C9D8E3; }
        .badge-gray { background: #F1F4F6; color: #708090; border: 1px solid #DDE5EA; }
        .text-white { color: #1B3B59 !important; }
        [class~="text-white/70"], [class~="text-white/60"], [class~="text-white/50"] { color: #425E7B !important; }
        [class~="text-white/40"], [class~="text-white/30"], [class~="text-white/20"] { color: #708090 !important; }
        [class~="border-white/5"], [class~="border-white/10"] { border-color: #DDE5EA !important; }
        .site-footer,
        .site-footer .text-white { color: #ffffff !important; }
        .site-footer [class~="text-white/70"] { color: rgba(255,255,255,0.70) !important; }
        .site-footer [class~="text-white/60"] { color: rgba(255,255,255,0.60) !important; }
        .site-footer [class~="border-white/10"] { border-color: rgba(255,255,255,0.10) !important; }
        .site-footer .footer-cta { color: #062B45 !important; }
        .site-footer .footer-cta-button { color: #ffffff !important; }
    </style>
</head>
<body>
    <nav class="bg-white/95 backdrop-blur border-b border-line sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between">
            <a href="{{ route('accueil') }}" class="font-extrabold text-xl text-ink">
                Stage<span class="text-teal">Connect</span>
            </a>

            <div class="hidden md:flex items-center gap-7">
                <a href="{{ route('accueil') }}" class="text-sm font-semibold text-primary hover:text-teal transition">Accueil</a>
                <a href="{{ route('offres.index') }}" class="text-sm font-semibold text-primary hover:text-teal transition">Offres de stage</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm font-semibold px-5 py-2.5 rounded-lg">Mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex btn-outline text-sm font-semibold px-5 py-2.5 rounded-lg">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm font-semibold px-5 py-2.5 rounded-lg">S'inscrire</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-5 py-10">
        @yield('contenu')
    </main>

    <footer class="site-footer bg-[#031E31] text-white px-5 pt-12 pb-7">
        <div class="max-w-6xl mx-auto">
            <div class="footer-cta bg-[#FDD400] rounded p-5 mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-xl">StageConnect</h2>
                    <p class="text-sm mt-1">Une plateforme simple pour gérer les stages académiques.</p>
                </div>
                <a href="{{ route('offres.index') }}" class="footer-cta-button bg-[#062B45] text-white font-bold px-5 py-2.5 rounded text-sm">
                    Explorer les offres
                </a>
            </div>

            <div class="grid md:grid-cols-[1.4fr_1fr_1fr_1fr] gap-8 pb-9 border-b border-white/10">
                <div>
                    <p class="font-extrabold text-2xl">Stage<span class="text-[#FDD400]">Connect</span></p>
                    <p class="text-sm text-white/70 leading-relaxed mt-3 max-w-sm">
                        Projet Laravel de gestion des stages, conçu pour centraliser les offres, candidatures, conventions et suivis.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Navigation</h3>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        <li><a href="{{ route('accueil') }}#fonctionnement" class="hover:text-[#FDD400]">Fonctionnement</a></li>
                        <li><a href="{{ route('accueil') }}#espaces" class="hover:text-[#FDD400]">Espaces</a></li>
                        <li><a href="{{ route('offres.index') }}" class="hover:text-[#FDD400]">Offres</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Rôles</h3>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        <li>Stagiaire</li>
                        <li>Entreprise</li>
                        <li>Encadrant</li>
                        <li>Admin</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Projet</h3>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        <li>YouCode Safi</li>
                        <li>Laravel</li>
                        <li>PostgreSQL</li>
                        <li>Docker</li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pt-6 text-sm text-white/60">
                <p>© 2026 StageConnect. Tous droits réservés.</p>
                <p>Plateforme de gestion des stages académiques.</p>
            </div>
        </div>
    </footer>
</body>
</html>
