@extends('layouts.app')
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

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Stages disponibles</h1>
        <p class="text-white/40 text-sm mt-1">Découvrez les opportunités qui correspondent à votre profil.</p>
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
        <span class="ml-auto text-sm font-semibold self-center" style="color:#60a5fa;">
            {{ $offres->total() }} offre(s)
        </span>
    </form>

    {{-- Liste --}}
    <div class="space-y-4">
        @forelse($offres as $offre)
            <div class="card-dark rounded-2xl p-5 flex gap-5 hover:border-primary/30 transition"
                 style="transition: all 0.3s ease;">
                {{-- Image --}}
                <div class="w-36 h-28 rounded-xl overflow-hidden shrink-0">
                    <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=300&q=70"
                         alt="Offre" class="w-full h-full object-cover opacity-70">
                </div>

                {{-- Infos --}}
                <div class="flex-1 min-w-0">
                    <h2 class="font-bold text-white text-base">{{ $offre->title }}</h2>
                    <p class="text-sm font-semibold mt-0.5" style="color:#60a5fa;">
                        {{ $offre->entreprise->company_name ?? '—' }}
                    </p>
                    <div class="flex flex-wrap gap-3 mt-2 text-xs text-white/40">
                        @if($offre->city)        <span>{{ $offre->city }}</span> @endif
                        <span>{{ $offre->duration_months }} mois</span>
                        @if($offre->stipend)     <span>{{ number_format($offre->stipend,0,',',' ') }} MAD/mois</span> @endif
                        @if($offre->is_remote)   <span>Télétravail</span> @endif
                    </div>
                    <p class="text-white/30 text-sm mt-2 line-clamp-2">{{ Str::limit($offre->description, 100) }}</p>
                    @if($offre->required_skills)
                        <div class="flex flex-wrap gap-1.5 mt-3">
                            @foreach(array_slice($offre->required_skills, 0, 4) as $skill)
                                <span class="badge-blue text-xs font-medium px-2.5 py-0.5 rounded-lg">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex flex-col items-end justify-between shrink-0">
                    <a href="{{ route('offres.show', $offre) }}"
                       class="text-sm text-white/30 hover:text-white transition">Voir les détails</a>
                    @auth
                        @if(auth()->user()->estStagiaire())
                            <a href="{{ route('stagiaire.candidatures.create', $offre) }}"
                               class="btn-primary text-white text-sm font-semibold px-5 py-2 rounded-xl">
                                Postuler
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-primary text-white text-sm font-semibold px-5 py-2 rounded-xl">
                            Postuler
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <p class="text-white/30 font-medium">Aucune offre disponible pour le moment.</p>
            </div>
        @endforelse
    </div>

    @if($offres->hasPages())
        <div class="mt-8 flex justify-center">{{ $offres->links() }}</div>
    @endif

@endsection
{{-- ── Widget "Offres en temps réel" consommant l'API ── --}}
<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-white">
            Chargement via API
            <span class="badge-blue text-xs font-medium px-2 py-0.5 rounded-lg ml-2">JSON REST</span>
        </h2>
        <button onclick="chargerOffres()"
            class="btn-primary text-white text-xs font-medium px-4 py-2 rounded-xl flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Actualiser
        </button>
    </div>

    <div id="api-loading" class="hidden text-center py-6 text-white/30 text-sm">
        Chargement en cours...
    </div>

    <div id="api-offres" class="grid grid-cols-3 gap-4"></div>
</div>

<script>
async function chargerOffres() {
    const loading = document.getElementById('api-loading');
    const container = document.getElementById('api-offres');

    loading.classList.remove('hidden');
    container.innerHTML = '';

    try {
        const response = await fetch('/api/offres?par_page=3');
        const json = await response.json();

        loading.classList.add('hidden');

        if (!json.success || !json.data.length) {
            container.innerHTML = '<p class="text-white/30 text-sm col-span-3 text-center py-4">Aucune offre disponible.</p>';
            return;
        }

        json.data.forEach(offre => {
            const card = document.createElement('div');
            card.className = 'card-dark rounded-2xl p-5 border border-white/5 hover:border-primary/30 transition';
            card.innerHTML = `
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge-blue text-xs font-medium px-2.5 py-0.5 rounded-lg">${offre.type.toUpperCase()}</span>
                    ${offre.teletravail ? '<span class="badge-gray text-xs font-medium px-2.5 py-0.5 rounded-lg">Télétravail</span>' : ''}
                </div>
                <h3 class="font-bold text-white text-sm mb-1">${offre.titre}</h3>
                <p class="text-xs font-semibold mb-3" style="color:#60a5fa;">${offre.entreprise.nom}</p>
                <div class="flex flex-wrap gap-2 text-xs text-white/30 mb-3">
                    ${offre.ville ? `<span>${offre.ville}</span>` : ''}
                    <span>${offre.duree_mois} mois</span>
                    ${offre.gratification ? `<span>${offre.gratification} MAD/mois</span>` : ''}
                </div>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    ${offre.competences.slice(0,3).map(s => `<span class="badge-blue text-xs px-2 py-0.5 rounded">${s}</span>`).join('')}
                </div>
                <a href="/offres/${offre.id}"
                   class="block text-center text-white text-xs font-semibold py-2 rounded-xl btn-primary">
                    Voir l'offre
                </a>
            `;
            container.appendChild(card);
        });
    } catch (error) {
        loading.classList.add('hidden');
        container.innerHTML = '<p class="text-red-400 text-sm col-span-3 text-center py-4">Erreur lors du chargement.</p>';
    }
}

// Charger automatiquement au chargement de la page
document.addEventListener('DOMContentLoaded', chargerOffres);
</script>