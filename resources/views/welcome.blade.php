<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageConnect - Plateforme de gestion des stages</title>
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
                        sand: '#C6AD93',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'DM Sans', sans-serif; letter-spacing: 0; }
        html { scroll-behavior: smooth; }
        body { background: #F6F8FA; color: #102A43; }
        .btn-yellow {
            background: #FDD400;
            color: #102A43;
            border: 1px solid #E5BE00;
            transition: all .2s ease;
        }
        .btn-yellow:hover { background: #F4C900; transform: translateY(-1px); }
        .btn-navy {
            background: #062B45;
            color: #ffffff;
            border: 1px solid #062B45;
            transition: all .2s ease;
        }
        .btn-navy:hover { background: #0A3858; transform: translateY(-1px); }
        .btn-light {
            background: #ffffff;
            color: #062B45;
            border: 1px solid #DDE5EA;
            transition: all .2s ease;
        }
        .btn-light:hover { border-color: #062B45; }
        .card {
            background: #ffffff;
            border: 1px solid #DDE5EA;
            box-shadow: 0 12px 30px rgba(16, 42, 67, .06);
        }
        .hero-overlay {
            background: linear-gradient(90deg, rgba(6,43,69,.92), rgba(6,43,69,.72), rgba(253,212,0,.42));
        }
        .section-navy { background: #062B45; color: #ffffff; }
        .section-footer-cta { background: #031E31; color: #ffffff; }
        .small-label {
            display: inline-flex;
            background: #FFF6BF;
            color: #062B45;
            border: 1px solid #FDD400;
            font-size: 12px;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 4px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <nav class="bg-white border-b border-line sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between">
            <a href="{{ route('accueil') }}" class="font-extrabold text-xl text-navy">
                Stage<span class="text-teal">Connect</span>
            </a>

            <div class="hidden lg:flex items-center gap-7">
                <a href="#accueil" class="text-sm font-semibold text-bluegray hover:text-navy">Accueil</a>
                <a href="#fonctionnement" class="text-sm font-semibold text-bluegray hover:text-navy">Fonctionnement</a>
                <a href="#espaces" class="text-sm font-semibold text-bluegray hover:text-navy">Espaces</a>
                <a href="{{ route('offres.index') }}" class="text-sm font-semibold text-bluegray hover:text-navy">Offres</a>
                <a href="#contact" class="text-sm font-semibold text-bluegray hover:text-navy">Contact</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-navy text-sm font-bold px-5 py-2.5 rounded">
                        Mon espace
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex btn-light text-sm font-bold px-5 py-2.5 rounded">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn-yellow text-sm font-bold px-5 py-2.5 rounded">
                        S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section id="accueil" class="relative min-h-[620px] flex items-center overflow-hidden">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600&q=85"
             alt="Étudiants en travail collaboratif"
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 hero-overlay"></div>

        <div class="relative max-w-6xl mx-auto px-5 py-24 w-full">
            <div class="max-w-2xl text-white">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                    Gérez les stages académiques dans un seul espace.
                </h1>
                <p class="text-white/85 text-lg leading-relaxed mt-5">
                    StageConnect facilite la relation entre stagiaires, entreprises, encadrants et administration, depuis l'offre jusqu'à la convention.
                </p>
                <div class="flex flex-wrap gap-3 mt-8">
                    <a href="{{ route('offres.index') }}" class="btn-yellow font-bold px-7 py-3 rounded">
                        Voir les offres
                    </a>
                    <a href="#fonctionnement" class="bg-white/10 text-white border border-white/30 font-bold px-7 py-3 rounded hover:bg-white/15 transition">
                        Découvrir
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="fonctionnement" class="py-20 px-5">
        <div class="max-w-6xl mx-auto grid lg:grid-cols-[1fr_420px] gap-12 items-center">
            <div>
                <h2 class="text-3xl font-extrabold text-navy mt-5">Une solution simple pour organiser le cycle de stage</h2>
                <p class="text-bluegray leading-relaxed mt-4">
                    Le projet remplace les échanges dispersés par un flux clair. L'étudiant postule, l'entreprise décide, l'admin organise et l'encadrant suit le stage.
                </p>

                <div class="grid sm:grid-cols-3 gap-4 mt-8">
                    @foreach([
                        ['title' => 'Candidature', 'text' => 'CV, lettre de motivation et statut de la demande.'],
                        ['title' => 'Convention', 'text' => 'PDF préparé, signé par l’entreprise puis validé.'],
                        ['title' => 'Suivi', 'text' => 'Stages assignés, documents et compte-rendu.'],
                    ] as $item)
                        <div class="card rounded p-5">
                            <h3 class="font-extrabold text-navy">{{ $item['title'] }}</h3>
                            <p class="text-sm text-bluegray leading-relaxed mt-3">{{ $item['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=900&q=85"
                     alt="Réunion autour d'un projet"
                     class="w-full h-[360px] object-cover rounded">
                <div class="absolute -bottom-8 left-8 right-8 bg-navy text-white rounded p-5 shadow-xl">
                    <p class="text-sm text-white/70">Objectif principal</p>
                    <p class="font-bold mt-1">Centraliser les informations importantes du stage.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-navy py-20 px-5">
        <div class="max-w-6xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-10">
                
                <h2 class="text-3xl font-extrabold mt-5">Comment StageConnect fonctionne ?</h2>
                <p class="text-white/70 mt-4">Un parcours logique, facile à suivre et adapté à une gestion académique.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-5">
                @foreach([
                    ['step' => '01', 'title' => 'Publier', 'text' => "L'entreprise publie une offre de stage après validation de son compte."],
                    ['step' => '02', 'title' => 'Postuler', 'text' => "Le stagiaire envoie sa candidature avec les documents nécessaires."],
                    ['step' => '03', 'title' => 'Accepter', 'text' => "L'entreprise accepte ou refuse la candidature."],
                    ['step' => '04', 'title' => 'Valider', 'text' => "L'encadrant vérifie la convention signée et valide le dossier."],
                ] as $item)
                    <div class="bg-white text-ink rounded p-6">
                        <span class="inline-flex bg-yellow text-navy text-xs font-extrabold px-3 py-1 rounded">{{ $item['step'] }}</span>
                        <h3 class="font-extrabold text-lg mt-5">{{ $item['title'] }}</h3>
                        <p class="text-sm text-bluegray leading-relaxed mt-3">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="espaces" class="bg-white py-20 px-5">
        <div class="max-w-6xl mx-auto">
            <div class="grid lg:grid-cols-[360px_1fr] gap-12 items-start">
                <div>
                    <h2 class="text-3xl font-extrabold text-navy mt-5">Des espaces adaptés à chaque rôle</h2>
                    <p class="text-bluegray leading-relaxed mt-4">
                        Chaque utilisateur voit les actions utiles pour lui, sans complexité.
                    </p>
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=700&q=85"
                         alt="Équipe de travail"
                         class="w-full h-64 object-cover rounded mt-8">
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    @foreach([
                        ['title' => 'Stagiaire', 'items' => ['Consulter les offres', 'Postuler', 'Suivre ses candidatures']],
                        ['title' => 'Entreprise', 'items' => ['Publier des offres', 'Traiter les candidatures', 'Déposer la convention signée']],
                        ['title' => 'Encadrant', 'items' => ['Voir ses stages', 'Télécharger la convention', 'Valider le document']],
                        ['title' => 'Admin', 'items' => ['Valider les comptes', 'Assigner les encadrants', 'Préparer la convention']],
                    ] as $role)
                        <div class="card rounded p-6">
                            <h3 class="font-extrabold text-xl text-navy">{{ $role['title'] }}</h3>
                            <ul class="mt-5 space-y-3">
                                @foreach($role['items'] as $item)
                                    <li class="flex items-start gap-3 text-sm text-bluegray">
                                        <span class="w-5 h-5 bg-yellow text-navy rounded flex items-center justify-center text-xs font-bold mt-0.5">✓</span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-5 bg-soft">
        <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=700&q=85"
                         alt="Discussion professionnelle"
                         class="w-full h-72 object-cover rounded">
                    <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?w=700&q=85"
                         alt="Réunion en entreprise"
                         class="w-full h-72 object-cover rounded mt-8">
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-3xl font-extrabold text-navy mt-5">Un document préparé, signé puis validé</h2>
                <p class="text-bluegray leading-relaxed mt-4">
                    L’administration prépare la convention avec les informations du stage. L’entreprise télécharge le PDF, le signe, puis redépose la version signée. L’encadrant vérifie et valide.
                </p>
                <div class="space-y-3 mt-7">
                    @foreach([
                        'PDF généré automatiquement',
                        'Dépôt sécurisé dans un stockage privé',
                        'Validation finale par l’encadrant',
                    ] as $item)
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded bg-yellow text-navy flex items-center justify-center font-bold">✓</span>
                            <p class="font-semibold text-ink">{{ $item }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-5 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-3xl font-extrabold text-navy mt-5">Ce que la plateforme améliore</h2>
                <p class="text-bluegray mt-4">Moins de confusion, plus de visibilité et une meilleure organisation du stage.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                @foreach([
                    ['title' => 'Informations centralisées', 'text' => 'Les offres, candidatures, conventions et stages sont regroupés.'],
                    ['title' => 'Interfaces moins vides', 'text' => 'Chaque espace affiche des messages et actions utiles.'],
                    ['title' => 'Suivi plus professionnel', 'text' => 'L’encadrant et l’admin gardent une trace claire des étapes.'],
                ] as $item)
                    <div class="card rounded p-6">
                        <h3 class="font-extrabold text-navy">{{ $item['title'] }}</h3>
                        <p class="text-sm text-bluegray leading-relaxed mt-3">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-footer-cta py-14 px-5">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h2 class="text-2xl font-extrabold">Prêt à commencer ?</h2>
                <p class="text-white/70 mt-2">Consultez les offres ouvertes ou créez un compte adapté à votre rôle.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('offres.index') }}" class="btn-yellow font-bold px-6 py-3 rounded">Voir les offres</a>
                <a href="{{ route('register') }}" class="bg-white text-navy font-bold px-6 py-3 rounded">Créer un compte</a>
            </div>
        </div>
    </section>

    <footer id="contact" class="bg-[#031E31] text-white px-5 pt-12 pb-7">
        <div class="max-w-6xl mx-auto">
            <div class="bg-yellow text-navy rounded p-5 mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-xl">StageConnect</h2>
                    <p class="text-sm mt-1">Une plateforme simple pour gérer les stages académiques.</p>
                </div>
                <a href="{{ route('offres.index') }}" class="bg-navy text-white font-bold px-5 py-2.5 rounded text-sm">
                    Explorer les offres
                </a>
            </div>

            <div class="grid md:grid-cols-[1.4fr_1fr_1fr_1fr] gap-8 pb-9 border-b border-white/10">
                <div>
                    <p class="font-extrabold text-2xl">Stage<span class="text-yellow">Connect</span></p>
                    <p class="text-sm text-white/70 leading-relaxed mt-3 max-w-sm">
                        Projet Laravel de gestion des stages, conçu pour centraliser les offres, candidatures, conventions et suivis.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold mb-4">Navigation</h3>
                    <ul class="space-y-2.5 text-sm text-white/70">
                        <li><a href="#fonctionnement" class="hover:text-yellow">Fonctionnement</a></li>
                        <li><a href="#espaces" class="hover:text-yellow">Espaces</a></li>
                        <li><a href="{{ route('offres.index') }}" class="hover:text-yellow">Offres</a></li>
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
