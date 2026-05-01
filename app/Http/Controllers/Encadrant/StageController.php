<?php

namespace App\Http\Controllers\Encadrant;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Notifications\ConventionValidee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        
        $stage->update(['student_feedback' => $donnees['compte_rendu']]);

        return back()->with('succes', 'Compte-rendu enregistré.');
    }

    public function telechargerConvention(Stage $stage)
    {
        $this->verifierAcces($stage);

        abort_unless($stage->convention_path, 404, 'Aucune convention déposée.');
        abort_if(str_starts_with($stage->convention_path, 'cvs/'), 422, 'Le fichier enregistré comme convention ressemble à un CV.');
        abort_unless(Storage::disk('private')->exists($stage->convention_path), 404, 'Le fichier de convention est introuvable.');

        return Storage::disk('private')->download($stage->convention_path, 'convention-stage-' . $stage->id . '.pdf');
    }

    public function validerConvention(Stage $stage)
    {
        $this->verifierAcces($stage);
        abort_unless($stage->convention_path, 422, 'La convention doit être déposée avant validation.');

        $stage->update([
            'convention_status' => 'validated',
            'convention_validated_at' => now(),
        ]);

        $stage->loadMissing([
            'candidature.offre.entreprise.user',
            'candidature.stagiaire.user',
        ]);

        $stage->candidature->stagiaire->user->notify(new ConventionValidee($stage));
        $stage->candidature->offre->entreprise->user->notify(new ConventionValidee($stage));

        return back()->with('succes', 'Convention validée avec succès.');
    }

    private function verifierAcces(Stage $stage): void
    {
        $encadrantId = Auth::user()->profilEncadrant->id;
        abort_if($stage->supervisor_id !== $encadrantId, 403);
    }
}
