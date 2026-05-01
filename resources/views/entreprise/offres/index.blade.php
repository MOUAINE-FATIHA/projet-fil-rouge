@extends('layouts.app')
@section('titre', 'Mes offres')

@section('sidebar-links')
    @include('entreprise.partials.sidebar')
@endsection

@section('contenu')

    <div class="card-dark rounded-2xl p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Espace entreprise</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Mes offres de stage</h1>
                <p class="text-white/40 text-sm mt-1">Publiez vos offres et traitez les candidatures reçues.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('entreprise.candidatures.toutes') }}"
                   class="btn-outline text-sm font-semibold px-5 py-2.5 rounded-xl">
                    Voir les candidatures
                </a>
                <a href="{{ route('entreprise.offres.create') }}"
                   class="btn-primary text-white font-semibold text-sm px-5 py-2.5 rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle offre
                </a>
            </div>
        </div>
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
                               class="text-sm font-semibold transition" style="color:#FDD400;">
                                {{ $offre->candidatures_count ?? 0 }} candidature(s)
                            </a>
                            @if(($offre->en_attente ?? 0) > 0)
                                <span class="badge-pending text-xs font-medium px-2.5 py-0.5 rounded-lg">
                                    {{ $offre->en_attente }} en attente
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 shrink-0">
                        @if(($offre->candidatures_count ?? 0) > 0)
                            <a href="{{ route('entreprise.candidatures.index', $offre) }}"
                               class="btn-primary text-white text-sm font-semibold px-4 py-2 rounded-xl">
                                Traiter les candidatures
                            </a>
                        @else
                            <a href="{{ route('entreprise.candidatures.index', $offre) }}"
                               class="btn-outline text-sm font-semibold px-4 py-2 rounded-xl">
                                Voir les candidatures
                            </a>
                        @endif

                        <div class="flex items-center gap-2 text-xs">
                            <a href="{{ route('entreprise.offres.edit', $offre) }}"
                               class="text-white/30 hover:text-white transition">
                                Modifier l'offre
                            </a>
                            <span class="text-white/20">·</span>
                            <form method="POST" action="{{ route('entreprise.offres.destroy', $offre) }}"
                                  onsubmit="return confirm('Supprimer cette offre ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-white/30 hover:text-red-400 transition">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#EAF1F6] text-teal flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-white mb-2">Aucune offre publiée</h2>
                <p class="text-white/30 font-medium mb-4">Créez une première offre pour commencer à recevoir des candidatures.</p>
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
