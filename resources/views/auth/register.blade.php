@extends('layouts.guest')
@section('titre', 'Inscription')

@section('nav-action')
    <div class="flex items-center justify-end gap-2 text-sm">
        <span class="text-bluegray">Déjà membre ?</span>
        <a href="{{ route('login') }}" class="font-bold text-navy hover:text-teal transition">Connexion</a>
    </div>
@endsection

@section('contenu')
    <div>
        <h1 class="text-3xl font-extrabold text-navy">Créer un compte</h1>
        <p class="text-bluegray text-sm mt-2">Choisissez votre rôle et rejoignez StageConnect.</p>
    </div>

    @if($errors->any())
        <div class="mt-5 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="mt-7 space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-extrabold text-bluegray uppercase mb-2">Je suis</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="role-option cursor-pointer">
                    <input type="radio" name="role" value="stagiaire" class="sr-only"
                           {{ old('role', 'stagiaire') === 'stagiaire' ? 'checked' : '' }}>
                    <span class="block text-center py-3 rounded text-sm font-bold transition">Étudiant</span>
                </label>
                <label class="role-option cursor-pointer">
                    <input type="radio" name="role" value="entreprise" class="sr-only"
                           {{ old('role') === 'entreprise' ? 'checked' : '' }}>
                    <span class="block text-center py-3 rounded text-sm font-bold transition">Entreprise</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Nom complet</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="Votre nom complet" required
                   class="input-dark w-full px-4 py-3 rounded text-sm">
        </div>

        <div>
            <label class="block text-sm font-bold text-ink mb-2">Adresse e-mail</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="vous@email.com" required
                   class="input-dark w-full px-4 py-3 rounded text-sm">
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Mot de passe</label>
                <input type="password" name="password" placeholder="Minimum 8 caractères" required
                       class="input-dark w-full px-4 py-3 rounded text-sm">
            </div>
            <div>
                <label class="block text-sm font-bold text-ink mb-2">Confirmation</label>
                <input type="password" name="password_confirmation" placeholder="Confirmer" required
                       class="input-dark w-full px-4 py-3 rounded text-sm">
            </div>
        </div>

        <button type="submit" class="btn-primary w-full font-bold py-3 rounded">
            Créer mon compte
        </button>
    </form>
@endsection
