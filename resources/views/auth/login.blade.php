@extends('layouts.guest')
@section('titre', 'Connexion')

@section('nav-action')
    <span class="text-white/40 text-sm">Pas encore de compte ?</span>
    <a href="{{ route('register') }}"
       class="border border-white/10 text-white text-sm font-medium px-4 py-2 rounded-xl hover:border-primary hover:text-primary transition">
        S'inscrire
    </a>
@endsection

@section('contenu')
<div class="w-full max-w-md">

    {{-- En-tête --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Ravi de vous revoir</h1>
        <p class="text-white/40 text-sm">Connectez-vous pour accéder à votre espace.</p>
    </div>

    {{-- Carte --}}
    <div class="glass rounded-2xl p-8">

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

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
                           class="input-dark w-full pl-11 pr-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <button type="submit"
                class="btn-primary w-full text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2">
                Se connecter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <p class="text-center text-sm text-white/30 mt-6">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-primary hover:text-blue-400 font-medium transition">S'inscrire gratuitement</a>
        </p>
    </div>
</div>
@endsection