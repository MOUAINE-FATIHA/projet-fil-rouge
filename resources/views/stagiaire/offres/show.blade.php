@extends('layouts.app')
@section('titre', 'Détail de l\'offre')

@section('sidebar-links')
    <a href="{{ route('offres.index') }}"
       class="sidebar-link active">
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
    <a href="{{ route('stagiaire.stages.index') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Mes stages
    </a>
@endsection

@section('contenu')

    <div class="mb-4">
        <a href="{{ route('offres.index') }}" class="text-sm text-white/30 hover:text-white transition">Retour aux offres</a>
    </div>

    <div class="max-w-2xl space-y-5">

        {{-- En-tête --}}
        <div class="card-dark rounded-2xl overflow-hidden">
            <div class="h-48 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&q=80"
                     alt="Offre" class="w-full h-full object-cover opacity-50">
            </div>
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-extrabold text-white">{{ $offre->title }}</h1>
                        <p class="font-semibold text-sm mt-1" style="color:#60a5fa;">
                            {{ $offre->entreprise->company_name ?? '—' }}
                        </p>
                        <div class="flex flex-wrap gap-4 mt-3 text-sm text-white/40">
                            @if($offre->city)     <span>{{ $offre->city }}, Maroc</span> @endif
                            <span>⏱ {{ $offre->duration_months }} mois</span>
                            @if($offre->stipend)  <span>{{ number_format($offre->stipend,0,',',' ') }} MAD/mois</span> @endif
                            @if($offre->is_remote)<span>Télétravail</span> @endif
                        </div>
                        @if($offre->application_deadline)
                            <p class="text-xs mt-2" style="color:#F59E0B;">
                                ⚠ Date limite : {{ $offre->application_deadline->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                    @auth
                        @if(auth()->user()->estStagiaire())
                            @if($offre->estFermee())
                                <span class="badge-gray text-sm font-medium px-4 py-2 rounded-xl shrink-0">Fermée</span>
                            @else
                                <a href="{{ route('stagiaire.candidatures.create', $offre) }}"
                                   class="btn-primary text-white font-semibold px-6 py-2.5 rounded-xl shrink-0 text-sm">
                                    Postuler
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="btn-primary text-white font-semibold px-6 py-2.5 rounded-xl shrink-0 text-sm">
                            Postuler
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-3 pb-3 border-b border-white/5">Description du poste</h2>
            <p class="text-white/50 text-sm leading-relaxed whitespace-pre-line">{{ $offre->description }}</p>
        </div>

        {{-- Compétences --}}
        @if($offre->required_skills)
            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-3 pb-3 border-b border-white/5">Compétences requises</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($offre->required_skills as $skill)
                        <span class="badge-blue text-xs font-medium px-3 py-1.5 rounded-lg">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Entreprise --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">À propos de l'entreprise</h2>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-lg shrink-0">
                    {{ strtoupper(substr($offre->entreprise->company_name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-white">{{ $offre->entreprise->company_name ?? '—' }}</p>
                    @if($offre->entreprise->industry)
                        <p class="text-white/30 text-sm">{{ $offre->entreprise->industry }}</p>
                    @endif
                    @if($offre->entreprise->city)
                        <p class="text-white/30 text-sm">{{ $offre->entreprise->city }}</p>
                    @endif
                </div>
            </div>
            @if($offre->entreprise->description)
                <p class="text-white/40 text-sm mt-4 leading-relaxed">{{ $offre->entreprise->description }}</p>
            @endif
        </div>

    </div>

@endsection