@extends('layouts.app')
@section('titre', 'Mon profil')

@section('sidebar-links')
    <a href="{{ route('dashboard') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/>
        </svg>
        Mon espace
    </a>
@endsection

@section('contenu')
    <div class="mb-8">
        <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Compte</span>
        <h1 class="text-2xl font-extrabold text-white mt-4">Mon profil</h1>
        <p class="text-white/40 text-sm mt-1">
            Gardez vos informations à jour pour faciliter le suivi des stages et les échanges.
        </p>
    </div>

    <div class="grid xl:grid-cols-[1fr_380px] gap-6 items-start">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="card-dark rounded-2xl p-6 space-y-5">
                <div class="pb-3 border-b border-white/5">
                    <h2 class="font-bold text-white">Informations générales</h2>
                    <p class="text-sm text-white/30 mt-1">Ces informations sont communes à tous les comptes.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/60 mb-2">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/60 mb-2">Adresse e-mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white/60 mb-2">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="Ex : +212 6 00 00 00 00"
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/60 mb-2">Rôle</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm opacity-70">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Bio courte</label>
                    <textarea name="bio" rows="4"
                              placeholder="Quelques lignes utiles pour présenter votre profil."
                              class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </div>

            @if($user->estStagiaire())
                @php $profil = $user->profilStagiaire; @endphp
                <div class="card-dark rounded-2xl p-6 space-y-5">
                    <div class="pb-3 border-b border-white/5">
                        <h2 class="font-bold text-white">Profil stagiaire</h2>
                        <p class="text-sm text-white/30 mt-1">Ajoutez les informations utiles pour vos candidatures.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Filière</label>
                            <input type="text" name="field_of_study" value="{{ old('field_of_study', $profil->field_of_study ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Niveau académique</label>
                            <input type="text" name="academic_level" value="{{ old('academic_level', $profil->academic_level ?? '') }}"
                                   placeholder="Ex : Bac+2"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Établissement</label>
                            <input type="text" name="university" value="{{ old('university', $profil->university ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Ville</label>
                            <input type="text" name="city" value="{{ old('city', $profil->city ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Année de sortie</label>
                            <input type="number" name="graduation_year" value="{{ old('graduation_year', $profil->graduation_year ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">LinkedIn</label>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $profil->linkedin_url ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">GitHub</label>
                            <input type="url" name="github_url" value="{{ old('github_url', $profil->github_url ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Portfolio</label>
                            <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profil->portfolio_url ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>
                </div>
            @endif

            @if($user->estEntreprise())
                @php $profil = $user->profilEntreprise; @endphp
                <div class="card-dark rounded-2xl p-6 space-y-5">
                    <div class="pb-3 border-b border-white/5">
                        <h2 class="font-bold text-white">Profil entreprise</h2>
                        <p class="text-sm text-white/30 mt-1">Ces informations aident les stagiaires à comprendre votre structure.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Nom de l'entreprise</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $profil->company_name ?? $user->name) }}" required
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Secteur</label>
                            <input type="text" name="industry" value="{{ old('industry', $profil->industry ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Taille</label>
                            <input type="text" name="size" value="{{ old('size', $profil->size ?? '') }}"
                                   placeholder="PME, startup..."
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Ville</label>
                            <input type="text" name="city" value="{{ old('city', $profil->city ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Pays</label>
                            <input type="text" name="country" value="{{ old('country', $profil->country ?? 'Maroc') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Site web</label>
                            <input type="url" name="website" value="{{ old('website', $profil->website ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Registre de commerce</label>
                            <input type="text" name="rc_number" value="{{ old('rc_number', $profil->rc_number ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white/60 mb-2">Adresse</label>
                        <input type="text" name="address" value="{{ old('address', $profil->address ?? '') }}"
                               class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white/60 mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="input-dark w-full px-4 py-3 rounded-xl text-sm resize-none">{{ old('description', $profil->description ?? '') }}</textarea>
                    </div>
                </div>
            @endif

            @if($user->estEncadrant())
                @php $profil = $user->profilEncadrant; @endphp
                <div class="card-dark rounded-2xl p-6 space-y-5">
                    <div class="pb-3 border-b border-white/5">
                        <h2 class="font-bold text-white">Profil encadrant</h2>
                        <p class="text-sm text-white/30 mt-1">Complétez vos informations d'encadrement.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Département</label>
                            <input type="text" name="department" value="{{ old('department', $profil->department ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Université</label>
                            <input type="text" name="university" value="{{ old('university', $profil->university ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Spécialisation</label>
                            <input type="text" name="specialization" value="{{ old('specialization', $profil->specialization ?? '') }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white/60 mb-2">Capacité d'encadrement</label>
                            <input type="number" name="max_students" value="{{ old('max_students', $profil->max_students ?? 10) }}"
                                   class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>
                </div>
            @endif

            <button type="submit" class="btn-primary text-white font-semibold px-7 py-3 rounded-xl">
                Enregistrer les informations
            </button>
        </form>

        <div class="space-y-5">
            <div class="card-dark rounded-2xl p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-teal flex items-center justify-center text-white font-extrabold text-xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-white/30">{{ $user->email }}</p>
                        <span class="badge-blue text-xs font-bold px-2.5 py-1 rounded-full mt-2 inline-flex">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="card-dark rounded-2xl p-6 space-y-5">
                @csrf
                @method('PUT')

                <div class="pb-3 border-b border-white/5">
                    <h2 class="font-bold text-white">Sécurité</h2>
                    <p class="text-sm text-white/30 mt-1">Modifiez votre mot de passe quand c'est nécessaire.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Mot de passe actuel</label>
                    <input type="password" name="current_password" required
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password" required
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Confirmation</label>
                    <input type="password" name="password_confirmation" required
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>

                <button type="submit" class="btn-primary text-white font-semibold px-5 py-2.5 rounded-xl w-full">
                    Modifier le mot de passe
                </button>
            </form>
        </div>
    </div>
@endsection
