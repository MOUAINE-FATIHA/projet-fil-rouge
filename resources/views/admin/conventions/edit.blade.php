@extends('layouts.app')
@section('titre', 'Préparer la convention')

@section('sidebar-links')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">Tableau de bord</a>
    <a href="{{ route('admin.entreprises') }}" class="sidebar-link">Entreprises</a>
    <a href="{{ route('admin.utilisateurs') }}" class="sidebar-link">Utilisateurs</a>
    <a href="{{ route('admin.stages') }}" class="sidebar-link active">Stages</a>
@endsection

@section('contenu')
    <div class="mb-4">
        <a href="{{ route('admin.stages') }}" class="text-sm text-white/30 hover:text-white transition">
            Retour aux stages
        </a>
    </div>

    <div class="grid xl:grid-cols-[1fr_360px] gap-5 items-start">
        <div class="card-dark rounded-2xl p-6">
            <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Convention</span>
            <h1 class="text-2xl font-extrabold text-white mt-4">Préparer la convention</h1>
            <p class="text-white/40 text-sm mt-1">
                Les dates viennent de l'offre. Complétez surtout les missions et les règles de l'école.
            </p>

            <form method="POST" action="{{ route('admin.stages.convention.preparer', $stage) }}" class="mt-6 space-y-5">
                @csrf

                @php
                    $offre = $stage->candidature->offre;
                    $dateDebut = $stage->actual_start_date ?? $offre->start_date;
                    $dateFin = $stage->actual_end_date ?? ($offre->end_date ?: $offre->start_date->copy()->addMonths($offre->duration_months));
                @endphp

                <div class="grid md:grid-cols-3 gap-4">
                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Début</p>
                        <p class="text-white/60 text-sm">{{ $dateDebut ? $dateDebut->format('d/m/Y') : '—' }}</p>
                    </div>

                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Fin</p>
                        <p class="text-white/60 text-sm">{{ $dateFin ? $dateFin->format('d/m/Y') : '—' }}</p>
                    </div>

                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Durée</p>
                        <p class="text-white/60 text-sm">{{ $offre->duration_months }} mois</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white/60 mb-2">Nom de l'école</label>
                    <input type="text" name="school_name"
                           value="{{ old('school_name', $stage->school_name ?? 'YouCode') }}"
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm"
                           placeholder="Exemple : YouCode">
                    @error('school_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white/60 mb-2">Lieu du stage</label>
                    <input type="text" name="convention_place"
                           value="{{ old('convention_place', $stage->convention_place ?? $stage->candidature->offre->city) }}"
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm"
                           placeholder="Exemple : Safi, Maroc">
                    @error('convention_place') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white/60 mb-2">Missions principales</label>
                    <textarea name="convention_tasks" rows="5"
                              class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none"
                              placeholder="Décrivez les tâches du stagiaire pendant le stage.">{{ old('convention_tasks', $stage->convention_tasks ?? $stage->candidature->offre->description) }}</textarea>
                    @error('convention_tasks') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-white/60 mb-2">Règles et conditions de l'école</label>
                    <textarea name="convention_notes" rows="4"
                              class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none"
                              placeholder="Exemple : respect du règlement de stage, présence régulière, confidentialité, rapport final obligatoire.">{{ old('convention_notes', $stage->convention_notes) }}</textarea>
                    @error('convention_notes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary text-white font-semibold px-6 py-3 rounded-xl text-sm">
                    Enregistrer et notifier l'entreprise
                </button>
            </form>
        </div>

        <aside class="space-y-5">
            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-4">Informations du stage</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-white/20 uppercase text-xs tracking-widest">Stagiaire</p>
                        <p class="text-white/60">{{ $stage->candidature->stagiaire->user->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-white/20 uppercase text-xs tracking-widest">Entreprise</p>
                        <p class="text-white/60">{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-white/20 uppercase text-xs tracking-widest">Sujet</p>
                        <p class="text-white/60">{{ $stage->candidature->offre->title ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-white/20 uppercase text-xs tracking-widest">Encadrant</p>
                        <p class="text-white/60">{{ $stage->encadrant->user->name ?? 'Non assigné' }}</p>
                    </div>
                </div>
            </div>

            <div class="panel-dark rounded-2xl p-5">
                <p class="text-white font-bold">Flux simple</p>
                <p class="text-white/70 text-sm leading-relaxed mt-2">
                    Après enregistrement, l'entreprise reçoit une notification. Elle signe la convention puis dépose le PDF signé.
                </p>
            </div>
        </aside>
    </div>
@endsection
