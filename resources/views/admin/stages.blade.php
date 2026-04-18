@extends('layouts.app')
@section('titre', 'Tous les stages')

@section('sidebar-links')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Tableau de bord
    </a>
    <a href="{{ route('admin.entreprises') }}" class="sidebar-link">
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
    <a href="{{ route('admin.stages') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Stages
    </a>
@endsection

{{-- Assigner un encadrant --}}
@if(!$stage->supervisor_id)
    <form method="POST"
          action="{{ route('admin.stages.assigner-encadrant', $stage) }}"
          class="mt-3 pt-3 border-t border-white/5 flex items-center gap-3">
        @csrf
        <select name="supervisor_id"
                class="input-dark flex-1 px-3 py-2 rounded-xl text-xs">
            <option value="">Assigner un encadrant...</option>
            @foreach(\App\Models\ProfilEncadrant::with('user')->get() as $enc)
                <option value="{{ $enc->id }}">{{ $enc->user->name }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-xl">
            Assigner
        </button>
    </form>
@else
    <p class="mt-3 pt-3 border-t border-white/5 text-xs text-white/30">
        Encadrant : <span class="text-white/50">{{ $stage->encadrant->user->name ?? '—' }}</span>
    </p>
@endif

@section('contenu')

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Tous les stages</h1>
        <p class="text-white/40 text-sm mt-1">Vue globale de tous les stages de la plateforme.</p>
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
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="font-bold text-white">{{ $stage->candidature->offre->title ?? '—' }}</h2>
                            <span class="{{ $bClass }} text-xs font-semibold px-3 py-1 rounded-full">{{ $bLabel }}</span>
                        </div>
                        <div class="flex gap-6 mt-2 text-xs text-white/40">
                            <span>
                                <span class="text-white/60">{{ $stage->candidature->stagiaire->user->name ?? '—' }}</span>
                            </span>
                            <span>
                                <span class="text-white/60">{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</span>
                            </span>
                        </div>
                        <div class="flex gap-4 mt-1 text-xs text-white/20">
                            @if($stage->actual_start_date)
                                <span>Début : {{ $stage->actual_start_date->format('d/m/Y') }}</span>
                            @endif
                            @if($stage->actual_end_date)
                                <span>Fin : {{ $stage->actual_end_date->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <p class="text-white/30 font-medium">Aucun stage enregistré.</p>
            </div>
        @endforelse
    </div>

    @if($stages->hasPages())
        <div class="mt-8 flex justify-center">{{ $stages->links() }}</div>
    @endif

@endsection