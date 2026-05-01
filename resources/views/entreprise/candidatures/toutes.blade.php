@extends('layouts.app')
@section('titre', 'Candidatures')

@section('sidebar-links')
    @include('entreprise.partials.sidebar')
@endsection

@section('contenu')
    <div class="card-dark rounded-2xl p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Candidatures</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Toutes les candidatures reçues</h1>
                <p class="text-white/40 text-sm mt-1">Traitez les demandes envoyées sur toutes vos offres.</p>
            </div>
            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                <p class="text-xs text-white/30 font-bold uppercase">Total</p>
                <p class="text-2xl font-extrabold text-white">{{ $candidatures->total() }}</p>
            </div>
        </div>
    </div>

    <div class="card-dark rounded-2xl p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            @foreach(['' => 'Toutes', 'pending' => 'En attente', 'accepted' => 'Acceptées', 'rejected' => 'Refusées'] as $statut => $label)
                <a href="{{ route('entreprise.candidatures.toutes', ['statut' => $statut]) }}"
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
                    'withdrawn' => 'Retirée',
                    default     => $candidature->status,
                };
            @endphp

            <div class="card-dark rounded-2xl p-5">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="w-10 h-10 rounded-full bg-teal flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-white">{{ $candidature->stagiaire->user->name ?? '—' }}</p>
                                <p class="text-xs text-white/30">{{ $candidature->stagiaire->field_of_study ?? 'Profil stagiaire' }}</p>
                            </div>
                            <span class="{{ $badgeClass }} text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $badgeLabel }}
                            </span>
                        </div>

                        <div class="mt-4 rounded-xl bg-soft border border-white/5 px-4 py-3">
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Offre concernée</p>
                            <p class="text-sm font-semibold text-white">{{ $candidature->offre->title ?? '—' }}</p>
                            <p class="text-xs text-white/30 mt-1">Reçue le {{ $candidature->created_at->format('d/m/Y') }}</p>
                        </div>

                        @if($candidature->cover_letter)
                            <p class="text-sm text-white/30 mt-3 line-clamp-2">{{ Str::limit($candidature->cover_letter, 140) }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 shrink-0 md:w-44">
                        <a href="{{ route('entreprise.candidatures.show', $candidature) }}"
                           class="btn-outline text-sm font-semibold px-4 py-2 rounded-xl text-center">
                            Voir le dossier
                        </a>

                        @if(in_array($candidature->status, ['pending','reviewing']))
                            <form method="POST" action="{{ route('entreprise.candidatures.accepter', $candidature) }}">
                                @csrf
                                <button type="submit" class="w-full badge-accepted text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-80">
                                    Accepter
                                </button>
                            </form>
                            <form method="POST" action="{{ route('entreprise.candidatures.refuser', $candidature) }}"
                                  onsubmit="return confirm('Refuser cette candidature ?')">
                                @csrf
                                <button type="submit"
                                    class="w-full text-sm font-medium px-4 py-2 rounded-xl border border-white/10 text-white/30 hover:border-red-400/30 hover:text-red-400 transition">
                                    Refuser
                                </button>
                            </form>
                        @endif
                    </div>
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
                <p class="text-white/30 font-medium">Les candidatures envoyées par les stagiaires apparaîtront ici.</p>
            </div>
        @endforelse
    </div>

    @if($candidatures->hasPages())
        <div class="mt-8 flex justify-center">{{ $candidatures->links() }}</div>
    @endif
@endsection
