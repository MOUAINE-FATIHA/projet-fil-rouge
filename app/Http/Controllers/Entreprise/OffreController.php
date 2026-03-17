<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Repositories\Contracts\OffreContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function __construct(private OffreContract $offres) {}

    public function index()
    {
        $entreprise = Auth::user()->profilEntreprise;
        $offres = $this->offres->parEntrepriseAvecStats($entreprise->id);

        return view('entreprise.offres.index', compact('offres'));
    }

    public function create()
    {
        return view('entreprise.offres.create');
    }

    public function store(Request $request)
    {
        $entreprise = Auth::user()->profilEntreprise;

        abort_unless($entreprise->estValidee(), 403,
            'Votre compte entreprise doit être validé avant de publier des offres.'
        );

        $donnees = $request->validate([
            'title'                => ['required', 'string', 'max:255'],
            'description'          => ['required', 'string'],
            'domain'               => ['nullable', 'string', 'max:100'],
            'type'                 => ['required', 'in:pfe,pfa,summer,other'],
            'duration_months'      => ['required', 'integer', 'min:1', 'max:24'],
            'start_date'           => ['required', 'date', 'after:today'],
            'end_date'             => ['nullable', 'date', 'after:start_date'],
            'application_deadline' => ['nullable', 'date'],
            'city'                 => ['nullable', 'string'],
            'is_remote'            => ['boolean'],
            'stipend'              => ['nullable', 'numeric', 'min:0'],
            'required_skills'      => ['nullable', 'array'],
            'required_level'       => ['nullable', 'string'],
            'slots'                => ['integer', 'min:1'],
        ]);

        $this->offres->creer(array_merge($donnees, [
            'company_id' => $entreprise->id,
            'status'     => 'published',
        ]));

        return redirect()
            ->route('entreprise.offres.index')
            ->with('succes', 'Offre publiée avec succès.');
    }

    public function show(Offre $offre)
    {
        $this->verifierAppartenance($offre);
        $offre->load('candidatures.stagiaire.user');

        return view('entreprise.offres.show', compact('offre'));
    }

    public function edit(Offre $offre)
    {
        $this->verifierAppartenance($offre);
        return view('entreprise.offres.edit', compact('offre'));
    }
    public function update(Request $request, Offre $offre)
    {
        $this->verifierAppartenance($offre);

        $donnees = $request->validate([
            'title'                => ['required', 'string', 'max:255'],
            'description'          => ['required', 'string'],
            'domain'               => ['nullable', 'string'],
            'type'                 => ['required', 'in:pfe,pfa,summer,other'],
            'duration_months'      => ['required', 'integer', 'min:1'],
            'start_date'           => ['required', 'date'],
            'end_date'             => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date'],
            'city'                 => ['nullable', 'string'],
            'is_remote'            => ['boolean'],
            'stipend'              => ['nullable', 'numeric', 'min:0'],
            'required_level'       => ['nullable', 'string'],
            'slots'                => ['integer', 'min:1'],
            'status'               => ['required', 'in:draft,published,closed'],
        ]);

        $this->offres->modifier($offre->id, $donnees);

        return redirect()
            ->route('entreprise.offres.index')
            ->with('succes', 'Offre modifiée avec succès.');
    }

    public function destroy(Offre $offre)
    {
        $this->verifierAppartenance($offre);
        $this->offres->supprimer($offre->id);

        return redirect()
            ->route('entreprise.offres.index')
            ->with('succes', 'Offre supprimée.');
    }

    private function verifierAppartenance(Offre $offre): void
    {
        $entrepriseId = Auth::user()->profilEntreprise->id;
        abort_if($offre->company_id !== $entrepriseId, 403);
    }
}