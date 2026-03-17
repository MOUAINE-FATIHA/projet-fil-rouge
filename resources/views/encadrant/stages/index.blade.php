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

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Stages à encadrer</h1>
        <p class="text-white/40 text-sm mt-1">Suivez la progression de vos étudiants.</p>
    </div>

    <div class="space-y-4">
        @forelse($stages as $stage)
            @php
                $bClass = match($stage->status) {
                    'not_started' => 'badge-gray',
                    'in_progress' => 'badge-blue',
                    'completed'   => 'badge-accepted',
                    'interrupted' => 'badge-rejected',
                    default       => 'badge-gray',
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
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-sm shrink-0">
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
                        </div>

                        <div class="flex gap-4 mt-3 text-xs text-white/40">
                            <span>{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</span>
                            <span>{{ $stage->candidature->offre->title ?? '—' }}</span>
                        </div>

                        <div class="flex gap-4 mt-1 text-xs text-white/20">
                            @if($stage->actual_start_date)
                                <span>Début : {{ $stage->actual_start_date->format('d/m/Y') }}</span>
                            @endif
                            @if($stage->actual_end_date)
                                <span>Fin : {{ $stage->actual_end_date->format('d/m/Y') }}</span>
                            @endif
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
                                <div class="w-full h-1.5 rounded-full" style="background:rgba(255,255,255,0.06);">
                                    <div class="h-1.5 rounded-full"
                                         style="width:{{ $pct }}%; background:linear-gradient(90deg,#2563EB,#60a5fa);"></div>
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
                <p class="text-white/30 font-medium">Aucun stage ne vous est encore assigné.</p>
            </div>
        @endforelse
    </div>

    @if($stages->hasPages())
        <div class="mt-8 flex justify-center">{{ $stages->links() }}</div>
    @endif

@endsection