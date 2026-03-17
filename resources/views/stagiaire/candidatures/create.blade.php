@extends('layouts.app')
@section('titre', 'Postuler')

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
@endsection

@section('contenu')

    <div class="mb-2">
        <a href="{{ route('offres.index') }}" class="text-sm text-white/30 hover:text-white transition">Retour aux offres</a>
    </div>

    <p class="font-semibold text-sm mb-1" style="color:#60a5fa;">{{ $offre->title }}</p>
    <h1 class="text-2xl font-extrabold text-white mb-1">Postuler à l'offre</h1>
    <p class="text-white/40 text-sm mb-8">Complétez votre dossier pour maximiser vos chances.</p>

    <form method="POST" action="{{ route('stagiaire.candidatures.store', $offre) }}"
          enctype="multipart/form-data" class="max-w-2xl space-y-5">
        @csrf

        {{-- Lettre de motivation --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white flex items-center gap-2 mb-1">
                <svg class="w-4 h-4" style="color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Lettre de motivation
            </h2>
            <p class="text-white/30 text-sm mb-4">Pourquoi souhaitez-vous rejoindre cette entreprise ?</p>
            <textarea name="cover_letter" rows="6"
                placeholder="Parlez de vos motivations et compétences..."
                class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none">{{ old('cover_letter') }}</textarea>
            <p class="text-right text-xs text-white/20 mt-2">Minimum 500 caractères conseillés</p>
        </div>

        {{-- CV --}}
        <div class="card-dark rounded-2xl p-6">
            <h2 class="font-bold text-white flex items-center gap-2 mb-4">
                <svg class="w-4 h-4" style="color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Curriculum Vitae (CV)
            </h2>
            <label class="block border-2 border-dashed border-white/10 hover:border-primary/50 rounded-xl p-8 text-center cursor-pointer transition">
                <input type="file" name="cv" accept=".pdf" class="sr-only"
                       onchange="document.getElementById('cvNom').textContent = this.files[0]?.name || ''">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background:rgba(37,99,235,0.1);">
                        <svg class="w-6 h-6" style="color:#2563EB;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="text-sm text-white/50">Cliquez pour téléverser</p>
                    <p class="text-xs text-white/20">PDF uniquement · Max 5MB</p>
                    <p id="cvNom" class="text-xs font-medium" style="color:#60a5fa;"></p>
                </div>
            </label>
        </div>

        <button type="submit"
            class="btn-primary w-full text-white font-semibold py-3.5 rounded-xl flex items-center justify-center gap-2">
            Envoyer ma candidature
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </form>

@endsection