@extends(auth()->check() && auth()->user()->estStagiaire() ? 'layouts.app' : 'layouts.public')
@section('titre', 'Offres disponibles')

@section('sidebar-links')
    <a href="{{ route('offres.index') }}"
       class="sidebar-link {{ request()->routeIs('offres.index') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Offres de stage
    </a>
    <a href="{{ route('stagiaire.candidatures.index') }}"
       class="sidebar-link {{ request()->routeIs('stagiaire.candidatures.index') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Mes candidatures
    </a>
    <a href="{{ route('stagiaire.stages.index') }}"
       class="sidebar-link {{ request()->routeIs('stagiaire.stages.index') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Mes stages
    </a>
@endsection

@section('contenu')

    <div class="mb-8 card-dark rounded-2xl p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Opportunités</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Stages disponibles</h1>
                <p class="text-white/40 text-sm mt-1">
                    Consultez les offres ouvertes. La connexion sera demandée uniquement au moment de postuler.
                </p>
            </div>
            @guest
                <a href="{{ route('register') }}" class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-xl self-start md:self-auto">
                    Créer un compte
                </a>
            @endguest
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('offres.index') }}" class="flex flex-wrap gap-3 mb-6">
        <select name="type" class="input-dark px-4 py-2 rounded-xl text-sm">
            <option value="">Type ▾</option>
            <option value="pfe"    {{ request('type') === 'pfe'    ? 'selected' : '' }}>PFE</option>
            <option value="pfa"    {{ request('type') === 'pfa'    ? 'selected' : '' }}>PFA</option>
            <option value="summer" {{ request('type') === 'summer' ? 'selected' : '' }}>Stage d'été</option>
        </select>
        <input type="text" name="ville" value="{{ request('ville') }}" placeholder="Ville..."
               class="input-dark px-4 py-2 rounded-xl text-sm w-36">
        <button type="submit"
                class="btn-primary text-white text-sm font-medium px-5 py-2 rounded-xl">
            Filtrer
        </button>
        @if(request()->hasAny(['type','ville','recherche']))
            <a href="{{ route('offres.index') }}" class="text-white/30 hover:text-red-400 text-sm self-center transition">Réinitialiser</a>
        @endif
        <span class="ml-auto text-sm font-semibold self-center" style="color:#FDD400;">
            {{ $offres->total() }} offre(s)
        </span>
    </form>

    {{-- Liste --}}
    @php
        $images = [
            'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=600&q=80',
            'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=600&q=80',
            'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=600&q=80',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=600&q=80',
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&q=80',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80',
        ];
    @endphp

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($offres as $offre)
            <div class="card-dark rounded-2xl overflow-hidden hover:border-primary/30 transition flex flex-col"
                 style="transition: all 0.25s ease;">
                {{-- Image --}}
                <div class="h-40 bg-soft overflow-hidden">
                    <img src="{{ $images[$loop->index % count($images)] }}"
                         alt="Offre de stage"
                         class="w-full h-full object-cover">
                </div>

                {{-- Infos --}}
                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="font-bold text-white text-base leading-snug">{{ $offre->title }}</h2>
                        @if($offre->type)
                            <span class="badge-blue text-[11px] font-bold px-2 py-0.5 rounded uppercase shrink-0">
                                {{ $offre->type }}
                            </span>
                        @endif
                    </div>

                    <p class="text-sm font-semibold mt-0.5" style="color:#FDD400;">
                        {{ $offre->entreprise->company_name ?? '—' }}
                    </p>

                    <div class="flex flex-wrap gap-2 mt-3 text-xs text-white/40">
                        @if($offre->city)        <span>{{ $offre->city }}</span> @endif
                        <span>{{ $offre->duration_months }} mois</span>
                        @if($offre->stipend)     <span>{{ number_format($offre->stipend,0,',',' ') }} MAD/mois</span> @endif
                        @if($offre->is_remote)   <span>Télétravail</span> @endif
                    </div>

                    <p class="text-white/30 text-sm mt-3 leading-relaxed line-clamp-3">
                        {{ Str::limit($offre->description, 120) }}
                    </p>

                    @if($offre->required_skills)
                        <div class="flex flex-wrap gap-1.5 mt-3">
                            @foreach(array_slice($offre->required_skills, 0, 3) as $skill)
                                <span class="badge-blue text-xs font-medium px-2.5 py-0.5 rounded-lg">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-auto pt-5 flex items-center justify-between gap-3">
                        <a href="{{ route('offres.show', $offre) }}"
                           class="text-sm font-semibold text-white/40 hover:text-white transition">
                            Détails
                        </a>
                        @auth
                            @if(auth()->user()->estStagiaire())
                                <a href="{{ route('stagiaire.candidatures.create', $offre) }}"
                                   class="btn-primary text-white text-sm font-semibold px-4 py-2 rounded-xl">
                                    Postuler
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="btn-primary text-white text-sm font-semibold px-4 py-2 rounded-xl">
                                Postuler
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center md:col-span-2 xl:col-span-3">
                <div class="w-12 h-12 rounded-full bg-[#EAF1F6] text-teal flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-white mb-2">Aucune offre trouvée</h2>
                <p class="text-white/30 font-medium mb-5">
                    Essayez de modifier vos filtres ou revenez plus tard. Les nouvelles offres apparaîtront ici.
                </p>
                @if(request()->hasAny(['type','ville','recherche']))
                    <a href="{{ route('offres.index') }}"
                       class="btn-primary inline-block text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                        Réinitialiser les filtres
                    </a>
                @else
                    <a href="{{ route('accueil') }}"
                       class="btn-primary inline-block text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                        Retour à l'accueil
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($offres->hasPages())
        <div class="mt-8 flex justify-center">{{ $offres->links() }}</div>
    @endif
@endsection
