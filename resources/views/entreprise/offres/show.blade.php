@extends('layouts.app')
@section('titre', 'Détail de l\'offre')

@section('sidebar-links')
    <a href="{{ route('entreprise.offres.index') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Mes offres
    </a>
@endsection

@section('contenu')

    <div class="mb-2">
        <a href="{{ route('entreprise.offres.index') }}" class="text-sm text-white/30 hover:text-white transition">Retour</a>
    </div>


    <div class="max-w-2xl space-y-5">
        {{-- En-tête --}}
        <div class="card-dark rounded-2xl p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-extrabold text-white">{{ $offre->title }}</h1>
                    <div class="flex flex-wrap gap-4 mt-3 text-sm text-white/40">
                        @if($offre->city)     <span>{{ $offre->city }}</span> @endif
                        <span>{{ $offre->duration_months }} mois</span>
                        @if($offre->stipend)  <span>{{ number_format($offre->stipend,0,',',' ') }} MAD/mois</span> @endif
                        @if($offre->is_remote)<span>Télétravail</span> @endif
                        @if($offre->application_deadline)
                            <span>Limite : {{ $offre->application_deadline->format('d/m/Y') }}</span>
                        @endif
                    </div>
                    @php
                        $badgeClass = match($offre->status) {
                            'published' => 'badge-accepted',
                            'draft'     => 'badge-gray',
                            'closed'    => 'badge-rejected',
                            default     => 'badge-gray',
                        };
                        $badgeLabel = match($offre->status) {
                            'published' => 'Publiée',
                            'draft'     => 'Brouillon',
                            'closed'    => 'Fermée',
                            default     => $offre->status,
                        };
                    @endphp
                    <span class="inline-block mt-3 {{ $badgeClass }} text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $badgeLabel }}
                    </span>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('entreprise.offres.edit', $offre) }}"
                       class="border border-white/10 text-white/40 hover:text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                        Modifier
                    </a>
                    <a href="{{ route('entreprise.candidatures.index', $offre) }}"
                       class="btn-primary text-white text-sm font-semibold px-4 py-2 rounded-xl">
                        Candidatures ({{ $offre->candidatures->count() }})
                    </a>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-white/5">
                <h2 class="text-sm font-semibold text-white/50 mb-2">Description</h2>
                <p class="text-white/40 text-sm leading-relaxed whitespace-pre-line">{{ $offre->description }}</p>
            </div>

            @if($offre->required_skills)
                <div class="mt-4 pt-4 border-t border-white/5">
                    <h2 class="text-sm font-semibold text-white/50 mb-2">Compétences requises</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($offre->required_skills as $skill)
                            <span class="badge-blue text-xs font-medium px-2.5 py-1 rounded-lg">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Dernières candidatures --}}
        <div class="card-dark rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-white">Candidatures ({{ $offre->candidatures->count() }})</h2>
                <a href="{{ route('entreprise.candidatures.index', $offre) }}"
                   class="text-sm font-medium transition" style="color:#60a5fa;">
                    Voir tout
                </a>
            </div>

            @forelse($offre->candidatures->take(5) as $candidature)
                @php
                    $bClass = match($candidature->status) {
                        'pending'   => 'badge-pending',
                        'accepted'  => 'badge-accepted',
                        'rejected'  => 'badge-rejected',
                        default     => 'badge-gray',
                    };
                    $bLabel = match($candidature->status) {
                        'pending'  => 'En attente',
                        'accepted' => 'Acceptée',
                        'rejected' => 'Refusée',
                        default    => $candidature->status,
                    };
                @endphp
                <div class="flex items-center justify-between py-3 border-b border-white/5 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $candidature->stagiaire->user->name ?? '—' }}</p>
                            <p class="text-xs text-white/30">{{ $candidature->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <span class="{{ $bClass }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ $bLabel }}</span>
                </div>
            @empty
                <p class="text-white/30 text-sm text-center py-4">Aucune candidature pour l'instant.</p>
            @endforelse
        </div>
    </div>

@endsection