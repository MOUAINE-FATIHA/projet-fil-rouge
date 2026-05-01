@extends('layouts.app')
@section('titre', 'Mes stages')

@section('sidebar-links')
    <a href="{{ route('encadrant.stages.index') }}" class="sidebar-link active">
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
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Espace encadrant</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Stages à encadrer</h1>
                <p class="text-white/40 text-sm mt-1">Retrouvez vos étudiants, leur entreprise et l'avancement du stage.</p>
            </div>
            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                <p class="text-xs text-white/30 font-bold uppercase">Stages assignés</p>
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
                    default  => 'badge-gray',
                };
                $bLabel = match($stage->status) {
                    'not_started' => 'Non commencé',
                    'in_progress' => 'En cours',
                    'completed'   => 'Terminé',
                    'interrupted' => 'Interrompu',
                    default => $stage->status,
                };
            @endphp

            <div class="card-dark rounded-2xl p-5">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="w-10 h-10 rounded-xl bg-teal flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($stage->candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-white">
                                    {{ $stage->candidature->stagiaire->user->name ?? '—' }}
                                </p>
                                <p class="text-xs text-white/30">
                                    {{ $stage->candidature->stagiaire->field_of_study ?? '' }}
                                </p>
                            </div>
                            <span class="{{ $bClass }} text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $bLabel }}
                            </span>
                            @if($stage->conventionValidee())
                                <span class="badge-accepted text-xs font-semibold px-3 py-1 rounded-full">Convention validée</span>
                            @elseif($stage->conventionDeposee())
                                <span class="badge-pending text-xs font-semibold px-3 py-1 rounded-full">Convention à valider</span>
                            @elseif($stage->conventionPreparee())
                                <span class="badge-blue text-xs font-semibold px-3 py-1 rounded-full">Convention préparée</span>
                            @else
                                <span class="badge-gray text-xs font-semibold px-3 py-1 rounded-full">Convention en préparation</span>
                            @endif
                        </div>

                        <div class="grid md:grid-cols-3 gap-3 mt-4">
                            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Entreprise</p>
                                <p class="text-sm text-white/60">{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Sujet</p>
                                <p class="text-sm text-white/60">{{ $stage->candidature->offre->title ?? '—' }}</p>
                            </div>
                            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Période</p>
                                <p class="text-sm text-white/60">
                                    {{ optional($stage->actual_start_date)->format('d/m/Y') ?? '—' }}
                                    -
                                    {{ optional($stage->actual_end_date)->format('d/m/Y') ?? '—' }}
                                </p>
                            </div>
                        </div>

                        {{-- Barre progression --}}
                        @if($stage->status === 'in_progress' && $stage->actual_start_date && $stage->actual_end_date)
                            @php
                                $total = $stage->actual_start_date->diffInDays($stage->actual_end_date);
                                $passe = $stage->actual_start_date->diffInDays(now());
                                $pct   = $total > 0 ? min(100, round(($passe / $total) * 100)) : 0;
                            @endphp
                            <div class="mt-3">
                                <div class="flex justify-between text-xs text-white/20 mb-1">
                                    <span>Progression</span>
                                    <span>{{ $pct }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-soft border border-white/5 overflow-hidden">
                                    <div class="h-1.5 rounded-full"
                                         style="width:{{ $pct }}%; background:linear-gradient(90deg,#FDD400,#FDD400);"></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('encadrant.stages.show', $stage) }}"
                       class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-xl shrink-0">
                        Voir le détail
                    </a>
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
                <h2 class="text-lg font-bold text-white mb-2">Aucun stage assigné</h2>
                <p class="text-white/30 font-medium">Les stages affectés par l'administration apparaîtront ici.</p>
            </div>
        @endforelse
    </div>

    
    @if($stages->hasPages())
        <div class="mt-8 flex justify-center">{{ $stages->links() }}</div>
    @endif

@endsection
