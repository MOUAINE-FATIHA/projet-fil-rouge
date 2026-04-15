<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OffreResource;
use App\Repositories\Contracts\OffreRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OffreApiController extends Controller
{
    public function __construct(
        private OffreRepositoryContract $offres
    ) {}

    /**
     * GET /api/offres
     * Liste des offres ouvertes avec filtres
     */
    public function index(Request $request): JsonResponse
    {
        $offres = $this->offres->toutesOuvertes($request->only([
            'domaine', 'ville', 'type', 'recherche',
        ]));
        return response()->json([
            'success' => true,
            'data'    => OffreResource::collection($offres->items()),
            'meta'    => [
                'total'        => $offres->total(),
                'par_page'     => $offres->perPage(),
                'page_actuelle'=> $offres->currentPage(),
                'derniere_page'=> $offres->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/offres/{id}
     * Détail d'une offre
     */
    public function show(int $id): JsonResponse
    {
        $offre = $this->offres->trouverParId($id);

        return response()->json([
            'success' => true,
            'data'    => new OffreResource($offre),
        ]);
    }
}