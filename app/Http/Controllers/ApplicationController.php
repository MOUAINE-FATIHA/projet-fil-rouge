<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function myApplications(Request $request): JsonResponse
    {
        $applications = $request->user()
            ->studentProfile
            ->applications()
            ->with([
                'offer.company.user',
                'internship',
            ])
            ->latest()
            ->paginate(10);

        return response()->json($applications);
    }

    public function apply(Request $request, InternshipOffer $offer): JsonResponse
    {
        $student = $request->user()->studentProfile;

        abort_unless($request->user()->isStudent(), 403, 'Seuls les étudiants peuvent postuler.');
        abort_if($offer->isClosed(), 422, 'Cette offre n\'accepte plus de candidatures.');
        abort_unless($offer->hasAvailableSlots(), 422, 'Plus de places disponibles.');

        $exists = $student->applications()->where('offer_id', $offer->id)->exists();
        abort_if($exists, 422, 'Vous avez déjà postulé à cette offre.');

        $data = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'cv'           => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'private');
        }

        $application = $student->applications()->create([
            'offer_id'     => $offer->id,
            'cover_letter' => $data['cover_letter'] ?? null,
            'cv_path'      => $cvPath,
            'status'       => 'pending',
        ]);

        return response()->json([
            'message'     => 'Candidature envoyée avec succès.',
            'application' => $application->load('offer.company.user'),
        ], 201);
    }

    public function withdraw(Request $request, Application $application): JsonResponse
    {
        $this->authorize('withdraw', $application);

        abort_if(
            in_array($application->status, ['accepted', 'rejected']),
            422,
            'Impossible de retirer une candidature déjà traitée.'
        );

        $application->update(['status' => 'withdrawn']);

        return response()->json(['message' => 'Candidature retirée.']);
    }

    public function offerApplications(Request $request, InternshipOffer $offer): JsonResponse
    {
        $this->authorize('viewApplications', $offer);

        $applications = $offer->applications()
            ->with('student.user')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return response()->json($applications);
    }

    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        $this->authorize('updateStatus', $application);

        $data = $request->validate([
            'status'   => ['required', 'in:accepted,rejected,reviewing'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['status'] === 'accepted') {
            abort_unless($application->offer->hasAvailableSlots(), 422, 'Plus de places disponibles.');
            $application->accept($data['feedback'] ?? null);
        } elseif ($data['status'] === 'rejected') {
            $application->reject($data['feedback'] ?? null);
        } else {
            $application->update(['status' => $data['status']]);
        }

        return response()->json([
            'message'     => 'Statut de la candidature mis à jour.',
            'application' => $application->fresh('offer', 'internship'),
        ]);
    }
}
