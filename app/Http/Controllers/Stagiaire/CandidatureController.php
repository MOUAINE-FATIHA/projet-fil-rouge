<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use App\Repositories\Contracts\CandidatureContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
public function __construct(private CandidatureContract $candidatures) {}
    public function index()
    {
        $stagiaireId = Auth::user()->profilStagiaire->id;
        $candidatures = $this->candidatures->parStagiaire($stagiaireId);
        return view('stagiaire.candidatures.index', compact('candidatures'));
    }

    public function create(Offre $offre)
    {
        abort_if($offre->estFermee(), 422, 'Cette offre n\'accepte plus de candidatures.');
        $stagiaireId = Auth::user()->profilStagiaire->id;
        abort_if(
            $this->candidatures->dejaPostule($stagiaireId, $offre->id),
            422,
            'Vous avez déjà postulé à cette offre.'
        );
        return view('stagiaire.candidatures.create', compact('offre'));
    }

    public function store(Request $request, Offre $offre)
    {
        $stagiaire = Auth::user()->profilStagiaire;

        abort_if($offre->estFermee(), 422, 'Cette offre n\'accepte plus de candidatures.');
        abort_unless($offre->aDesPlacesDisponibles(), 422, 'Plus de places disponibles.');
        abort_if(
            $this->candidatures->dejaPostule($stagiaire->id, $offre->id),
            422,
            'Vous avez déjà postulé à cette offre.'
        );

        $donnees = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'cv'=> ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'private');
        }

        $this->candidatures->creer([
            'student_id'   => $stagiaire->id,
            'offer_id'     => $offre->id,
            'cover_letter' => $donnees['cover_letter'] ?? null,
            'cv_path'      => $cvPath,
            'status'       => 'pending',
        ]);
        return redirect()
            ->route('stagiaire.candidatures.index')
            ->with('succes', 'Candidature envoyée avec succès.');
    }

    public function retirer(Candidature $candidature)
    {
        $this->verifierAppartenance($candidature);
        abort_if(
            in_array($candidature->status, ['accepted', 'rejected']),
            422,
            'Impossible de retirer une candidature déjà traitée.'
        );

        $this->candidatures->retirer($candidature->id);

        return redirect()
            ->route('stagiaire.candidatures.index')
            ->with('succes', 'Candidature retirée.');
    }

    private function verifierAppartenance(Candidature $candidature): void
    {
        $stagiaireId = Auth::user()->profilStagiaire->id;
        abort_if($candidature->student_id !== $stagiaireId, 403);
    }
}