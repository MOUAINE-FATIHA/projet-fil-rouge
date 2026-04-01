<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InternshipOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternshipOfferController extends Controller
{
    /**
     * GET /api/offers
     * Liste publique des offres avec filtres
     */
    public function index(Request $request): JsonResponse
    {
        $query = InternshipOffer::with('company.user')
            ->open();

        // Filtres
        if ($request->filled('domain')) {
            $query->byDomain($request->domain);
        }
        if ($request->filled('city')) {
            $query->byCity($request->city);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('is_remote')) {
            $query->where('is_remote', $request->boolean('is_remote'));
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search}%")
                  ->orWhere('description', 'LIKE', "%{$request->search}%");
            });
        }

        $offers = $query->latest()->paginate(12);

        return response()->json($offers);
    }

    /**
     * POST /api/offers
     * Créer une offre (entreprise uniquement)
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', InternshipOffer::class);

        $data = $request->validate([
            'title'                => ['required', 'string', 'max:255'],
            'description'          => ['required', 'string'],
            'domain'               => ['nullable', 'string', 'max:100'],
            'type'                 => ['required', 'in:pfe,pfa,summer,other'],
            'duration_months'      => ['required', 'integer', 'min:1', 'max:24'],
            'start_date'           => ['required', 'date', 'after:today'],
            'end_date'             => ['nullable', 'date', 'after:start_date'],
            'application_deadline' => ['nullable', 'date', 'before:start_date'],
            'city'                 => ['nullable', 'string'],
            'is_remote'            => ['boolean'],
            'stipend'              => ['nullable', 'numeric', 'min:0'],
            'required_skills'      => ['nullable', 'array'],
            'required_level'       => ['nullable', 'string'],
            'slots'                => ['integer', 'min:1'],
        ]);

        $company = $request->user()->companyProfile;

        abort_unless($company?->isApproved(), 403, 'Votre compte entreprise doit être validé.');

        $offer = $company->offers()->create($data);

        return response()->json([
            'message' => 'Offre créée avec succès.',
            'offer'   => $offer->load('company.user'),
        ], 201);
    }

    /**
     * GET /api/offers/{offer}
     */
    public function show(InternshipOffer $offer): JsonResponse
    {
        $offer->load([
            'company.user',
            'applications' => fn ($q) => $q->select('id', 'offer_id', 'status'),
        ]);

        return response()->json(['offer' => $offer]);
    }

    /**
     * PUT /api/offers/{offer}
     */
    public function update(Request $request, InternshipOffer $offer): JsonResponse
    {
        $this->authorize('update', $offer);

        $data = $request->validate([
            'title'                => ['sometimes', 'string', 'max:255'],
            'description'          => ['sometimes', 'string'],
            'domain'               => ['nullable', 'string'],
            'type'                 => ['sometimes', 'in:pfe,pfa,summer,other'],
            'duration_months'      => ['sometimes', 'integer', 'min:1'],
            'start_date'           => ['sometimes', 'date'],
            'end_date'             => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date'],
            'city'                 => ['nullable', 'string'],
            'is_remote'            => ['boolean'],
            'stipend'              => ['nullable', 'numeric', 'min:0'],
            'required_skills'      => ['nullable', 'array'],
            'required_level'       => ['nullable', 'string'],
            'slots'                => ['integer', 'min:1'],
            'status'               => ['sometimes', 'in:draft,published,closed'],
        ]);

        $offer->update($data);

        return response()->json([
            'message' => 'Offre mise à jour.',
            'offer'   => $offer->fresh('company.user'),
        ]);
    }

    /**
     * DELETE /api/offers/{offer}
     */
    public function destroy(InternshipOffer $offer): JsonResponse
    {
        $this->authorize('delete', $offer);

        $offer->delete();

        return response()->json(['message' => 'Offre supprimée.']);
    }

    /**
     * GET /api/company/offers
     * Offres de l'entreprise connectée
     */
    public function myOffers(Request $request): JsonResponse
    {
        $offers = $request->user()
            ->companyProfile
            ->offers()
            ->withCount(['applications', 'acceptedApplications'])
            ->latest()
            ->paginate(10);

        return response()->json($offers);
    }
}
