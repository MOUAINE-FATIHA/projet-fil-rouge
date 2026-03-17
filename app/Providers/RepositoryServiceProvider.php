<?php

namespace App\Providers;

use App\Repositories\CandidatureRepo;
use App\Repositories\Contracts\CandidatureContract;
use App\Repositories\Contracts\OffreContract;
use App\Repositories\OffreRepo;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            OffreContract::class,
            OffreRepo::class
        );

        $this->app->bind(
            CandidatureContract::class,
            CandidatureRepo::class
        );
    }
}