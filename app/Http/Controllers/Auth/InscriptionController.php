<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class InscriptionController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
    public function inscrire(Request $request)
    {
        $donnees = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:stagiaire,entreprise'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create($donnees);

        if ($user->estStagiaire()) {
            $user->profilStagiaire()->create([]);
        } elseif ($user->estEntreprise()) {
            $user->profilEntreprise()->create([
                'company_name' => $user->name,
            ]);
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}