<?php

namespace App\Providers;

use App\Observers\RequisicaoObserver;
use App\Observers\AprovacaoObserver;
use App\Observers\DetalheObserver;
use App\Observers\PendenciaObserver;
use App\Observers\ChecklistObserver;

use App\Models\DetalheVeiculo;
use App\Models\AprovacaoRequisicaoVeiculoUt;
use App\Models\Checklists;
use App\Models\RequisicaoVeiculos;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        RequisicaoVeiculos::observe(RequisicaoObserver::class);
        AprovacaoRequisicaoVeiculoUt::observe(AprovacaoObserver::class);
        Checklists::observe(ChecklistObserver::class);  
        DetalheVeiculo::observe(DetalheObserver::class);
    }
}
