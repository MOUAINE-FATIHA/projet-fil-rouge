@extends('layouts.app')
@section('titre', 'Candidatures reçues')

@section('sidebar-links')
    <a href="{{ route('entreprise.offres.index') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Mes offres
    </a>
@endsection

@section('contenu')

    <div class="mb-2">
        <a href="{{ route('entreprise.offres.index') }}" class="text-sm text-white/30 hover:text-white transition">Retour aux offres</a>
    </div>

    <div class="flex items-start justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Candidatures reçues</h1>
            <p class="font-semibold text-sm mt-1" style="color:#60a5fa;">{{ $offre->title }}</p>
        </div>
        <div class="flex gap-2">
            @foreach(['' => 'Toutes', 'pending' => 'En attente', 'accepted' => 'Acceptées', 'rejected' => 'Refusées'] as $statut => $label)
                <a href="{{ route('entreprise.candidatures.index', [$offre, 'statut' => $statut]) }}"
                   class="text-xs font-medium px-3 py-1.5 rounded-lg border transition
                          {{ request('statut') === $statut
                              ? 'bg-primary text-white border-primary'
                              : 'border-white/10 text-white/40 hover:border-primary/50 hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        @forelse($candidatures as $candidature)
            @php
                $badgeClass = match($candidature->status) {
                    'pending'   => 'badge-pending',
                    'reviewing' => 'badge-blue',
                    'accepted'  => 'badge-accepted',
                    'rejected'  => 'badge-rejected',
                    default     => 'badge-gray',
                };
                $badgeLabel = match($candidature->status) {
                    'pending'   => 'En attente',
                    'reviewing' => 'En cours',
                    'accepted'  => 'Acceptée',
                    'rejected'  => 'Refusée',
                    default     => $candidature->status,
                };
            @endphp
            <div class="card-dark rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-white">{{ $candidature->stagiaire->user->name ?? '—' }}</p>
                                <p class="text-xs text-white/30">{{ $candidature->stagiaire->field_of_study ?? '' }}</p>
                            </div>
                            <span class="{{ $badgeClass }} text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $badgeLabel }}
                            </span>
                        </div>
                        <p class="text-xs text-white/20 mt-3">{{ $candidature->created_at->format('d/m/Y') }}</p>
                        @if($candidature->cover_letter)
                            <p class="text-sm text-white/30 mt-2 line-clamp-2">{{ Str::limit($candidature->cover_letter, 120) }}</p>
                        @endif
                    </div>
                    @if(in_array($candidature->status, ['pending','reviewing']))
                        <div class="flex flex-col gap-2 shrink-0">
                            <form method="POST" action="{{ route('entreprise.candidatures.accepter', $candidature) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-white text-sm font-semibold px-4 py-2 rounded-xl transition"
                                    style="background:rgba(16,185,129,0.2); border:1px solid rgba(16,185,129,0.3); color:#10B981;">
                                    Accepter
                                </button>
                            </form>
                            <form method="POST" action="{{ route('entreprise.candidatures.refuser', $candidature) }}"
                                  onsubmit="return confirm('Refuser cette candidature ?')">
                                @csrf
                                <button type="submit"
                                    class="w-full text-sm font-medium px-4 py-2 rounded-xl transition border border-white/10 text-white/30 hover:border-red-400/30 hover:text-red-400">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <p class="text-white/30 font-medium">Aucune candidature pour cette offre.</p>
            </div>
        @endforelse
    </div>

    @if($candidatures->hasPages())
        <div class="mt-8 flex justify-center">{{ $candidatures->links() }}</div>
    @endif

@endsection