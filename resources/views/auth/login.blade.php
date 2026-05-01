@extends('layouts.guest')
@section('titre', 'Connexion')

@section('nav-action')
    <div class="flex items-center justify-end gap-2 text-sm">
        <span class="text-bluegray">Pas encore de compte ?</span>
        <a href="{{ route('register') }}" class="font-bold text-navy hover:text-teal transition">S'inscrire</a>
    </div>
@endsection

@section('contenu')
    <div>
        <h1 class="text-3xl font-extrabold text-navy">Bon retour</h1>
        <p class="text-bluegray text-sm mt-2">Connectez-vous pour accéder à votre espace StageConnect.</p>
    </div>

    @if($errors->any())
        <div class="mt-5 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="vous@email.com" required
                   class="input-dark w-full px-4 py-3 rounded text-sm">
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Mot de passe</label>
            <input type="password" name="password" placeholder="Votre mot de passe" required
                   class="input-dark w-full px-4 py-3 rounded text-sm">
        </div>

        <label class="flex items-center gap-2 text-sm text-bluegray">
            <input type="checkbox" name="remember" class="rounded border-line">
            Se souvenir de moi
        </label>

        <button type="submit" class="btn-primary w-full font-bold py-3 rounded">
            Se connecter
        </button>
    </form>
@endsection
