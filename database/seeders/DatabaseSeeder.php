<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\Internship;
use App\Models\InternshipOffer;
use App\Models\StudentProfile;
use App\Models\SupervisorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────────────────
        $admin = User::create([
            'name'              => 'Admin StageConnect',
            'email'             => 'admin@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // ── Encadrant ────────────────────────────────────────────────────────
        $supervisorUser = User::create([
            'name'              => 'Prof. Hassan Alami',
            'email'             => 'supervisor@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'supervisor',
            'email_verified_at' => now(),
        ]);
        $supervisor = SupervisorProfile::create([
            'user_id'        => $supervisorUser->id,
            'department'     => 'Informatique',
            'university'     => 'YouCode Safi',
            'specialization' => 'Génie Logiciel',
            'max_students'   => 8,
        ]);

        // ── Entreprise ───────────────────────────────────────────────────────
        $companyUser = User::create([
            'name'              => 'TechMaroc Solutions',
            'email'             => 'company@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'company',
            'email_verified_at' => now(),
        ]);
        $company = CompanyProfile::create([
            'user_id'           => $companyUser->id,
            'company_name'      => 'TechMaroc Solutions',
            'industry'          => 'Technologies de l\'information',
            'size'              => 'PME',
            'city'              => 'Casablanca',
            'country'           => 'Maroc',
            'description'       => 'Entreprise spécialisée dans le développement web et mobile.',
            'validation_status' => 'approved',
            'validated_at'      => now(),
            'validated_by'      => $admin->id,
        ]);

        // ── Offres de stage ──────────────────────────────────────────────────
        $offer1 = InternshipOffer::create([
            'company_id'           => $company->id,
            'title'                => 'Développeur Full Stack Laravel/Vue.js',
            'description'          => 'Stage de fin d\'études en développement web full stack. Vous travaillerez sur des projets clients variés.',
            'domain'               => 'Développement Web',
            'type'                 => 'pfe',
            'duration_months'      => 4,
            'start_date'           => now()->addMonth(),
            'application_deadline' => now()->addWeeks(3),
            'city'                 => 'Casablanca',
            'is_remote'            => false,
            'stipend'              => 2000,
            'required_skills'      => ['Laravel', 'Vue.js', 'MySQL', 'Git'],
            'required_level'       => 'Bac+3',
            'slots'                => 2,
            'status'               => 'published',
        ]);

        $offer2 = InternshipOffer::create([
            'company_id'           => $company->id,
            'title'                => 'Stage Data Science & Machine Learning',
            'description'          => 'Participation au développement de modèles de ML pour l\'analyse prédictive.',
            'domain'               => 'Data Science',
            'type'                 => 'pfa',
            'duration_months'      => 3,
            'start_date'           => now()->addMonths(2),
            'application_deadline' => now()->addMonth(),
            'city'                 => 'Rabat',
            'is_remote'            => true,
            'stipend'              => 2500,
            'required_skills'      => ['Python', 'Pandas', 'Scikit-learn', 'SQL'],
            'required_level'       => 'Bac+4',
            'slots'                => 1,
            'status'               => 'published',
        ]);

        // ── Étudiants ────────────────────────────────────────────────────────
        $studentUser1 = User::create([
            'name'              => 'Fatiha Mouaine',
            'email'             => 'student@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);
        $student1 = StudentProfile::create([
            'user_id'        => $studentUser1->id,
            'student_id'     => 'YC2024001',
            'field_of_study' => 'Développement Web & Mobile',
            'academic_level' => 'Bac+2',
            'university'     => 'YouCode Safi',
            'city'           => 'Safi',
            'skills'         => ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'MySQL'],
            'languages'      => ['Arabe', 'Français', 'Anglais'],
            'graduation_year' => 2025,
        ]);

        $studentUser2 = User::create([
            'name'              => 'Youssef Benali',
            'email'             => 'student2@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);
        $student2 = StudentProfile::create([
            'user_id'        => $studentUser2->id,
            'student_id'     => 'YC2024002',
            'field_of_study' => 'Data Science',
            'academic_level' => 'Bac+3',
            'university'     => 'YouCode Safi',
            'city'           => 'Casablanca',
            'skills'         => ['Python', 'Machine Learning', 'SQL', 'Power BI'],
            'languages'      => ['Arabe', 'Français'],
            'graduation_year' => 2025,
        ]);

        // ── Candidature + Stage ───────────────────────────────────────────────
        $application = Application::create([
            'student_id'   => $student1->id,
            'offer_id'     => $offer1->id,
            'cover_letter' => 'Je suis très motivée pour rejoindre votre équipe dans le cadre de mon stage de fin d\'études...',
            'status'       => 'accepted',
            'reviewed_at'  => now(),
        ]);

        Internship::create([
            'application_id'    => $application->id,
            'supervisor_id'     => $supervisor->id,
            'actual_start_date' => now()->addMonth(),
            'status'            => 'not_started',
        ]);

        // Candidature en attente
        Application::create([
            'student_id'   => $student2->id,
            'offer_id'     => $offer2->id,
            'cover_letter' => 'Passionné de data science, je souhaite mettre mes compétences au service de vos projets...',
            'status'       => 'pending',
        ]);

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Admin',      'admin@stageconnect.ma',      'password'],
                ['Encadrant',  'supervisor@stageconnect.ma', 'password'],
                ['Entreprise', 'company@stageconnect.ma',    'password'],
                ['Étudiant 1', 'student@stageconnect.ma',    'password'],
                ['Étudiant 2', 'student2@stageconnect.ma',   'password'],
            ]
        );
    }
}
