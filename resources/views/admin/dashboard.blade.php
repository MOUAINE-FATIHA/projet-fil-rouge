@extends('layouts.app')
@section('titre', 'Dashboard Admin')

@section('sidebar-links')
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Tableau de bord
    </a>
    <a href="{{ route('admin.entreprises') }}"
       class="sidebar-link {{ request()->routeIs('admin.entreprises') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Entreprises
        @if($stats['en_attente'] > 0)
            <span class="ml-auto badge-pending text-xs font-bold px-2 py-0.5 rounded-full">{{ $stats['en_attente'] }}</span>
        @endif
    </a>
    <a href="{{ route('admin.utilisateurs') }}"
       class="sidebar-link {{ request()->routeIs('admin.utilisateurs') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Utilisateurs
    </a>
    <a href="{{ route('admin.stages') }}"
       class="sidebar-link {{ request()->routeIs('admin.stages') ? 'active' : '' }}">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Stages
    </a>
@endsection

@section('contenu')

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Tableau de bord</h1>
        <p class="text-white/40 text-sm mt-1">Vue d'ensemble de la plateforme StageConnect.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        @foreach([
            ['label' => 'Stagiaires','value' => $stats['stagiaires'],   'color' => '#60a5fa'],
            ['label' => 'Entreprises','value' => $stats['entreprises'],  'color' => '#60a5fa'],
            ['label' => 'Offres publiées', 'value' => $stats['offres'],       'color' => '#60a5fa'],
            ['label' => 'Candidatures',  'value' => $stats['candidatures'], 'color' => '#60a5fa'],
            ['label' => 'Stages actifs',    'value' => $stats['stages'],       'color' => '#10B981'],
            ['label' => 'En attente validation','value' => $stats['en_attente'],  'color' => '#F59E0B'],
        ] as $stat)
            <div class="card-dark rounded-2xl p-5">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-widest mb-2">{{ $stat['label'] }}</p>
                <p class="text-3xl font-extrabold" style="color:{{ $stat['color'] }}">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>


    {{-- Entreprises en attente --}}
    @if($entreprises_recentes->count() > 0)
        <div class="card-dark rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-white">Entreprises en attente de validation</h2>
                <a href="{{ route('admin.entreprises', ['statut' => 'pending']) }}"
                   class="text-sm font-medium transition" style="color:#60a5fa;">
                    Voir tout
                </a>
            </div>
            <div class="space-y-3">
                @foreach($entreprises_recentes as $entreprise)
                    <div class="flex items-center justify-between py-3 border-b border-white/5 last:border-0">
                        <div>
                            <p class="font-semibold text-white text-sm">{{ $entreprise->company_name }}</p>
                            <p class="text-xs text-white/30">{{ $entreprise->user->email ?? '' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.entreprises.valider', $entreprise) }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition badge-accepted hover:opacity-80">
                                    Valider
                                </button>
                            </form>
                            <a href="{{ route('admin.entreprises') }}"
                               class="text-xs font-medium px-3 py-1.5 rounded-lg border border-white/10 text-white/30 hover:text-white transition">
                                Voir
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection