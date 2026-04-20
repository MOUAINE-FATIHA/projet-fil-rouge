@extends('layouts.app')
@section('titre', 'Mes offres')

@section('sidebar-links')
    <a href="{{ route('entreprise.offres.index') }}"
       class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Mes offres
    </a>
@endsection

@section('contenu')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Mes offres de stage</h1>
            <p class="text-white/40 text-sm mt-1">Gérez vos offres et suivez les candidatures.</p>
        </div>
        <a href="{{ route('entreprise.offres.create') }}"
           class="btn-primary text-white font-semibold text-sm px-5 py-2.5 rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle offre
        </a>
    </div>

    <div class="space-y-4">
        @forelse($offres as $offre)
            @php
                $badgeClass = match($offre->status) {
                    'published' => 'badge-accepted',
                    'draft'     => 'badge-gray',
                    'closed'    => 'badge-rejected',
                    default => 'badge-gray',
                };
                $badgeLabel = match($offre->status) {
                    'published' => 'Publiée',
                    'draft'  => 'Brouillon',
                    'closed'    => 'Fermée',
                    default     => $offre->status,
                };
            @endphp
            <div class="card-dark rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="font-bold text-white">{{ $offre->title }}</h2>
                            <span class="{{ $badgeClass }} text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $badgeLabel }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 mt-2 text-xs text-white/30 flex-wrap">
                            @if($offre->city) <span>{{ $offre->city }}</span> @endif
                            <span>{{ $offre->duration_months }} mois</span>
                            @if($offre->application_deadline)
                                <span>Limite : {{ $offre->application_deadline->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 mt-3">
                            <a href="{{ route('entreprise.candidatures.index', $offre) }}"
                               class="text-sm font-semibold transition" style="color:#60a5fa;">
                                {{ $offre->candidatures_count ?? 0 }} candidature(s)
                            </a>
                            @if(($offre->en_attente ?? 0) > 0)
                                <span class="badge-pending text-xs font-medium px-2.5 py-0.5 rounded-lg">
                                    {{ $offre->en_attente }} en attente
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('entreprise.offres.edit', $offre) }}"
                           class="text-sm text-white/30 hover:text-white border border-white/10 hover:border-white/20 px-3 py-1.5 rounded-lg transition">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('entreprise.offres.destroy', $offre) }}"
                              onsubmit="return confirm('Supprimer cette offre ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-sm text-white/30 hover:text-red-400 border border-white/10 hover:border-red-400/30 px-3 py-1.5 rounded-lg transition">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <p class="text-white/30 font-medium mb-4">Vous n'avez pas encore d'offre publiée.</p>
                <a href="{{ route('entreprise.offres.create') }}"
                   class="btn-primary inline-block text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                    Créer votre première offre
                </a>
            </div>
        @endforelse
    </div>

    @if($offres->hasPages())
        <div class="mt-8 flex justify-center">{{ $offres->links() }}</div>
    @endif

@endsection