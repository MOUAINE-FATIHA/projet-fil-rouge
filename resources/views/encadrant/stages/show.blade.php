@extends('layouts.app')
@section('titre', 'Suivi du stage')

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

    <div class="mb-2">
        <a href="{{ route('encadrant.stages.index') }}" class="text-sm text-white/30 hover:text-white transition">Retour</a>
    </div>

    <h1 class="text-2xl font-extrabold text-white mb-1">Suivi du stage</h1>
    <p class="font-semibold text-sm mb-8" style="color:#60a5fa;">
        {{ $stage->candidature->offre->title ?? '—' }}
    </p>

    <div class="max-w-2xl space-y-5">

        {{-- Infos étudiant --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Étudiant</h2>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-xl shrink-0">
                    {{ strtoupper(substr($stage->candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-white text-lg">{{ $stage->candidature->stagiaire->user->name ?? '—' }}</p>
                    <p class="text-white/40 text-sm">{{ $stage->candidature->stagiaire->user->email ?? '' }}</p>
                    <p class="text-white/30 text-sm">
                        {{ $stage->candidature->stagiaire->field_of_study ?? '' }}
                        @if($stage->candidature->stagiaire->academic_level)
                            · {{ $stage->candidature->stagiaire->academic_level }}
                        @endif
                    </p>
                </div>
            </div>
            @if($stage->candidature->stagiaire->skills)
                <div class="flex flex-wrap gap-2">
                    @foreach($stage->candidature->stagiaire->skills as $skill)
                        <span class="badge-blue text-xs font-medium px-3 py-1 rounded-lg">{{ $skill }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Infos stage --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Informations du stage</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Entreprise</p>
                    <p class="text-white/60">{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Poste</p>
                    <p class="text-white/60">{{ $stage->candidature->offre->title ?? '—' }}</p>
                </div>
                @if($stage->actual_start_date)
                    <div>
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Début</p>
                        <p class="text-white/60">{{ $stage->actual_start_date->format('d/m/Y') }}</p>
                    </div>
                @endif
                @if($stage->actual_end_date)
                    <div>
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Fin prévue</p>
                        <p class="text-white/60">{{ $stage->actual_end_date->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>

            {{-- Barre progression --}}
            @if($stage->status === 'in_progress' && $stage->actual_start_date && $stage->actual_end_date)
                @php
                    $total = $stage->actual_start_date->diffInDays($stage->actual_end_date);
                    $passe = $stage->actual_start_date->diffInDays(now());
                    $pct   = $total > 0 ? min(100, round(($passe / $total) * 100)) : 0;
                @endphp
                <div class="mt-4 pt-4 border-t border-white/5">
                    <div class="flex justify-between text-xs text-white/30 mb-2">
                        <span>Progression du stage</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full" style="background:rgba(255,255,255,0.06);">
                        <div class="h-2 rounded-full"
                             style="width:{{ $pct }}%; background:linear-gradient(90deg,#2563EB,#60a5fa);"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Documents --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Documents</h2>
            <div class="flex gap-4">
                @if($stage->convention_path)
                    <span class="badge-accepted text-xs font-medium px-3 py-1.5 rounded-lg">✓ Convention signée</span>
                @else
                    <span class="badge-pending text-xs font-medium px-3 py-1.5 rounded-lg">Convention en attente</span>
                @endif
                @if($stage->report_path)
                    <span class="badge-accepted text-xs font-medium px-3 py-1.5 rounded-lg">Rapport déposé</span>
                @else
                    <span class="badge-gray text-xs font-medium px-3 py-1.5 rounded-lg">Rapport non déposé</span>
                @endif
            </div>
        </div>

        {{-- Compte-rendu de suivi --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Compte-rendu de suivi</h2>

            @if($stage->student_feedback)
                <div class="mb-4 p-4 rounded-xl text-sm text-white/50"
                     style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05);">
                    {{ $stage->student_feedback }}
                </div>
            @endif

            <form method="POST" action="{{ route('encadrant.stages.compte-rendu', $stage) }}">
                @csrf
                <label class="block text-sm font-medium text-white/60 mb-2">
                    {{ $stage->student_feedback ? 'Mettre à jour le compte-rendu' : 'Ajouter un compte-rendu' }}
                </label>
                <textarea name="compte_rendu" rows="4"
                    placeholder="Observations, remarques, points à améliorer..."
                    class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none mb-3">{{ $stage->student_feedback }}</textarea>
                <button type="submit"
                    class="btn-primary text-white font-semibold px-6 py-2.5 rounded-xl text-sm">
                    Enregistrer
                </button>
            </form>
        </div>

    </div>

@endsection