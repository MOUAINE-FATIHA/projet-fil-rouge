@extends('layouts.app')
@section('titre', 'Dossier candidature')

@section('sidebar-links')
    @include('entreprise.partials.sidebar')
@endsection

@section('contenu')

    <div class="mb-2">
        <a href="{{ route('entreprise.candidatures.index', $candidature->offre) }}"
           class="text-sm text-white/30 hover:text-white transition">Retour aux candidatures</a>
    </div>

    <h1 class="text-2xl font-extrabold text-white mb-1">Dossier de candidature</h1>
    <p class="font-semibold text-sm mb-8" style="color:#FDD400;">{{ $candidature->offre->title }}</p>

    <div class="max-w-2xl space-y-5">

        {{-- Profil --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Profil du candidat</h2>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-xl shrink-0">
                    {{ strtoupper(substr($candidature->stagiaire->user->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-white text-lg">{{ $candidature->stagiaire->user->name ?? '—' }}</p>
                    <p class="text-white/40 text-sm">{{ $candidature->stagiaire->user->email ?? '' }}</p>
                    @if($candidature->stagiaire->field_of_study)
                        <p class="text-white/30 text-sm">
                            {{ $candidature->stagiaire->field_of_study }}
                            @if($candidature->stagiaire->academic_level)
                                · {{ $candidature->stagiaire->academic_level }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            @if($candidature->stagiaire->skills)
                <div class="mt-4 pt-4 border-t border-white/5">
                    <p class="text-xs font-semibold text-white/30 uppercase tracking-widest mb-2">Compétences</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($candidature->stagiaire->skills as $skill)
                            <span class="badge-blue text-xs font-medium px-3 py-1 rounded-lg">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Lettre de motivation --}}
        @if($candidature->cover_letter)
            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-3 pb-3 border-b border-white/5">Lettre de motivation</h2>
                <p class="text-white/40 text-sm leading-relaxed whitespace-pre-line">{{ $candidature->cover_letter }}</p>
            </div>
        @endif


        {{-- CV --}}
        @if($candidature->cv_path)
            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-3 pb-3 border-b border-white/5">Curriculum Vitae</h2>
                <a href="{{ route('entreprise.candidatures.cv', $candidature) }}"
                   class="inline-flex items-center gap-2 font-medium text-sm transition" style="color:#FDD400;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger le CV (PDF)
                </a>
            </div>
        @endif

        {{-- Convention --}}
        @if($candidature->status === 'accepted' && $candidature->stage)
            @php
                $stage = $candidature->stage;
            @endphp
            <div class="card-dark rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4 pb-3 border-b border-white/5">
                    <div>
                        <h2 class="font-bold text-white">Convention de stage</h2>
                        <p class="text-white/30 text-sm mt-1">Document officiel après acceptation de la candidature.</p>
                    </div>
                    @if($stage->conventionValidee())
                        <span class="badge-accepted text-xs font-semibold px-3 py-1 rounded-lg">Validée</span>
                    @elseif($stage->conventionDeposee())
                        <span class="badge-pending text-xs font-semibold px-3 py-1 rounded-lg">En attente</span>
                    @elseif($stage->conventionPreparee())
                        <span class="badge-blue text-xs font-semibold px-3 py-1 rounded-lg">Prête à signer</span>
                    @else
                        <span class="badge-gray text-xs font-semibold px-3 py-1 rounded-lg">En préparation</span>
                    @endif
                </div>

                @if($stage->conventionPreparee())
                    <div class="grid sm:grid-cols-2 gap-3 text-sm mb-4">
                        <div class="rounded-xl bg-soft border border-white/5 p-4">
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Période</p>
                            <p class="text-white/60">
                                {{ optional($stage->actual_start_date)->format('d/m/Y') ?? '—' }}
                                -
                                {{ optional($stage->actual_end_date)->format('d/m/Y') ?? '—' }}
                            </p>
                        </div>
                        <div class="rounded-xl bg-soft border border-white/5 p-4">
                            <p class="text-xs text-white/20 uppercase tracking-widest mb-1">Lieu</p>
                            <p class="text-white/60">{{ $stage->convention_place ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-soft border border-white/5 p-4 mb-4">
                        <p class="text-xs text-white/20 uppercase tracking-widest mb-2">Missions</p>
                        <p class="text-white/50 text-sm leading-relaxed whitespace-pre-line">{{ $stage->convention_tasks }}</p>
                    </div>
                @else
                    <p class="text-white/30 text-sm">
                        La convention doit d'abord être préparée par l'administration.
                    </p>
                @endif

                @if($stage->conventionPreparee())
                    <a href="{{ route('entreprise.candidatures.convention.preparee', $candidature) }}"
                       class="inline-flex items-center gap-2 font-semibold text-sm mb-4 mr-4" style="color:#FDD400;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger la convention préparée
                    </a>
                @endif


                @if($stage->conventionPreparee() && !$stage->conventionValidee())
                    <form method="POST"
                          action="{{ route('entreprise.candidatures.convention.upload', $candidature) }}"
                          enctype="multipart/form-data"
                          class="grid sm:grid-cols-[1fr_auto] gap-3 items-center">
                        @csrf
                        <input type="file" name="convention" accept="application/pdf"
                               class="input-dark w-full px-3 py-2 rounded-xl text-sm">
                        <button type="submit" class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-xl">
                            {{ $stage->conventionDeposee() ? 'Remplacer' : 'Déposer' }}
                        </button>
                    </form>
                    @error('convention')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        @endif

        {{-- Décision --}}
        @if(in_array($candidature->status, ['pending','reviewing']))
            <div class="card-dark rounded-2xl p-6">
                <h2 class="font-bold text-white mb-4 pb-3 border-b border-white/5">Décision</h2>
                <div class="grid grid-cols-2 gap-4">
                    <form method="POST" action="{{ route('entreprise.candidatures.accepter', $candidature) }}">
                        @csrf
                        <textarea name="feedback" rows="3" placeholder="Message pour le candidat (optionnel)..."
                            class="input-dark w-full px-3 py-2 rounded-xl text-sm resize-none mb-3"></textarea>
                        <button type="submit"
                            class="w-full font-semibold py-2.5 rounded-xl transition text-sm badge-accepted hover:opacity-80">
                            Accepter la candidature
                        </button>
                    </form>
                    <form method="POST" action="{{ route('entreprise.candidatures.refuser', $candidature) }}"
                          onsubmit="return confirm('Refuser cette candidature ?')">
                        @csrf
                        <textarea name="feedback" rows="3" placeholder="Motif du refus (optionnel)..."
                            class="input-dark w-full px-3 py-2 rounded-xl text-sm resize-none mb-3"></textarea>
                        <button type="submit"
                            class="w-full font-medium py-2.5 rounded-xl transition text-sm border border-white/10 text-white/30 hover:border-red-400/30 hover:text-red-400">
                            Refuser la candidature
                        </button>
                    </form>
                </div>
            </div>
        @else
            @php
                $bClass = match($candidature->status) {
                    'accepted'  => 'badge-accepted',
                    'rejected'  => 'badge-rejected',
                    default => 'badge-gray',
                };
                $bLabel = match($candidature->status) {
                    'accepted'  => 'Candidature acceptée',
                    'rejected'  => 'Candidature refusée',
                    'withdrawn' => 'Candidature retirée',
                    default => $candidature->status,
                };
            @endphp
            <div class="{{ $bClass }} rounded-2xl px-5 py-4">
                <p class="font-semibold text-sm">{{ $bLabel }}</p>
                @if($candidature->company_feedback)
                    <p class="text-sm mt-1 opacity-70">{{ $candidature->company_feedback }}</p>
                @endif
            </div>
        @endif
    </div>

@endsection
