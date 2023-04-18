<?php

namespace App\Observers;

use App\Models\PendenciasVeiculos;

class PendenciaObserver
{
    /**
     * Handle the pendencias veiculos "created" event.
     *
     * @param  \App\PendenciasVeiculos  $pendenciasVeiculos
     * @return void
     */
    public function created(PendenciasVeiculos $pendenciasVeiculos)
    {
        //
    }

    /**
     * Handle the pendencias veiculos "updated" event.
     *
     * @param  \App\PendenciasVeiculos  $pendenciasVeiculos
     * @return void
     */
    public function updated(PendenciasVeiculos $pendenciasVeiculos)
    {
        //
    }

    /**
     * Handle the pendencias veiculos "deleted" event.
     *
     * @param  \App\PendenciasVeiculos  $pendenciasVeiculos
     * @return void
     */
    public function deleted(PendenciasVeiculos $pendenciasVeiculos)
    {
        //
    }

    /**
     * Handle the pendencias veiculos "restored" event.
     *
     * @param  \App\PendenciasVeiculos  $pendenciasVeiculos
     * @return void
     */
    public function restored(PendenciasVeiculos $pendenciasVeiculos)
    {
        //
    }

    /**
     * Handle the pendencias veiculos "force deleted" event.
     *
     * @param  \App\PendenciasVeiculos  $pendenciasVeiculos
     * @return void
     */
    public function forceDeleted(PendenciasVeiculos $pendenciasVeiculos)
    {
        //
    }
}
