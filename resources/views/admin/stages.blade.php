@extends('layouts.app')
@section('titre', 'Tous les stages')

@section('sidebar-links')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/>
        </svg>
        Tableau de bord
    </a>
    <a href="{{ route('admin.entreprises') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
        </svg>
        Entreprises
    </a>
    <a href="{{ route('admin.utilisateurs') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1"/>
        </svg>
        Utilisateurs
    </a>
    <a href="{{ route('admin.stages') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0118 8v11a2 2 0 01-2 2z"/>
        </svg>
        Stages
    </a>
@endsection

@section('contenu')
    <div class="card-dark rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Suivi</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Tous les stages</h1>
                <p class="text-white/40 text-sm mt-1">Vue globale des stages et des encadrants affectés.</p>
            </div>
            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                <p class="text-xs text-white/30 font-bold uppercase">Stages</p>
                <p class="text-2xl font-extrabold text-white">{{ $stages->total() }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($stages as $stage)
            @php
                $bClass = match($stage->status) {
                    'not_started' => 'badge-gray',
                    'in_progress' => 'badge-blue',
                    'completed'   => 'badge-accepted',
                    'interrupted' => 'badge-rejected',
                    default => 'badge-gray',
                };
                $bLabel = match($stage->status) {
                    'not_started' => 'Non commencé',
                    'in_progress' => 'En cours',
                    'completed'   => 'Terminé',
                    'interrupted' => 'Interrompu',
                    default       => $stage->status,
                };
            @endphp

            <div class="card-dark rounded-2xl p-5">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="font-bold text-white text-lg">{{ $stage->candidature->offre->title ?? '—' }}</h2>
                            <span class="{{ $bClass }} text-xs font-semibold px-3 py-1 rounded-full">{{ $bLabel }}</span>
                            @if($stage->conventionValidee())
                                <span class="badge-accepted text-xs font-semibold px-3 py-1 rounded-full">Convention validée</span>
                            @elseif($stage->conventionDeposee())
                                <span class="badge-pending text-xs font-semibold px-3 py-1 rounded-full">Convention à valider</span>
                            @elseif($stage->conventionPreparee())
                                <span class="badge-blue text-xs font-semibold px-3 py-1 rounded-full">Convention préparée</span>
                            @else
                                <span class="badge-gray text-xs font-semibold px-3 py-1 rounded-full">À préparer</span>
                            @endif
                        </div>

                        <div class="grid md:grid-cols-3 gap-3 mt-4">
                            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Stagiaire</p>
                                <p class="text-sm text-white/60">{{ $stage->candidature->stagiaire->user->name ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Entreprise</p>
                                <p class="text-sm text-white/60">{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Encadrant</p>
                                <p class="text-sm text-white/60">{{ $stage->encadrant->user->name ?? 'Non assigné' }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4 mt-3 text-xs text-white/30">
                            @if($stage->actual_start_date)
                                <span>Début : {{ $stage->actual_start_date->format('d/m/Y') }}</span>
                            @endif
                            @if($stage->actual_end_date)
                                <span>Fin : {{ $stage->actual_end_date->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="lg:w-80 space-y-3">
                        <a href="{{ route('admin.stages.convention', $stage) }}"
                           class="btn-outline block text-center text-xs font-semibold px-4 py-2.5 rounded-xl">
                            {{ $stage->conventionPreparee() ? 'Modifier la convention' : 'Préparer la convention' }}
                        </a>

                    @if(!$stage->supervisor_id)
                        <form method="POST"
                              action="{{ route('stages.assigner-encadrant', $stage) }}"
                              class="rounded-xl bg-soft border border-white/5 p-4">
                            @csrf
                            <label class="block text-xs font-bold text-white/30 uppercase mb-2">Assigner un encadrant</label>
                            <div class="flex gap-2">
                                <select name="supervisor_id" class="input-dark flex-1 px-3 py-2 rounded-xl text-xs" required>
                                    <option value="">Choisir...</option>
                                    @foreach(\App\Models\ProfilEncadrant::with('user')->get() as $enc)
                                        <option value="{{ $enc->id }}">{{ $enc->user->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                    OK
                                </button>
                            </div>
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
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0118 8v11a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-white mb-2">Aucun stage enregistré</h2>
                <p class="text-white/30 font-medium">Les stages créés après acceptation des candidatures apparaîtront ici.</p>
            </div>
        @endforelse
    </div>

    @if($stages->hasPages())
        <div class="mt-8 flex justify-center">{{ $stages->links() }}</div>
    @endif
@endsection
