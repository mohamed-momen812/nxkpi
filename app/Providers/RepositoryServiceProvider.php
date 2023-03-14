<?php

namespace App\Providers;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\EntryRepositoryInterface;
use App\Interfaces\KpiRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\EntryRepository;
use App\Repositories\KpiRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseRepositoryInterface::class,BaseRepository::class);
        $this->app->bind(EntryRepositoryInterface::class,EntryRepository::class);
        $this->app->bind(KpiRepositoryInterface::class,KpiRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
