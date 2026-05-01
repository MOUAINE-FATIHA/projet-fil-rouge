@extends('layouts.app')
@section('titre', 'Nouvelle offre')

@section('sidebar-links')
    @include('entreprise.partials.sidebar')
@endsection

@section('contenu')
    @php
        $entreprise = auth()->user()->profilEntreprise;
        $competences = old('required_skills', ['', '', '']);
    @endphp

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <a href="{{ route('entreprise.offres.index') }}" class="text-sm font-semibold text-white/30 hover:text-white transition">
                Retour aux offres
            </a>
            <h1 class="text-3xl font-extrabold text-white mt-4">Nouvelle offre de stage</h1>
            <p class="text-white/40 text-sm mt-2 max-w-2xl">
                Une offre courte, précise et lisible reçoit de meilleures candidatures.
            </p>
        </div>

        <div class="card-dark rounded-2xl px-5 py-4 min-w-[260px]">
            <p class="text-xs font-bold text-white/30 uppercase">Entreprise</p>
            <p class="font-bold text-white mt-1">{{ $entreprise->company_name ?? auth()->user()->name }}</p>
            @if(($entreprise->validation_status ?? null) === 'approved')
                <span class="badge-accepted text-xs font-bold px-2.5 py-1 rounded-full mt-3 inline-flex">Compte validé</span>
            @else
                <span class="badge-pending text-xs font-bold px-2.5 py-1 rounded-full mt-3 inline-flex">Validation en attente</span>
            @endif
        </div>
    </div>

    @if(($entreprise->validation_status ?? null) !== 'approved')
        <div class="badge-pending rounded-2xl px-5 py-4 mb-6">
            <p class="font-bold">Votre compte entreprise n'est pas encore validé.</p>
            <p class="text-sm mt-1">Vous pouvez préparer l'offre, mais la publication sera possible après validation par l'administration.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('entreprise.offres.store') }}"
          class="grid xl:grid-cols-[minmax(0,1fr)_360px] gap-6 items-start"
          x-data="{ remote: {{ old('is_remote') ? 'true' : 'false' }} }">
        @csrf

        <div class="space-y-5">
            <div class="card-dark rounded-2xl p-6">
                <div class="flex items-center gap-3 pb-5 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-[#E8F6EF] text-teal flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-white">Ce que le candidat verra en premier</h2>
                        <p class="text-sm text-white/30">Titre, type de stage, domaine et description.</p>
                    </div>
                </div>

                <div class="space-y-5 mt-5">
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Titre du poste <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="Ex : Développeur Laravel / React"
                               required class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-white/60 mb-2">Domaine</label>
                            <input type="text" name="domain" value="{{ old('domain') }}"
                                   placeholder="Développement web, Data, Design..."
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white/60 mb-2">Type <span class="text-red-400">*</span></label>
                            <select name="type" required class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                                <option value="">Sélectionner</option>
                                <option value="pfe" {{ old('type') === 'pfe' ? 'selected' : '' }}>PFE</option>
                                <option value="pfa" {{ old('type') === 'pfa' ? 'selected' : '' }}>PFA</option>
                                <option value="summer" {{ old('type') === 'summer' ? 'selected' : '' }}>Stage d'été</option>
                                <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Missions principales <span class="text-red-400">*</span></label>
                        <textarea name="description" rows="7" required
                                  placeholder="Présentez le contexte, les missions concrètes et ce que le stagiaire va apprendre."
                                  class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-dark rounded-2xl p-6">
                <div class="flex items-center gap-3 pb-5 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-[#EAF1F6] text-primary flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-white">Cadre du stage</h2>
                        <p class="text-sm text-white/30">Dates, durée, nombre de places et conditions.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-4 gap-4 mt-5">
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Durée <span class="text-red-400">*</span></label>
                        <input type="number" name="duration_months" value="{{ old('duration_months', 3) }}"
                               min="1" max="24" required class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Places</label>
                        <input type="number" name="slots" value="{{ old('slots', 1) }}" min="1"
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Début <span class="text-red-400">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" required
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Limite</label>
                        <input type="date" name="application_deadline" value="{{ old('application_deadline') }}"
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mt-5">
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Ville</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="Casablanca, Rabat, Safi..."
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Gratification mensuelle</label>
                        <div class="relative">
                            <input type="number" name="stipend" value="{{ old('stipend') }}" min="0" placeholder="2000"
                                   class="input-dark w-full px-4 py-3 pr-14 rounded-xl text-sm">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-white/30">MAD</span>
                        </div>
                    </div>
                </div>

                <label class="mt-5 flex items-center justify-between gap-4 rounded-xl border border-white/5 bg-soft px-4 py-3 cursor-pointer">
                    <div>
                        <p class="font-semibold text-white text-sm">Télétravail possible</p>
                        <p class="text-xs text-white/30 mt-0.5">À activer si le stage peut se faire à distance ou hybride.</p>
                    </div>
                    <input type="checkbox" name="is_remote" value="1" x-model="remote"
                           class="w-5 h-5 rounded accent-teal">
                </label>
            </div>

            <div class="card-dark rounded-2xl p-6">
                <div class="flex items-center gap-3 pb-5 border-b border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-[#FFF7D6] text-[#7A5B00] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.784.57-1.838-.197-1.539-1.118l1.286-3.957a1 1 0 00-.364-1.118L4.06 9.384c-.783-.57-.38-1.81.588-1.81H8.81a1 1 0 00.95-.69l1.286-3.957z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-white">Profil recherché</h2>
                        <p class="text-sm text-white/30">Restez précis, sans transformer l'offre en liste interminable.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-[220px_1fr] gap-4 mt-5">
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Niveau requis</label>
                        <input type="text" name="required_level" value="{{ old('required_level') }}"
                               placeholder="Bac+2, Bac+3..."
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white/60 mb-2">Compétences clés</label>
                        <div class="grid md:grid-cols-3 gap-3">
                            @foreach($competences as $skill)
                                <input type="text" name="required_skills[]" value="{{ $skill }}"
                                       placeholder="Ex : Laravel"
                                       class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit"
                    class="btn-primary text-white font-semibold px-7 py-3.5 rounded-xl flex items-center justify-center gap-2">
                    Publier l'offre
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
                <a href="{{ route('entreprise.offres.index') }}"
                   class="btn-outline font-semibold px-7 py-3.5 rounded-xl text-center">
                    Annuler
                </a>
            </div>
        </div>

        <aside class="space-y-5 xl:sticky xl:top-24">
            <div class="card-dark rounded-2xl overflow-hidden">
                <div class="h-40 bg-[#1B3B59] relative">
                    <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?w=700&q=80"
                         alt="Équipe en discussion"
                         class="w-full h-full object-cover opacity-80">
                    <div class="absolute left-4 bottom-4 bg-white rounded-xl px-3 py-2">
                        <p class="text-xs font-bold text-primary">Aperçu public</p>
                    </div>
                </div>

                <div class="p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="badge-blue text-xs font-bold px-2.5 py-1 rounded-full">Stage</span>
                        <span class="badge-gray text-xs font-bold px-2.5 py-1 rounded-full" x-show="remote">Télétravail</span>
                    </div>
                    <h3 class="font-extrabold text-white text-lg">{{ old('title') ?: 'Titre de votre offre' }}</h3>
                    <p class="text-sm font-semibold mt-1" style="color:#FDD400;">
                        {{ $entreprise->company_name ?? auth()->user()->name }}
                    </p>
                    <div class="flex flex-wrap gap-2 mt-4 text-xs text-white/30">
                        <span>{{ old('city') ?: 'Ville' }}</span>
                        <span>{{ old('duration_months', 3) }} mois</span>
                        <span>{{ old('slots', 1) }} place(s)</span>
                    </div>
                    <p class="text-sm text-white/40 leading-relaxed mt-4">
                        {{ old('description') ? Str::limit(old('description'), 130) : 'Votre description apparaîtra ici avec les missions principales et le contexte du stage.' }}
                    </p>
                </div>
            </div>

            <div class="card-dark rounded-2xl p-5">
                <h3 class="font-bold text-white">Avant de publier</h3>
                <div class="space-y-3 mt-4">
                    @foreach([
                        'Titre compréhensible sans jargon interne',
                        'Missions concrètes et niveau attendu',
                        'Date limite cohérente avec la date de début',
                        'Compétences limitées aux vraies priorités',
                    ] as $item)
                        <div class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-[#E8F6EF] text-teal flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <p class="text-sm text-white/50">{{ $item }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-dark rounded-2xl p-5">
                <p class="text-white font-bold">Petit repère</p>
                <p class="text-white/70 text-sm leading-relaxed mt-2">
                    Une bonne offre donne envie sans promettre trop. Elle explique le travail réel, les outils, et ce que le stagiaire va apprendre.
                </p>
            </div>
        </aside>
    </form>
@endsection
