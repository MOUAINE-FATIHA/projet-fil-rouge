<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect — Plateforme de stages académiques</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark:    '#0A0F1E',
                        navy:    '#0D1B2A',
                        primary: '#2563EB',
                        gold:    '#F59E0B',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563EB, #1d4ed8);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(37,99,235,0.4);
        }

        .btn-outline {
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            border-color: #2563EB;
            background: rgba(37,99,235,0.1);
        }

        .hero-bg {
            background: linear-gradient(135deg, #0A0F1E 0%, #0D1B2A 50%, #0A0F1E 100%);
        }

        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .card-hover {
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            border-color: rgba(37,99,235,0.3);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .stat-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .tag {
            background: rgba(37,99,235,0.15);
            color: #60a5fa;
            border: 1px solid rgba(37,99,235,0.3);
        }
    </style>
</head>
<body class="bg-dark text-white">

    {{-- ── Navbar ─────────────────────────────────────────────── --}}
    <nav class="glass border-b border-white/5 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
        <a href="{{ route('accueil') }}" class="font-bold text-xl">
            Stage<span class="text-primary">Connect</span>
        </a>
        <div class="flex items-center gap-8">
            <a href="{{ route('accueil') }}"      class="text-sm text-white/60 hover:text-white transition">Accueil</a>
            <a href="{{ route('offres.index') }}" class="text-sm text-white/60 hover:text-white transition">Offres</a>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-xl">
                    Mon espace
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="btn-outline text-white/70 text-sm font-medium px-5 py-2.5 rounded-xl">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                   class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-xl">
                    S'inscrire
                </a>
            @endauth
        </div>
    </nav>

    {{-- ── Hero ────────────────────────────────────────────────── --}}
    <section class="hero-bg dot-grid relative overflow-hidden py-24 px-8">
        {{-- Lueurs d'ambiance --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto relative">
            <div class="max-w-3xl">
                <span class="tag text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full inline-block mb-6">
                    YouCode Safi
                </span>
                <h1 class="text-5xl font-extrabold text-white leading-tight mb-6">
                    Trouvez votre stage<br>
                    <span class="text-primary">idéal</span> en quelques clics.
                </h1>
                <p class="text-white/50 text-lg leading-relaxed mb-10 max-w-xl">
                    StageConnect connecte les talents de YouCode Safi avec les meilleures entreprises du Maroc.
                    Postulez, suivez vos candidatures, décrochez votre stage.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('register') }}"
                       class="btn-primary text-white font-semibold px-8 py-3.5 rounded-xl text-sm">
                        Commencer maintenant
                    </a>
                    <a href="{{ route('offres.index') }}"
                       class="btn-outline text-white/70 font-medium px-8 py-3.5 rounded-xl text-sm">
                        Voir les offres
                    </a>
                </div>
            </div>

            {{-- Image Hero --}}
            <div class="absolute right-0 top-1/2 -translate-y-1/2 hidden lg:block">
                <div class="relative w-80 h-64">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600&q=80"
                         alt="Équipe au travail"
                         class="w-full h-full object-cover rounded-2xl opacity-60"
                         style="border: 1px solid rgba(255,255,255,0.1);">
                    {{-- Badge flottant --}}
                    <div class="absolute -bottom-4 -left-4 glass rounded-xl px-4 py-3">
                        <p class="text-xs text-white/40">Candidatures cette semaine</p>
                        <p class="text-2xl font-extrabold text-white">500+</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Stats ────────────────────────────────────────────────── --}}
    <section class="py-12 px-8 border-y border-white/5" style="background:#0D1B2A;">
        <div class="max-w-6xl mx-auto grid grid-cols-4 gap-6">
            @foreach([
                ['500+', 'Stages trouvés'],
                ['120+', 'Entreprises partenaires'],
                ['95%',  'Taux de satisfaction'],
                ['2ans', 'D\'expérience'],
            ] as [$val, $label])
                <div class="text-center">
                    <p class="text-3xl font-extrabold text-white">{{ $val }}</p>
                    <p class="text-sm text-white/40 mt-1">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ── Offres récentes ──────────────────────────────────────── --}}
    <section class="py-20 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <span class="tag text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full inline-block mb-3">
                        Opportunités
                    </span>
                    <h2 class="text-3xl font-extrabold text-white">Offres disponibles</h2>
                </div>
                <a href="{{ route('offres.index') }}" class="text-primary text-sm font-medium hover:text-blue-400 transition">
                    Voir tout
                </a>
            </div>

            <div class="grid grid-cols-3 gap-5">
                @foreach([
                    [
                        'titre'  => 'Développeur Full Stack',
                        'type'   => 'PFE',
                        'ville'  => 'Casablanca',
                        'duree'  => '4 mois',
                        'img'    => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=400&q=80',
                    ],
                    [
                        'titre'  => 'Designer UI/UX',
                        'type'   => 'PFA',
                        'ville'  => 'Rabat',
                        'duree'  => '3 mois',
                        'img'    => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&q=80',
                    ],
                    [
                        'titre'  => 'Data Analyst Junior',
                        'type'   => 'Stage été',
                        'ville'  => 'Safi',
                        'duree'  => '2 mois',
                        'img'    => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&q=80',
                    ],
                ] as $offre)
                    <div class="card-hover rounded-2xl overflow-hidden" style="background:#111827;">
                        <div class="h-40 overflow-hidden">
                            <img src="{{ $offre['img'] }}" alt="{{ $offre['titre'] }}"
                                 class="w-full h-full object-cover opacity-70 hover:opacity-90 transition duration-500 hover:scale-105">
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="tag text-xs font-medium px-2.5 py-1 rounded-lg">{{ $offre['type'] }}</span>
                            </div>
                            <h3 class="font-bold text-white text-base mb-2">{{ $offre['titre'] }}</h3>
                            <div class="flex items-center gap-3 text-xs text-white/40">
                                <span>{{ $offre['ville'] }}</span>
                                <span>{{ $offre['duree'] }}</span>
                            </div>
                            <a href="{{ route('offres.index') }}"
                               class="mt-4 block text-center btn-primary text-white text-sm font-semibold py-2.5 rounded-xl">
                                Voir l'offre
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Pour les étudiants ───────────────────────────────────── --}}
    <section class="py-20 px-8" style="background:#0D1B2A;">
        <div class="max-w-6xl mx-auto flex items-center gap-14">
            <div class="flex-1">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80"
                     alt="Étudiants"
                     class="w-full h-72 object-cover rounded-2xl opacity-80"
                     style="border: 1px solid rgba(255,255,255,0.08);">
            </div>
            <div class="flex-1">
                <span class="tag text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full inline-block mb-4">
                    Pour les Étudiants
                </span>
                <h2 class="text-3xl font-extrabold text-white mb-4">
                    Votre tremplin vers le monde professionnel
                </h2>
                <p class="text-white/40 text-sm leading-relaxed mb-6">
                    Accédez aux meilleures offres de stage, suivez vos candidatures en temps réel et décrochez votre premier emploi.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach([
                        'Accès à des offres exclusives de startups et grands groupes',
                        'Suivi de vos candidatures en temps réel',
                        'Interface simple et intuitive',
                    ] as $item)
                        <li class="flex items-center gap-3 text-sm text-white/60">
                            <span class="w-5 h-5 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}"
                   class="btn-primary inline-block text-white font-semibold px-7 py-3 rounded-xl text-sm">
                    Postuler maintenant
                </a>
            </div>
        </div>
    </section>

    {{-- ── Pour les entreprises ─────────────────────────────────── --}}
    <section class="py-20 px-8">
        <div class="max-w-6xl mx-auto flex items-center gap-14">
            <div class="flex-1">
                <span class="tag text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full inline-block mb-4">
                    Pour les Entreprises
                </span>
                <h2 class="text-3xl font-extrabold text-white mb-4">
                    Recrutez les meilleurs talents tech
                </h2>
                <p class="text-white/40 text-sm leading-relaxed mb-6">
                    Accédez aux profils techniques de YouCode Safi, publiez vos offres et gérez vos candidatures depuis un seul espace.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach([
                        'Profils qualifiés et prêts à l\'emploi',
                        'Gestion simplifiée des candidatures',
                        'Mise en relation rapide avec les étudiants',
                    ] as $item)
                        <li class="flex items-center gap-3 text-sm text-white/60">
                            <span class="w-5 h-5 rounded-full bg-gold/20 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-gold" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}"
                   class="btn-outline inline-block text-white/70 font-semibold px-7 py-3 rounded-xl text-sm">
                    Déposer une offre →
                </a>
            </div>
            <div class="flex-1">
                <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&q=80"
                     alt="Entreprise"
                     class="w-full h-72 object-cover rounded-2xl opacity-80"
                     style="border: 1px solid rgba(255,255,255,0.08);">
            </div>
        </div>
    </section>

    {{-- ── CTA Final ────────────────────────────────────────────── --}}
    <section class="py-20 px-8" style="background: linear-gradient(135deg, #0D1B2A, #0A0F1E);">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-white mb-4">Prêt à commencer ?</h2>
            <p class="text-white/40 mb-8">
                Rejoignez des centaines d'étudiants et d'entreprises qui font confiance à StageConnect.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="btn-primary text-white font-semibold px-8 py-3.5 rounded-xl text-sm">
                    Créer mon compte
                </a>
                <a href="{{ route('offres.index') }}"
                   class="btn-outline text-white/70 font-medium px-8 py-3.5 rounded-xl text-sm">
                    Explorer les offres
                </a>
            </div>
        </div>
    </section>

    {{-- ── Footer ───────────────────────────────────────────────── --}}
    <footer class="border-t border-white/5 px-8 py-12" style="background:#0D1B2A;">
        <div class="max-w-6xl mx-auto grid grid-cols-4 gap-8 mb-10">
            <div>
                <p class="font-bold text-white text-lg mb-3">Stage<span class="text-primary">Connect</span></p>
                <p class="text-white/30 text-sm leading-relaxed">
                    La plateforme de mise en relation entre YouCode Safi et le monde professionnel.
                </p>
            </div>
            <div>
                <p class="font-semibold text-white/60 text-xs uppercase tracking-widest mb-4">Plateforme</p>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('offres.index') }}" class="text-white/30 text-sm hover:text-white transition">Offres de stage</a></li>
                    <li><a href="{{ route('register') }}"     class="text-white/30 text-sm hover:text-white transition">S'inscrire</a></li>
                    <li><a href="{{ route('login') }}"        class="text-white/30 text-sm hover:text-white transition">Se connecter</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white/60 text-xs uppercase tracking-widest mb-4">Ressources</p>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-white/30 text-sm hover:text-white transition">Guide du stagiaire</a></li>
                    <li><a href="#" class="text-white/30 text-sm hover:text-white transition">Aide & Support</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white/60 text-xs uppercase tracking-widest mb-4">Légal</p>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-white/30 text-sm hover:text-white transition">Confidentialité</a></li>
                    <li><a href="#" class="text-white/30 text-sm hover:text-white transition">CGU</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-6xl mx-auto pt-6 border-t border-white/5 flex items-center justify-between">
            <p class="text-white/20 text-sm">© 2026 StageConnect — YouCode Safi</p>
            <p class="text-white/20 text-sm">Powered by Simplon</p>
        </div>
    </footer>

</body>
</html>