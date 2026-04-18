@extends('layouts.app')
@section('titre', 'Entreprises')

@section('sidebar-links')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Tableau de bord
    </a>
    <a href="{{ route('admin.entreprises') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Entreprises
    </a>
    <a href="{{ route('admin.utilisateurs') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Utilisateurs
    </a>
    <a href="{{ route('admin.stages') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Stages
    </a>
@endsection

@section('contenu')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Gestion des entreprises</h1>
            <p class="text-white/40 text-sm mt-1">Validez ou rejetez les comptes entreprises.</p>
        </div>
        <div class="flex gap-2">
            @foreach(['' => 'Toutes', 'pending' => 'En attente', 'approved' => 'Validées', 'rejected' => 'Rejetées'] as $val => $label)
                <a href="{{ route('admin.entreprises', ['statut' => $val]) }}"
                   class="text-xs font-medium px-3 py-1.5 rounded-lg border transition
                          {{ request('statut') === $val
                              ? 'bg-primary text-white border-primary'
                              : 'border-white/10 text-white/40 hover:border-primary/50 hover:text-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-4">
        @forelse($entreprises as $entreprise)
            @php
                $bClass = match($entreprise->validation_status) {
                    'pending'  => 'badge-pending',
                    'approved' => 'badge-accepted',
                    'rejected' => 'badge-rejected',
                    default => 'badge-gray',
                };
                $bLabel = match($entreprise->validation_status) {
                    'pending'  => 'En attente',
                    'approved' => 'Validée',
                    'rejected' => 'Rejetée',
                    default => $entreprise->validation_status,
                };
            @endphp
            <div class="card-dark rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="font-bold text-white">{{ $entreprise->company_name }}</h2>
                            <span class="{{ $bClass }} text-xs font-semibold px-3 py-1 rounded-full">{{ $bLabel }}</span>
                        </div>
                        <p class="text-sm text-white/40 mt-1">{{ $entreprise->user->email ?? '' }}</p>
                        <div class="flex gap-4 mt-2 text-xs text-white/30">
                            @if($entreprise->industry)<span>{{ $entreprise->industry }}</span> @endif
                            @if($entreprise->city)    <span>{{ $entreprise->city }}</span> @endif
                        </div>
                        @if($entreprise->rejection_reason)
                            <p class="mt-2 text-xs badge-rejected px-3 py-2 rounded-lg">
                                Motif : {{ $entreprise->rejection_reason }}
                            </p>
                        @endif
                    </div>

                    @if($entreprise->validation_status === 'pending')
                        <div class="flex flex-col gap-2 shrink-0">
                            <form method="POST" action="{{ route('admin.entreprises.valider', $entreprise) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-sm font-semibold px-4 py-2 rounded-xl transition badge-accepted hover:opacity-80">
                                    Valider
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.entreprises.rejeter', $entreprise) }}"
                                  onsubmit="return confirm('Rejeter cette entreprise ?')">
                                @csrf
                                <input type="hidden" name="rejection_reason" value="Dossier incomplet ou non conforme.">
                                <button type="submit"
                                    class="w-full text-sm font-medium px-4 py-2 rounded-xl border border-white/10 text-white/30 hover:border-red-400/30 hover:text-red-400 transition">
                                    Rejeter
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <p class="text-white/30 font-medium">Aucune entreprise trouvée.</p>
            </div>
        @endforelse
    </div>

    @if($entreprises->hasPages())
        <div class="mt-8 flex justify-center">{{ $entreprises->links() }}</div>
    @endif

@endsection