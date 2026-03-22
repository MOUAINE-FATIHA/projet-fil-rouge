<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Internship::with([
            'application.student.user',
            'application.offer.company.user',
            'supervisor.user',
        ]);

        if ($user->isStudent()) {
            $studentId = $user->studentProfile->id;
            $query->whereHas('application', fn ($q) => $q->where('student_id', $studentId));
        } elseif ($user->isCompany()) {
            $companyId = $user->companyProfile->id;
            $query->whereHas('application.offer', fn ($q) => $q->where('company_id', $companyId));
        } elseif ($user->isSupervisor()) {
            $query->where('supervisor_id', $user->supervisorProfile->id);
        }

        $internships = $query->latest()->paginate(10);

        return response()->json($internships);
    }

    public function show(Request $request, Internship $internship): JsonResponse
    {
        $this->authorize('view', $internship);

        $internship->load([
            'application.student.user',
            'application.offer.company.user',
            'supervisor.user',
            'evaluations.evaluator',
        ]);

        return response()->json(['internship' => $internship]);
    }

    public function updateStatus(Request $request, Internship $internship): JsonResponse
    {
        $this->authorize('updateStatus', $internship);

        $data = $request->validate([
            'status'            => ['required', 'in:not_started,in_progress,completed,interrupted'],
            'actual_start_date' => ['nullable', 'date'],
            'actual_end_date'   => ['nullable', 'date'],
        ]);

        $internship->update($data);

        return response()->json([
            'message'    => 'Statut du stage mis à jour.',
            'internship' => $internship->fresh(),
        ]);
    }

    public function assignSupervisor(Request $request, Internship $internship): JsonResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validate([
            'supervisor_id' => ['required', 'exists:supervisor_profiles,id'],
        ]);

        $internship->update(['supervisor_id' => $data['supervisor_id']]);

        return response()->json([
            'message'    => 'Encadrant assigné.',
            'internship' => $internship->fresh('supervisor.user'),
        ]);
    }

    
    public function uploadDocument(Request $request, Internship $internship): JsonResponse
    {
        $this->authorize('uploadDocument', $internship);

        $data = $request->validate([
            'type' => ['required', 'in:convention,report'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $path = $request->file('file')->store(
            "internships/{$internship->id}/{$data['type']}s",
            'private'
        );

        $field = $data['type'] === 'convention' ? 'convention_path' : 'report_path';
        $internship->update([$field => $path]);

        return response()->json([
            'message' => ucfirst($data['type']) . ' téléversé avec succès.',
            'path'    => $path,
        ]);
    }

    public function studentFeedback(Request $request, Internship $internship): JsonResponse
    {
        abort_unless($request->user()->isStudent(), 403);
        abort_unless($internship->isCompleted(), 422, 'Le stage doit être terminé.');

        $data = $request->validate([
            'feedback' => ['required', 'string', 'max:3000'],
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $internship->update([
            'student_feedback' => $data['feedback'],
            'student_rating'   => $data['rating'],
        ]);

        return response()->json(['message' => 'Retour enregistré avec succès.']);
    }
}
