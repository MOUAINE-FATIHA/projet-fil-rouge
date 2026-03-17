<?php

namespace App\Http\Controllers\Stagiaire;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    public function index()
    {
        $stagiaire = Auth::user()->profilStagiaire;
        $stages = $stagiaire->stages()
            ->with('candidature.offre.entreprise.user')
            ->latest()
            ->paginate(10);
        return view('stagiaire.stages.index', compact('stages'));
    }
}