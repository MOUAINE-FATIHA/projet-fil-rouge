<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\ProfilEntreprise;
use App\Models\ProfilStagiaire;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //  Admin 
        User::create([
            'name'              => 'Admin StageConnect',
            'email'             => 'admin@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // ── Encadrant 
        $encadrantUser = User::create([
            'name'              => 'Prof. Hassan Alami',
            'email'             => 'encadrant@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'encadrant',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        \App\Models\ProfilEncadrant::create([
            'user_id'        => $encadrantUser->id,
            'department'     => 'Informatique',
            'university'     => 'YouCode Safi',
            'specialization' => 'Génie Logiciel',
            'max_students'   => 8,
        ]);

        // ── Entreprise 
        $entrepriseUser = User::create([
            'name'              => 'TechMaroc Solutions',
            'email'             => 'entreprise@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'entreprise',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $entreprise = ProfilEntreprise::create([
            'user_id'           => $entrepriseUser->id,
            'company_name'      => 'TechMaroc Solutions',
            'industry'          => 'Technologies de l\'information',
            'size'              => 'PME',
            'city'              => 'Casablanca',
            'country'           => 'Maroc',
            'description'       => 'Entreprise spécialisée dans le développement web et mobile.',
            'validation_status' => 'approved',
            'validated_at'      => now(),
        ]);

        // ── Offres 
        $offre1 = Offre::create([
            'company_id'           => $entreprise->id,
            'title'                => 'Développeur Full Stack Laravel / Vue.js',
            'description'          => 'Stage de fin d\'études en développement web full stack. Vous travaillerez sur des projets clients variés avec une équipe agile.',
            'domain'               => 'Développement Web',
            'type'                 => 'pfe',
            'duration_months'      => 4,
            'start_date'           => now()->addMonth(),
            'application_deadline' => now()->addWeeks(3),
            'city'                 => 'Casablanca',
            'is_remote'            => false,
            'stipend'              => 2000,
            'required_skills'      => ['Laravel', 'Vue.js', 'MySQL', 'Git'],
            'required_level'       => 'Bac+2',
            'slots'                => 2,
            'status'               => 'published',
        ]);

        $offre2 = Offre::create([
            'company_id'           => $entreprise->id,
            'title'                => 'Stage Data Science & Machine Learning',
            'description'          => 'Participation au développement de modèles de ML pour l\'analyse prédictive des données clients.',
            'domain'               => 'Data Science',
            'type'                 => 'pfa',
            'duration_months'      => 3,
            'start_date'           => now()->addMonths(2),
            'application_deadline' => now()->addMonth(),
            'city'                 => 'Rabat',
            'is_remote'            => true,
            'stipend'              => 2500,
            'required_skills'      => ['Python', 'Pandas', 'Scikit-learn', 'SQL'],
            'required_level'       => 'Bac+3',
            'slots'                => 1,
            'status'               => 'published',
        ]);

        // ── Stagiaires 
        $stagiaireUser1 = User::create([
            'name'              => 'Fatiha Mouaine',
            'email'             => 'stagiaire@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'stagiaire',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $stagiaire1 = ProfilStagiaire::create([
            'user_id'         => $stagiaireUser1->id,
            'student_id'      => 'YC2024001',
            'field_of_study'  => 'Développement Web & Mobile',
            'academic_level'  => 'Bac+2',
            'university'      => 'YouCode Safi',
            'city'            => 'Safi',
            'skills'          => ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'MySQL'],
            'languages'       => ['Arabe', 'Français', 'Anglais'],
            'graduation_year' => 2025,
        ]);

        $stagiaireUser2 = User::create([
            'name'              => 'Youssef Benali',
            'email'             => 'stagiaire2@stageconnect.ma',
            'password'          => Hash::make('password'),
            'role'              => 'stagiaire',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $stagiaire2 = ProfilStagiaire::create([
            'user_id'         => $stagiaireUser2->id,
            'student_id'      => 'YC2024002',
            'field_of_study'  => 'Data Science',
            'academic_level'  => 'Bac+3',
            'university'      => 'YouCode Safi',
            'city'            => 'Casablanca',
            'skills'          => ['Python', 'Machine Learning', 'SQL', 'Power BI'],
            'languages'       => ['Arabe', 'Français'],
            'graduation_year' => 2025,
        ]);

        // ── Candidature acceptée → stage créé ────────────────────
        $candidature = Candidature::create([
            'student_id'   => $stagiaire1->id,
            'offer_id'     => $offre1->id,
            'cover_letter' => 'Je suis très motivée pour rejoindre votre équipe dans le cadre de mon stage de fin d\'études. Mes compétences en Laravel et Vue.js correspondent parfaitement à vos besoins.',
            'status'       => 'accepted',
            'reviewed_at'  => now(),
        ]);

        Stage::create([
            'application_id'    => $candidature->id,
            'actual_start_date' => now()->addMonth(),
            'status'            => 'not_started',
        ]);

        // ── Candidature en attente ────────────────────────────────
        Candidature::create([
            'student_id'   => $stagiaire2->id,
            'offer_id'     => $offre2->id,
            'cover_letter' => 'Passionné de data science, je souhaite mettre mes compétences Python et ML au service de vos projets innovants.',
            'status'       => 'pending',
        ]);

        $this->command->info('Base de données peuplée avec succès !');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Admin',      'admin@stageconnect.ma',      'password'],
                ['Encadrant',  'encadrant@stageconnect.ma',  'password'],
                ['Entreprise', 'entreprise@stageconnect.ma', 'password'],
                ['Stagiaire 1','stagiaire@stageconnect.ma',  'password'],
                ['Stagiaire 2','stagiaire2@stageconnect.ma', 'password'],
            ]
        );
    }
}