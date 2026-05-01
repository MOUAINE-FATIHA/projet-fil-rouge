@extends('layouts.app')
@section('titre', 'Utilisateurs')

@section('sidebar-links')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Tableau de bord
    </a>
    <a href="{{ route('admin.entreprises') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Entreprises
    </a>
    <a href="{{ route('admin.utilisateurs') }}" class="sidebar-link active">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Utilisateurs
    </a>
    <a href="{{ route('admin.stages') }}" class="sidebar-link">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Stages
    </a>
@endsection


@section('contenu')

    <div class="card-dark rounded-2xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <span class="badge-blue text-xs font-bold px-3 py-1 rounded-full">Comptes</span>
                <h1 class="text-2xl font-extrabold text-white mt-4">Gestion des utilisateurs</h1>
                <p class="text-white/40 text-sm mt-1">Recherchez un compte et gérez son accès à la plateforme.</p>
            </div>
            <div class="rounded-xl bg-soft border border-white/5 px-4 py-3">
                <p class="text-xs text-white/30 font-bold uppercase">Résultats</p>
                <p class="text-2xl font-extrabold text-white">{{ $utilisateurs->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET" action="{{ route('admin.utilisateurs') }}" class="card-dark rounded-2xl p-4 flex flex-wrap gap-3 mb-6">
        <input type="text" name="recherche" value="{{ request('recherche') }}"
               placeholder="Nom ou email..."
               class="input-dark px-4 py-2 rounded-xl text-sm w-64">
        <select name="role" class="input-dark px-4 py-2 rounded-xl text-sm">
            <option value="">Tous les rôles</option>
            <option value="stagiaire"  {{ request('role') === 'stagiaire'  ? 'selected' : '' }}>Stagiaires</option>
            <option value="entreprise" {{ request('role') === 'entreprise' ? 'selected' : '' }}>Entreprises</option>
            <option value="admin"      {{ request('role') === 'admin'      ? 'selected' : '' }}>Admins</option>
        </select>
        <button type="submit" class="btn-primary text-white text-sm font-medium px-4 py-2 rounded-xl">Filtrer</button>
        @if(request()->hasAny(['recherche','role']))
            <a href="{{ route('admin.utilisateurs') }}" class="text-white/30 hover:text-red-400 text-sm self-center transition">✕</a>
        @endif
    </form>

    <div class="space-y-3">
        @forelse($utilisateurs as $user)
            <div class="card-dark rounded-2xl p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-white text-sm">{{ $user->name }}</p>
                        <p class="text-xs text-white/30">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="badge-gray text-xs font-medium px-2.5 py-1 rounded-lg capitalize">{{ $user->role }}</span>
                    @if($user->is_active)
                        <span class="badge-accepted text-xs font-medium px-2.5 py-1 rounded-lg">Actif</span>
                    @else
                        <span class="badge-rejected text-xs font-medium px-2.5 py-1 rounded-lg">Désactivé</span>
                    @endif

                    @unless($user->estAdmin())
                        <form method="POST" action="{{ route('admin.utilisateurs.toggle', $user) }}">
                            @csrf
                            <button type="submit"
                                class="text-xs font-medium px-3 py-1.5 rounded-lg border transition
                                       {{ $user->is_active
                                           ? 'border-red-400/20 text-red-400/70 hover:bg-red-400/10'
                                           : 'border-green-400/20 text-green-400/70 hover:bg-green-400/10' }}">
                                {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    @endunless
                </div>
            </div>
        @empty
            <div class="card-dark rounded-2xl p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#EAF1F6] text-teal flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-white mb-2">Aucun utilisateur trouvé</h2>
                <p class="text-white/30 font-medium mb-4">Essayez de modifier les filtres de recherche.</p>
                @if(request()->hasAny(['recherche','role']))
                    <a href="{{ route('admin.utilisateurs') }}" class="btn-primary inline-block text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                        Réinitialiser
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($utilisateurs->hasPages())
        <div class="mt-8 flex justify-center">{{ $utilisateurs->links() }}</div>
    @endif

@endsection
