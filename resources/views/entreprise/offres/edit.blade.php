@extends('layouts.app')
@section('titre', 'Modifier l\'offre')

@section('sidebar-links')
    @include('entreprise.partials.sidebar')
@endsection

@section('contenu')

    <div class="mb-2">
        <a href="{{ route('entreprise.offres.index') }}" class="text-sm text-white/30 hover:text-white transition">← Retour</a>
    </div>

    <h1 class="text-2xl font-extrabold text-white mb-1">Modifier l'offre</h1>
    <p class="font-semibold text-sm mb-8" style="color:#FDD400;">{{ $offre->title }}</p>

    <form method="POST" action="{{ route('entreprise.offres.update', $offre) }}" class="max-w-2xl space-y-5">
        @csrf
        @method('PUT')

        <div class="card-dark rounded-2xl p-6 space-y-5">
            <h2 class="font-bold text-white pb-3 border-b border-white/5">Informations générales</h2>

            <div>
                <label class="block text-sm font-medium text-white/60 mb-2">Titre <span class="text-red-400">*</span></label>
                <input type="text" name="title" value="{{ old('title', $offre->title) }}" required
                       class="input-dark w-full px-4 py-3 rounded-xl text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-white/60 mb-2">Description <span class="text-red-400">*</span></label>
                <textarea name="description" rows="5" required
                    class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none">{{ old('description', $offre->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Domaine</label>
                    <input type="text" name="domain" value="{{ old('domain', $offre->domain) }}"
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Type <span class="text-red-400">*</span></label>
                    <select name="type" required class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        <option value="pfe"    {{ old('type', $offre->type) === 'pfe'    ? 'selected' : '' }}>PFE</option>
                        <option value="pfa"    {{ old('type', $offre->type) === 'pfa'    ? 'selected' : '' }}>PFA</option>
                        <option value="summer" {{ old('type', $offre->type) === 'summer' ? 'selected' : '' }}>Stage d'été</option>
                        <option value="other"  {{ old('type', $offre->type) === 'other'  ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-white/60 mb-2">Statut</label>
                <select name="status" class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    <option value="published" {{ old('status', $offre->status) === 'published' ? 'selected' : '' }}>Publiée</option>
                    <option value="draft"     {{ old('status', $offre->status) === 'draft'     ? 'selected' : '' }}>Brouillon</option>
                    <option value="closed"    {{ old('status', $offre->status) === 'closed'    ? 'selected' : '' }}>Fermée</option>
                </select>
            </div>
        </div>

        <div class="card-dark rounded-2xl p-6 space-y-5">
            <h2 class="font-bold text-white pb-3 border-b border-white/5">Dates et durée</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Durée (mois) <span class="text-red-400">*</span></label>
                    <input type="number" name="duration_months"
                           value="{{ old('duration_months', $offre->duration_months) }}"
                           min="1" max="24" required class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Nombre de places</label>
                    <input type="number" name="slots" value="{{ old('slots', $offre->slots) }}"
                           min="1" class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Date de début <span class="text-red-400">*</span></label>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', $offre->start_date?->format('Y-m-d')) }}"
                           required class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Date limite candidature</label>
                    <input type="date" name="application_deadline"
                           value="{{ old('application_deadline', $offre->application_deadline?->format('Y-m-d')) }}"
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
        </div>

        <div class="card-dark rounded-2xl p-6 space-y-5">
            <h2 class="font-bold text-white pb-3 border-b border-white/5">Lieu et rémunération</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Ville</label>
                    <input type="text" name="city" value="{{ old('city', $offre->city) }}"
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Gratification (MAD/mois)</label>
                    <input type="number" name="stipend" value="{{ old('stipend', $offre->stipend) }}"
                           min="0" class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Niveau requis</label>
                    <input type="text" name="required_level"
                           value="{{ old('required_level', $offre->required_level) }}"
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div class="flex items-center gap-3 pt-7">
                    <input type="checkbox" name="is_remote" id="is_remote" value="1"
                           {{ old('is_remote', $offre->is_remote) ? 'checked' : '' }}
                           class="w-4 h-4 rounded accent-primary">
                    <label for="is_remote" class="text-sm font-medium text-white/60 cursor-pointer">
                        Télétravail possible
                    </label>
                </div>
            </div>
        </div>

        <button type="submit"
            class="btn-primary w-full text-white font-semibold py-3.5 rounded-xl flex items-center justify-center gap-2">
            Enregistrer les modifications
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </form>

@endsection
