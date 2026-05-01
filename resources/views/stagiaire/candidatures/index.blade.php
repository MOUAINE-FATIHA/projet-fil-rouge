@extends('layouts.app')
@section('titre', 'Mes candidatures')

@section('sidebar-links')
    <a href="{{ route('offres.index') }}"
       class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Offres de stage
    </a>
    <a href="{{ route('stagiaire.candidatures.index') }}"
       class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Mes candidatures
    </a>
    <a href="{{ route('stagiaire.stages.index') }}"
       class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Mes stages
    </a>
@endsection

@section('contenu')

    <div class="card-dark rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Candidatures</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Mes candidatures</h1>
                <p class="text-white/40 text-sm mt-1">Suivez les réponses des entreprises depuis un seul endroit.</p>
            </div>
            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                <p class="text-xs text-white/30 font-bold uppercase">Total</p>
                <p class="text-2xl font-extrabold text-white">{{ $candidatures->total() }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($candidatures as $c)
            @php
                $badgeClass = match($c->status) {
                    'pending'   => 'badge-pending',
                    'reviewing' => 'badge-blue',
                    'accepted'  => 'badge-accepted',
                    'rejected'  => 'badge-rejected',
                    default     => 'badge-gray',
                };
                $badgeLabel = match($c->status) {
                    'pending'   => 'En attente',
                    'reviewing' => 'En cours',
                    'accepted'  => 'Acceptée',
                    'rejected'  => 'Refusée',
                    'withdrawn' => 'Retirée',
                    default     => $c->status,
                };
            @endphp
            <div class="card-dark rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="font-bold text-white">{{ $c->offre->title ?? '—' }}</h2>
                            <span class="{{ $badgeClass }} text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $badgeLabel }}
                            </span>
                        </div>
                        <p class="text-sm font-medium mt-0.5" style="color:#FDD400;">
                            {{ $c->offre->entreprise->company_name ?? '—' }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3 text-xs text-white/30">
                            @if($c->offre->city)
                                <span class="badge-gray px-2.5 py-1 rounded-lg">{{ $c->offre->city }}</span>
                            @endif
                            <span class="badge-gray px-2.5 py-1 rounded-lg">Postulé le {{ $c->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($c->company_feedback)
                            <div class="mt-3 rounded-xl px-4 py-3 text-sm text-white/50 badge-gray">
                                <span class="font-medium text-white/70">Retour : </span>{{ $c->company_feedback }}
                            </div>
                        @endif
                    </div>
                    @if(in_array($c->status, ['pending','reviewing']))
                        <form method="POST" action="{{ route('stagiaire.candidatures.retirer', $c) }}"
                              onsubmit="return confirm('Retirer cette candidature ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs text-white/30 hover:text-red-400 border border-white/10 hover:border-red-400/30 px-3 py-1.5 rounded-lg transition">
                                Retirer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#EAF1F6] text-teal flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-white mb-2">Aucune candidature</h2>
                <p class="text-white/30 font-medium mb-4">Explorez les offres disponibles et postulez à celles qui correspondent à votre profil.</p>
                <a href="{{ route('offres.index') }}"
                   class="btn-primary inline-block text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                    Découvrir les offres
                </a>
            </div>
        @endforelse
    </div>

    @if($candidatures->hasPages())
        <div class="mt-8 flex justify-center">{{ $candidatures->links() }}</div>
    @endif

@endsection
