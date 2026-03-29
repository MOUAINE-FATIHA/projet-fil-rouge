<?php
namespace App\Http\Controllers\Entreprise;
use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use App\Repositories\Contracts\CandidatureContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
public function __construct(private CandidatureContract $candidatures) {}
    public function index(Offre $offre)
    {
        $this->verifierAppartenance($offre);
        $candidatures = $this->candidatures->parOffre(
            $offre->id,
            request('statut')
        );
        return view('entreprise.candidatures.index', compact('offre', 'candidatures'));
    }

    public function show(Candidature $candidature)
    {
        $this->verifierAcces($candidature);
        $candidature->load('stagiaire.user', 'offre');

        return view('entreprise.candidatures.show', compact('candidature'));
    }

    public function accepter(Request $request, Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        abort_unless(
            $candidature->offre->aDesPlacesDisponibles(),
            422,
            'Plus de places disponibles pour cette offre.'
        );
        $donnees = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);
        $this->candidatures->accepter($candidature->id, $donnees['feedback'] ?? null);

        return redirect()
            ->route('entreprise.candidatures.index', $candidature->offer_id)
            ->with('succes', 'Candidature acceptée. Le stage a été créé automatiquement.');
    }

    public function refuser(Request $request, Candidature $candidature)
    {
        $this->verifierAcces($candidature);

        $donnees = $request->validate([
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->candidatures->refuser($candidature->id, $donnees['feedback'] ?? null);

        return redirect()
            ->route('entreprise.candidatures.index', $candidature->offer_id)
            ->with('succes', 'Candidature refusée.');
    }

    private function verifierAppartenance(Offre $offre): void
    {
        $entrepriseId = Auth::user()->profilEntreprise->id;
        abort_if($offre->company_id !== $entrepriseId, 403);
    }

    private function verifierAcces(Candidature $candidature): void
    {
        $this->verifierAppartenance($candidature->offre);
    }
}