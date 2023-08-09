<?php

namespace App\Providers;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EntryRepositoryInterface;
use App\Interfaces\GroupRepositoryInterface;
use App\Interfaces\KpiRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\EntryRepository;
use App\Repositories\GroupRepository;
use App\Repositories\KpiRepository;
use App\Repositories\UserRepository;
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
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(GroupRepositoryInterface::class,GroupRepository::class);
        $this->app->bind(DashboardRepository::class);
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
