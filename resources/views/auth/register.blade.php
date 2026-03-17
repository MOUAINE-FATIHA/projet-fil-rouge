@extends('layouts.guest')
@section('titre', 'Inscription')

@section('nav-action')
    <span class="text-white/40 text-sm">Déjà membre ?</span>
    <a href="{{ route('login') }}"
       class="border border-white/10 text-white text-sm font-medium px-4 py-2 rounded-xl hover:border-primary hover:text-primary transition">
        Connexion
    </a>
@endsection

@section('contenu')
<div class="w-full max-w-lg">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Créer un compte</h1>
        <p class="text-white/40 text-sm">Rejoignez la plateforme de stages de YouCode Safi.</p>
    </div>

    <div class="glass rounded-2xl p-8">

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
            @csrf

            {{-- Sélecteur de rôle --}}
            <div>
                <label class="block text-xs font-bold text-white/40 uppercase tracking-widest mb-3">Je suis...</label>
                <div class="grid grid-cols-2 gap-2 p-1 rounded-xl" style="background:rgba(255,255,255,0.04);">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="stagiaire" class="peer sr-only"
                               {{ old('role', 'stagiaire') === 'stagiaire' ? 'checked' : '' }}>
                        <span class="block text-center py-2.5 rounded-lg text-sm font-semibold text-white/40
                                     peer-checked:bg-primary peer-checked:text-white transition">
                            Étudiant
                        </span>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="entreprise" class="peer sr-only"
                               {{ old('role') === 'entreprise' ? 'checked' : '' }}>
                        <span class="block text-center py-2.5 rounded-lg text-sm font-semibold text-white/40
                                     peer-checked:bg-primary peer-checked:text-white transition">
                            Entreprise
                        </span>
                    </label>
                </div>
            </div>

            {{-- Nom --}}
            <div>
                <label class="block text-sm font-medium text-white/60 mb-2">Nom complet</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Votre nom complet" required
                           class="input-dark w-full pl-11 pr-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-white/60 mb-2">Adresse e-mail</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="vous@email.com" required
                           class="input-dark w-full pl-11 pr-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            {{-- Mots de passe --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Mot de passe</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" placeholder="••••••••" required
                               class="input-dark w-full pl-11 pr-3 py-3 rounded-xl text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-2">Confirmation</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required
                           class="input-dark w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <button type="submit"
                class="btn-primary w-full text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2">
                Créer mon compte
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>
    </div>
</div>
@endsection