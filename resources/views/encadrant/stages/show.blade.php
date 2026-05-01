@extends('layouts.app')
@section('titre', 'Suivi du stage')

@section('sidebar-links')
    <a href="{{ route('encadrant.stages.index') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0118 8v11a2 2 0 01-2 2z"/>
        </svg>
        Mes stages
    </a>
@endsection

@section('contenu')
    @php
        $statusClass = match($stage->status) {
            'not_started' => 'badge-gray',
            'in_progress' => 'badge-blue',
            'completed'   => 'badge-accepted',
            'interrupted' => 'badge-rejected',
            default       => 'badge-gray',
        };
        $statusLabel = match($stage->status) {
            'not_started' => 'Non commencé',
            'in_progress' => 'En cours',
            'completed'   => 'Terminé',
            'interrupted' => 'Interrompu',
            default       => $stage->status,
        };
        $conventionLabel = $stage->conventionValidee()
            ? 'Convention validée'
            : ($stage->conventionDeposee()
                ? 'Convention à valider'
                : ($stage->conventionPreparee() ? 'En attente du PDF signé' : 'Convention en préparation'));
        $conventionClass = $stage->conventionValidee()
            ? 'badge-accepted'
            : ($stage->conventionDeposee()
                ? 'badge-pending'
                : ($stage->conventionPreparee() ? 'badge-blue' : 'badge-gray'));
    @endphp

    <div class="card-dark rounded-2xl p-6 mb-6">
        <a href="{{ route('encadrant.stages.index') }}" class="text-sm font-semibold text-white/30 hover:text-white transition">
            Retour aux stages
        </a>
        <div class="mt-4 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Suivi individuel</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Suivi du stage</h1>
                <p class="font-semibold text-sm mt-1" style="color:#FDD400;">
                    {{ $stage->candidature->offre->title ?? '—' }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2 self-start lg:self-auto">
                <span class="{{ $statusClass }} text-xs font-bold px-3 py-1.5 rounded-full">
                    {{ $statusLabel }}
                </span>
                <span class="{{ $conventionClass }} text-xs font-bold px-3 py-1.5 rounded-full">
                    {{ $conventionLabel }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid xl:grid-cols-[1fr_360px] gap-5 items-start">
        <div class="space-y-5">
            <div class="grid lg:grid-cols-2 gap-5">
                <div class="card-dark rounded-2xl p-6">
                    <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Étudiant</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-teal flex items-center justify-center text-white font-bold text-xl shrink-0">
                            {{ strtoupper(substr($stage->candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-white text-lg">{{ $stage->candidature->stagiaire->user->name ?? '—' }}</p>
                            <p class="text-white/40 text-sm">{{ $stage->candidature->stagiaire->user->email ?? '' }}</p>
                            <p class="text-white/30 text-sm">
                                {{ $stage->candidature->stagiaire->field_of_study ?? 'Formation non renseignée' }}
                                @if($stage->candidature->stagiaire->academic_level)
                                    · {{ $stage->candidature->stagiaire->academic_level }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($stage->candidature->stagiaire->skills)
                        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-white/5">
                            @foreach($stage->candidature->stagiaire->skills as $skill)
                                <span class="badge-blue text-xs font-medium px-3 py-1 rounded-lg">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card-dark rounded-2xl p-6">
                    <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Entreprise</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Nom</p>
                            <p class="text-white/60">{{ $stage->candidature->offre->entreprise->company_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Email</p>
                            <p class="text-white/60">{{ $stage->candidature->offre->entreprise->user->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Ville</p>
                            <p class="text-white/60">{{ $stage->candidature->offre->entreprise->city ?? $stage->candidature->offre->city ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Informations du stage</h2>
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Poste</p>
                        <p class="text-white/60">{{ $stage->candidature->offre->title ?? '—' }}</p>
                    </div>
                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Lieu</p>
                        <p class="text-white/60">{{ $stage->convention_place ?? $stage->candidature->offre->city ?? '—' }}</p>
                    </div>
                    @if($stage->actual_start_date)
                        <div class="rounded-xl bg-soft border border-white/5 p-4">
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Début</p>
                            <p class="text-white/60">{{ $stage->actual_start_date->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    @if($stage->actual_end_date)
                        <div class="rounded-xl bg-soft border border-white/5 p-4">
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Fin prévue</p>
                            <p class="text-white/60">{{ $stage->actual_end_date->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>

                @if($stage->status === 'in_progress' && $stage->actual_start_date && $stage->actual_end_date)
                    @php
                        $total = $stage->actual_start_date->diffInDays($stage->actual_end_date);
                        $passe = $stage->actual_start_date->diffInDays(now());
                        $pct = $total > 0 ? min(100, round(($passe / $total) * 100)) : 0;
                    @endphp
                    <div class="mt-4 pt-4 border-t border-white/5">
                        <div class="flex justify-between text-xs text-white/30 mb-2">
                            <span>Progression du stage</span>
                            <span>{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-soft border border-white/5 overflow-hidden">
                            <div class="h-2 rounded-full" style="width:{{ $pct }}%; background:#FDD400;"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Documents</h2>
                <div class="space-y-4">
                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-xs text-white/20 uppercase tracking-widest mb-2">Convention</p>
                                <div class="flex items-start gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full mt-1.5 shrink-0"
                                          style="background: {{ $stage->conventionValidee() ? '#166344' : ($stage->conventionDeposee() ? '#F59E0B' : '#708090') }};"></span>
                                    <div>
                                        <p class="text-sm font-semibold text-white/70">{{ $conventionLabel }}</p>
                                        <p class="text-xs text-white/30 mt-1">
                                            @if($stage->conventionValidee())
                                                Le document signé est vérifié et validé.
                                            @elseif($stage->conventionDeposee())
                                                L'entreprise a déposé le PDF signé. Vous pouvez le vérifier.
                                            @elseif($stage->conventionPreparee())
                                                L'entreprise doit encore déposer le PDF signé.
                                            @else
                                                L'administration doit préparer la convention.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($stage->conventionDeposee())
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('encadrant.stages.convention.download', $stage) }}"
                                       class="btn-outline text-sm font-semibold px-5 py-2.5 rounded-xl">
                                        Télécharger
                                    </a>

                                    @if(!$stage->conventionValidee())
                                        <form method="POST" action="{{ route('encadrant.stages.convention.valider', $stage) }}">
                                            @csrf
                                            <button type="submit" class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-xl">
                                                Valider
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl bg-soft border border-white/5 p-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-2">Rapport</p>
                        <div class="flex items-start gap-3">
                            <span class="w-2.5 h-2.5 rounded-full mt-1.5 shrink-0"
                                  style="background: {{ $stage->report_path ? '#166344' : '#708090' }};"></span>
                            <div>
                        @if($stage->report_path)
                                <p class="text-sm font-semibold text-white/70">Rapport déposé</p>
                                <p class="text-xs text-white/30 mt-1">Le rapport du stage est disponible.</p>
                        @else
                                <p class="text-sm font-semibold text-white/70">Rapport non déposé</p>
                                <p class="text-xs text-white/30 mt-1">Le rapport apparaîtra ici après son dépôt.</p>
                        @endif
                            </div>
                        </div>
                    </div>

                    @if($stage->conventionValidee() && $stage->convention_validated_at)
                        <p class="text-white/30 text-xs">
                            Validée le {{ $stage->convention_validated_at->format('d/m/Y à H:i') }}.
                        </p>
                    @endif
                </div>
            </div>

            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Compte-rendu de suivi</h2>

                @if($stage->student_feedback)
                    <div class="mb-4 p-4 rounded-xl text-sm text-white/50 bg-soft border border-white/5">
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
                    <button type="submit" class="btn-primary text-white font-semibold px-6 py-2.5 rounded-xl text-sm">
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>

        <aside class="space-y-5 xl:sticky xl:top-24">
            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white">À vérifier pendant le suivi</h2>
                <div class="space-y-3 mt-4">
                    @foreach([
                        'Le sujet reste cohérent avec l’offre',
                        'Le stagiaire reçoit un retour régulier',
                        'La convention signée est déposée par l’entreprise',
                        'Les remarques importantes sont notées',
                    ] as $point)
                        <div class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-[#E8F6EF] text-teal flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <p class="text-sm text-white/50">{{ $point }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-dark rounded-2xl p-5">
                <p class="text-white font-bold">Note simple</p>
                <p class="text-white/70 text-sm leading-relaxed mt-2">
                    Le compte-rendu sert à garder une trace claire du suivi. Il doit rester court, précis et utile pour l'administration.
                </p>
            </div>
        </aside>
    </div>
@endsection
