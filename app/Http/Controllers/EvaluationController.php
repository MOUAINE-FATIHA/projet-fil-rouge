<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Internship;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * POST /api/internships/{internship}/evaluations
     * Créer ou mettre à jour une évaluation
     */
    public function store(Request $request, Internship $internship): JsonResponse
    {
        $user = $request->user();

        // Déterminer le type d'évaluateur
        $evaluatorType = match (true) {
            $user->isCompany()    => 'company',
            $user->isSupervisor() => 'supervisor',
            default               => abort(403, 'Seules les entreprises et les encadrants peuvent évaluer.'),
        };

        $data = $request->validate([
            'technical_score'     => ['nullable', 'integer', 'min:0', 'max:20'],
            'behavior_score'      => ['nullable', 'integer', 'min:0', 'max:20'],
            'communication_score' => ['nullable', 'integer', 'min:0', 'max:20'],
            'autonomy_score'      => ['nullable', 'integer', 'min:0', 'max:20'],
            'overall_score'       => ['nullable', 'integer', 'min:0', 'max:20'],
            'comments'            => ['nullable', 'string', 'max:3000'],
            'is_final'            => ['boolean'],
        ]);

        $evaluation = Evaluation::updateOrCreate(
            [
                'internship_id' => $internship->id,
                'evaluator_id'  => $user->id,
                'evaluator_type' => $evaluatorType,
            ],
            $data
        );

        return response()->json([
            'message'    => 'Évaluation enregistrée.',
            'evaluation' => $evaluation,
        ], 201);
    }

    /**
     * GET /api/internships/{internship}/evaluations
     */
    public function index(Request $request, Internship $internship): JsonResponse
    {
        $this->authorize('view', $internship);

        $evaluations = $internship->evaluations()->with('evaluator')->get();

        return response()->json(['evaluations' => $evaluations]);
    }
}
