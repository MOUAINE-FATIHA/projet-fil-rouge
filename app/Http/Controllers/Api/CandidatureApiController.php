<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CandidatureResource;
use App\Http\Resources\StageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidatureApiController extends Controller
{
    /**
     * GET /api/stagiaire/candidatures
     * Mes candidatures (stagiaire connecté)
     */
    public function mesCandidatures(Request $request): JsonResponse
    {
        $stagiaire = $request->user()->profilStagiaire;

        $candidatures = $stagiaire->candidatures()
            ->with(['offre.entreprise'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => CandidatureResource::collection($candidatures),
            'total'   => $candidatures->count(),
        ]);
    }

    /**
     * GET /api/stagiaire/stages
     * Mes stages (stagiaire connecté)
     */
    public function mesStages(Request $request): JsonResponse
    {
        $stagiaire = $request->user()->profilStagiaire;

        $stages = $stagiaire->stages()
            ->with(['candidature.offre.entreprise'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => StageResource::collection($stages),
            'total'   => $stages->count(),
        ]);
    }

    /**
     * GET /api/entreprise/candidatures
     * Candidatures reçues pour toutes les offres de l'entreprise
     */
    public function candidaturesEntreprise(Request $request): JsonResponse
    {
        $entreprise = $request->user()->profilEntreprise;

        $candidatures = \App\Models\Candidature::whereHas('offre', fn ($q) =>
            $q->where('company_id', $entreprise->id)
        )
        ->with(['offre', 'stagiaire.user'])
        ->when($request->statut, fn ($q) => $q->where('status', $request->statut))
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data'    => CandidatureResource::collection($candidatures),
            'total'   => $candidatures->count(),
        ]);
    }
}