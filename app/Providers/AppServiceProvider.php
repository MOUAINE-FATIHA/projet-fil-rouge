<?php

namespace App\Providers;
use App\Models\Candidature;
use App\Models\Offre;
use App\Policies\CandidaturePolicy;
use App\Policies\OffrePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Offre::class       => OffrePolicy::class,
        Candidature::class => CandidaturePolicy::class,
    ];
    public function register(): void {}
    public function boot(): void
    {
        $this->registerPolicies();
    }
}