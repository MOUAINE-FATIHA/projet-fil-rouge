<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\ProfilEntreprise;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'stagiaires'   => User::where('role', 'stagiaire')->count(),
            'entreprises'  => User::where('role', 'entreprise')->count(),
            'offres'       => Offre::count(),
            'candidatures' => Candidature::count(),
            'stages'       => Stage::count(),
            'en_attente'   => ProfilEntreprise::where('validation_status', 'pending')->count(),
        ];

        $entreprises_recentes = ProfilEntreprise::with('user')
            ->where('validation_status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        return view('admin.dashboard', compact('stats', 'entreprises_recentes'));
    }

    public function utilisateurs(Request $request)
    {
        $utilisateurs = User::with('profilStagiaire', 'profilEntreprise')
            ->when($request->role, fn ($q) => $q->where('role', $request->role))
            ->when($request->recherche, fn ($q) => $q
                ->where('name', 'like', "%{$request->recherche}%")
                ->orWhere('email', 'like', "%{$request->recherche}%")
            )->latest()
            ->paginate(15);
        return view('admin.utilisateurs', compact('utilisateurs'));
    }
    public function toggleUtilisateur(User $user)
    {
        abort_if($user->estAdmin(), 403, 'Impossible de modifier un administrateur.');
        $user->update(['is_active' => !$user->is_active]);
        $statut = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('succes', "Compte {$statut} avec succès.");
    }

    public function entreprises(Request $request)
    {
        $entreprises = ProfilEntreprise::with('user')
            ->when($request->statut, fn ($q) => $q->where('validation_status', $request->statut))
            ->latest()
            ->paginate(15);
        return view('admin.entreprises', compact('entreprises'));
    }

    public function validerEntreprise(ProfilEntreprise $entreprise)
    {
        $entreprise->update([
            'validation_status' => 'approved',
            'validated_at'      => now(),
            'rejection_reason'  => null,
        ]);
        return back()->with('succes', "Entreprise « {$entreprise->company_name} » validée avec succès.");
    }

    public function rejeterEntreprise(Request $request, ProfilEntreprise $entreprise)
    {
        $donnees = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $entreprise->update([
            'validation_status' => 'rejected',
            'rejection_reason'  => $donnees['rejection_reason'],
        ]);

        return back()->with('succes', "Entreprise « {$entreprise->company_name} » rejetée.");
    }
    public function stages()
    {
        $stages = Stage::with([
            'candidature.offre.entreprise',
            'candidature.stagiaire.user',
        ])
        ->latest()
        ->paginate(15);
        return view('admin.stages', compact('stages'));
    }

    public function assignerEncadrant(Request $request, Stage $stage)
    {
        $donnees = $request->validate([
            'supervisor_id' => ['required', 'exists:supervisor_profiles,id'],
        ]);

        $stage->update(['supervisor_id' => $donnees['supervisor_id']]);

        $encadrant = \App\Models\ProfilEncadrant::find($donnees['supervisor_id']);
        $encadrant->user->notify(
            new \App\Notifications\StageAssigne($stage->fresh('candidature.offre.entreprise', 'candidature.stagiaire.user'))
        );

        return back()->with('succes', 'Encadrant assigné avec succès.');
    }
}