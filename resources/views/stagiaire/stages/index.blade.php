@extends('layouts.app')
@section('titre', 'Mes stages')

@section('sidebar-links')
    <a href="{{ route('offres.index') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Offres de stage
    </a>
    <a href="{{ route('stagiaire.candidatures.index') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Mes candidatures
    </a>
    <a href="{{ route('stagiaire.stages.index') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Mes stages
    </a>
@endsection

@section('contenu')

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Mes stages</h1>
        <p class="text-white/40 text-sm mt-1">Suivez l'avancement de vos stages.</p>
    </div>

    <div class="space-y-4">
        @forelse($stages as $stage)
            @php
                $badgeClass = match($stage->status) {
                    'not_started' => 'badge-gray',
                    'in_progress' => 'badge-blue',
                    'completed'   => 'badge-accepted',
                    'interrupted' => 'badge-rejected',
                    default       => 'badge-gray',
                };
                $badgeLabel = match($stage->status) {
                    'not_started' => 'Non commencé',
                    'in_progress' => 'En cours',
                    'completed'   => 'Terminé',
                    'interrupted' => 'Interrompu',
                    default       => $stage->status,
                };
            @endphp

            <div class="card-dark rounded-2xl p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="font-bold text-white text-lg">
                            {{ $stage->candidature->offre->title ?? '—' }}
                        </h2>
                        <p class="font-semibold text-sm mt-0.5" style="color:#60a5fa;">
                            {{ $stage->candidature->offre->entreprise->company_name ?? '—' }}
                        </p>
                    </div>
                    <span class="{{ $badgeClass }} text-xs font-semibold px-3 py-1.5 rounded-full shrink-0">
                        {{ $badgeLabel }}
                    </span>
                </div>

                {{-- Infos --}}
                <div class="grid grid-cols-2 gap-4 text-sm text-white/40 mb-4">
                    @if($stage->actual_start_date)
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Début</p>
                            <p>{{ $stage->actual_start_date->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    @if($stage->actual_end_date)
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Fin prévue</p>
                            <p>{{ $stage->actual_end_date->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    @if($stage->candidature->offre->city)
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Lieu</p>
                            <p>{{ $stage->candidature->offre->city }}</p>
                        </div>
                    @endif
                    @if($stage->candidature->offre->duration_months)
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Durée</p>
                            <p>{{ $stage->candidature->offre->duration_months }} mois</p>
                        </div>
                    @endif
                </div>

                {{-- Barre de progression --}}
                @if($stage->status === 'in_progress' && $stage->actual_start_date && $stage->actual_end_date)
                    @php
                        $total = $stage->actual_start_date->diffInDays($stage->actual_end_date);
                        $passe = $stage->actual_start_date->diffInDays(now());
                        $pct   = $total > 0 ? min(100, round(($passe / $total) * 100)) : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-white/30 mb-2">
                            <span>Progression</span>
                            <span>{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full" style="background:rgba(255,255,255,0.06);">
                            <div class="h-1.5 rounded-full transition-all"
                                 style="width:{{ $pct }}%; background: linear-gradient(90deg, #2563EB, #60a5fa);"></div>
                        </div>
                    </div>
                @endif

                {{-- Documents --}}
                <div class="flex items-center gap-4 pt-4 border-t border-white/5 text-xs">
                    @if($stage->convention_path)
                        <span class="badge-accepted px-2.5 py-1 rounded-lg font-medium">Convention signée</span>
                    @else
                        <span class="badge-pending px-2.5 py-1 rounded-lg font-medium">Convention en attente</span>
                    @endif
                    @if($stage->report_path)
                        <span class="badge-accepted px-2.5 py-1 rounded-lg font-medium">Rapport déposé</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <p class="text-white/30 font-medium mb-4">Vous n'avez pas encore de stage en cours.</p>
                <a href="{{ route('offres.index') }}"
                   class="btn-primary inline-block text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                    Découvrir les offres
                </a>
            </div>
        @endforelse
    </div>

    @if($stages->hasPages())
        <div class="mt-8 flex justify-center">{{ $stages->links() }}</div>
    @endif

@endsection