<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load([
            'profilStagiaire',
            'profilEntreprise',
            'profilEncadrant',
        ]);

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($data);

        if ($user->estStagiaire()) {
            $profileData = $request->validate([
                'field_of_study' => ['nullable', 'string', 'max:255'],
                'academic_level' => ['nullable', 'string', 'max:100'],
                'university' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:120'],
                'linkedin_url' => ['nullable', 'url', 'max:255'],
                'github_url' => ['nullable', 'url', 'max:255'],
                'portfolio_url' => ['nullable', 'url', 'max:255'],
                'graduation_year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            ]);

            $user->profilStagiaire()->firstOrCreate([])->update($profileData);
        }

        if ($user->estEntreprise()) {
            $profileData = $request->validate([
                'company_name' => ['required', 'string', 'max:255'],
                'industry' => ['nullable', 'string', 'max:255'],
                'size' => ['nullable', 'string', 'max:100'],
                'website' => ['nullable', 'url', 'max:255'],
                'description' => ['nullable', 'string', 'max:1200'],
                'address' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:120'],
                'country' => ['nullable', 'string', 'max:120'],
                'rc_number' => ['nullable', 'string', 'max:120'],
            ]);

            $user->profilEntreprise()->firstOrCreate([
                'company_name' => $user->name,
            ])->update($profileData);
        }

        if ($user->estEncadrant()) {
            $profileData = $request->validate([
                'department' => ['nullable', 'string', 'max:255'],
                'university' => ['nullable', 'string', 'max:255'],
                'specialization' => ['nullable', 'string', 'max:255'],
                'max_students' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            $user->profilEncadrant()->firstOrCreate([])->update($profileData);
        }

        return back()->with('succes', 'Votre profil a été mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => $data['password'],
        ]);

        return back()->with('succes', 'Votre mot de passe a été modifié.');
    }
}
