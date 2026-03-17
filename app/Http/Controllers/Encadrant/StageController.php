<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    // Mes stages assignés
    public function index()
    {
        $encadrant = Auth::user()->profilEncadrant;

        $stages = $encadrant->stages()
            ->with([
                'candidature.offre.entreprise.user',
                'candidature.stagiaire.user',
            ])
            ->latest()
            ->paginate(10);

        return view('encadrant.stages.index', compact('stages'));
    }

    // Détail d'un stage
    public function show(Stage $stage)
    {
        $this->verifierAcces($stage);

        $stage->load([
            'candidature.offre.entreprise.user',
            'candidature.stagiaire.user',
        ]);

        return view('encadrant.stages.show', compact('stage'));
    }

    // Ajouter un compte-rendu
    public function compteRendu(Request $request, Stage $stage)
    {
        $this->verifierAcces($stage);

        $donnees = $request->validate([
            'compte_rendu' => ['required', 'string', 'max:3000'],
        ]);

        // On stocke dans student_feedback pour simplifier
        $stage->update(['student_feedback' => $donnees['compte_rendu']]);

        return back()->with('succes', 'Compte-rendu enregistré.');
    }

    private function verifierAcces(Stage $stage): void
    {
        $encadrantId = Auth::user()->profilEncadrant->id;
        abort_if($stage->supervisor_id !== $encadrantId, 403);
    }
}