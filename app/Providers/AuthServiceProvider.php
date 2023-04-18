<?php

namespace App\Providers;

use App\Models\{
    Regra,
    Usuarios
};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $rules = Regra::all();
        foreach ($rules as $rule) {
            Gate::define($rule->nome, function (Usuarios $user) use ($rule) {
                return $user->hasPermission($rule->nome);
            });
        }

        Gate::before(function (Usuarios $user) {
            if ($user->isRoot()) {
                return true;
            }
        });
    }
}
