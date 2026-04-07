<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Repositories\Contracts\OffreContract;
use Illuminate\Http\Request;

class OffreController extends Controller
{
public function __construct(private OffreContract $offres) {}
    public function index(Request $request)
    {
        $offres = $this->offres->toutesOuvertes($request->only([
            'domaine', 'ville', 'type', 'recherche',
        ]));
        return view('stagiaire.offres.index', compact('offres'));
    }
    public function show(Offre $offre)
    {
        $offre->load('entreprise.user');
        return view('stagiaire.offres.show', compact('offre'));
    }
}