@extends('layouts.app')
@section('titre', 'Dossier candidature')

@section('sidebar-links')
    <a href="{{ route('entreprise.offres.index') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        Mes offres
    </a>
@endsection

@section('contenu')

    <div class="mb-2">
        <a href="{{ route('entreprise.candidatures.index', $candidature->offre) }}"
           class="text-sm text-white/30 hover:text-white transition">Retour aux candidatures</a>
    </div>

    <h1 class="text-2xl font-extrabold text-white mb-1">Dossier de candidature</h1>
    <p class="font-semibold text-sm mb-8" style="color:#60a5fa;">{{ $candidature->offre->title }}</p>

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
                <a href="{{ Storage::url($candidature->cv_path) }}" target="_blank"
                   class="inline-flex items-center gap-2 font-medium text-sm transition" style="color:#60a5fa;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger le CV (PDF)
                </a>
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
                    default     => 'badge-gray',
                };
                $bLabel = match($candidature->status) {
                    'accepted'  => 'Candidature acceptée',
                    'rejected'  => 'Candidature refusée',
                    'withdrawn' => 'Candidature retirée',
                    default     => $candidature->status,
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