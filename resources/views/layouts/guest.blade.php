<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect - @yield('titre', 'Bienvenue')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#062B45',
                        ink: '#102A43',
                        bluegray: '#425E7B',
                        yellow: '#FDD400',
                        soft: '#F6F8FA',
                        line: '#DDE5EA',
                        teal: '#FDD400',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; letter-spacing: 0; }
        body { background: #EDEFF2; color: #102A43; }
        .auth-shell {
            background: #ffffff;
            border: 1px solid #DDE5EA;
            box-shadow: 0 26px 70px rgba(6, 43, 69, .12);
        }
        .auth-image {
            background-image:
                linear-gradient(90deg, rgba(6,43,69,.94), rgba(6,43,69,.70)),
                url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1200&q=85');
            background-size: cover;
            background-position: center;
        }
        .input-dark {
            background: #ffffff;
            border: 1px solid #DDE5EA;
            color: #102A43;
            transition: all .2s ease;
        }
        .input-dark::placeholder { color: #9AA8B5; }
        .input-dark:focus {
            outline: none;
            border-color: #062B45;
            box-shadow: 0 0 0 3px rgba(6,43,69,.10);
        }
        .btn-primary {
            background: #FDD400;
            color: #102A43 !important;
            transition: all .2s ease;
        }
        .btn-primary:hover { background: #E5BE00; transform: translateY(-1px); }
        .btn-yellow {
            background: #FDD400;
            color: #102A43 !important;
            border: 1px solid #E5BE00;
            transition: all .2s ease;
        }
        .btn-yellow:hover { background: #F4C900; transform: translateY(-1px); }
        .btn-light {
            background: #ffffff;
            color: #062B45;
            border: 1px solid #DDE5EA;
            transition: all .2s ease;
        }
        .btn-light:hover { border-color: #062B45; }
        .role-option span {
            border: 1px solid #DDE5EA;
            color: #425E7B;
            background: #ffffff;
        }
        .role-option input:checked + span {
            background: #FDD400;
            color: #102A43;
            border-color: #E5BE00;
        }
    </style>
</head>
<body>
    <main class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="auth-shell w-full max-w-6xl rounded overflow-hidden grid lg:grid-cols-[1fr_470px] min-h-[640px]">
            <section class="auth-image hidden lg:flex flex-col justify-between p-10 text-white">
                <div class="flex items-center justify-between">
                    <a href="{{ route('accueil') }}" class="font-extrabold text-xl">
                        Stage<span class="text-yellow">Connect</span>
                    </a>
                    <a href="{{ route('accueil') }}" class="text-sm text-white/75 hover:text-white transition">
                        Retour au site
                    </a>
                </div>

                <div class="max-w-lg">
                    <span class="inline-flex bg-yellow text-ink text-xs font-extrabold px-3 py-1 rounded mb-5">
                        Gestion des stages
                    </span>
                    <h1 class="text-4xl font-extrabold leading-tight">
                        Connectez les étudiants, les entreprises et les encadrants.
                    </h1>
                    <p class="text-white/75 leading-relaxed mt-5">
                        Un espace clair pour suivre les offres, les candidatures, les conventions et les stages académiques.
                    </p>
                </div>
            </section>

            <section class="bg-white p-6 sm:p-10 flex flex-col justify-center">
                <div class="lg:hidden flex items-center justify-between mb-8">
                    <a href="{{ route('accueil') }}" class="font-extrabold text-xl text-navy">
                        Stage<span class="text-teal">Connect</span>
                    </a>
                    <a href="{{ route('accueil') }}" class="text-sm font-semibold text-bluegray">Accueil</a>
                </div>

                <div class="mb-8">
                    @yield('nav-action')
                </div>

                @yield('contenu')
            </section>
        </div>
    </main>
</body>
</html>
