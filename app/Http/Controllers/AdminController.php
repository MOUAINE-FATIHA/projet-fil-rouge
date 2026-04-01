<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Internship;
use App\Models\InternshipOffer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()?->isAdmin(), 403, 'Accès réservé aux administrateurs.');
            return $next($request);
        });
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'users' => [
                'total'       => User::count(),
                'students'    => User::where('role', 'student')->count(),
                'companies'   => User::where('role', 'company')->count(),
                'supervisors' => User::where('role', 'supervisor')->count(),
            ],
            'companies' => [
                'pending'  => CompanyProfile::where('validation_status', 'pending')->count(),
                'approved' => CompanyProfile::where('validation_status', 'approved')->count(),
                'rejected' => CompanyProfile::where('validation_status', 'rejected')->count(),
            ],
            'offers' => [
                'total'     => InternshipOffer::count(),
                'published' => InternshipOffer::where('status', 'published')->count(),
                'closed'    => InternshipOffer::where('status', 'closed')->count(),
            ],
            'applications' => [
                'total'    => Application::count(),
                'pending'  => Application::where('status', 'pending')->count(),
                'accepted' => Application::where('status', 'accepted')->count(),
                'rejected' => Application::where('status', 'rejected')->count(),
            ],
            'internships' => [
                'total'       => Internship::count(),
                'in_progress' => Internship::where('status', 'in_progress')->count(),
                'completed'   => Internship::where('status', 'completed')->count(),
            ],
        ];

        return response()->json(['stats' => $stats]);
    }

    public function pendingCompanies(): JsonResponse
    {
        $companies = CompanyProfile::with('user')
            ->where('validation_status', 'pending')
            ->latest()
            ->paginate(15);

        return response()->json($companies);
    }

    public function validateCompany(Request $request, CompanyProfile $company): JsonResponse
    {
        $data = $request->validate([
            'action'           => ['required', 'in:approve,reject'],
            'rejection_reason' => ['required_if:action,reject', 'nullable', 'string'],
        ]);

        if ($data['action'] === 'approve') {
            $company->update([
                'validation_status' => 'approved',
                'validated_at'      => now(),
                'validated_by'      => $request->user()->id,
                'rejection_reason'  => null,
            ]);
            $message = 'Entreprise approuvée.';
        } else {
            $company->update([
                'validation_status' => 'rejected',
                'rejection_reason'  => $data['rejection_reason'],
            ]);
            $message = 'Entreprise rejetée.';
        }

        return response()->json(['message' => $message, 'company' => $company->fresh()]);
    }

    public function users(Request $request): JsonResponse
    {
        $users = User::with('studentProfile', 'companyProfile', 'supervisorProfile')
            ->when($request->role, fn ($q) => $q->where('role', $request->role))
            ->when($request->search, fn ($q) => $q->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('email', 'LIKE', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return response()->json($users);
    }

    public function toggleUser(User $user): JsonResponse
    {
        abort_if($user->isAdmin(), 422, 'Impossible de désactiver un administrateur.');

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';

        return response()->json(['message' => "Compte {$status}.", 'user' => $user]);
    }
}
