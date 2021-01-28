<?php

namespace App\Observers;

use App\Models\RequisicaoVeiculos;
use App\Models\GestoresUt;
 
class RequisicaoObserver
{
    /**
     * Handle the requisicao veiculos "created" event.
     *
     * @param  \App\RequisicaoVeiculos  $requisicaoVeiculos
     * @return void
     */
    public function created(RequisicaoVeiculos $requisicaoVeiculos)
    {
        $gerente = GestoresUt::where('id_ut_cc',$requisicaoVeiculos->id_ut_cc)->where('id_gestor','<>',$requisicaoVeiculos->id_requisitante)->where('tipo_gestor','G')->first();
        $diretor = GestoresUt::where('id_ut_cc',$requisicaoVeiculos->id_ut_cc)->where('id_gestor','<>',$requisicaoVeiculos->id_requisitante)->where('tipo_gestor','D')->first();
        $vice = GestoresUt::where('id_ut_cc',$requisicaoVeiculos->id_ut_cc)->where('id_gestor','<>',$requisicaoVeiculos->id_requisitante)->where('tipo_gestor','V')->first();
        
        if($gerente==null && $diretor==null && $vice==null){
            $requisicaoVeiculos->status = 7;
            $requisicaoVeiculos->save();
        }
        
        if($gerente!=null){
            $requisicaoVeiculos->status = 2;
            $requisicaoVeiculos->save();
        }

        if($gerente==null && $diretor!=null){
            $requisicaoVeiculos->status = 3;
            $requisicaoVeiculos->save();
        }

        if($gerente==null && $diretor==null && $vice!=null){
            $requisicaoVeiculos->status = 7;
            $requisicaoVeiculos->save();
        }      
       
    }

    /**
     * Handle the requisicao veiculos "updated" event.
     *
     * @param  \App\RequisicaoVeiculos  $requisicaoVeiculos
     * @return void
     */
    public function updated(RequisicaoVeiculos $requisicaoVeiculos)
    {
        //
    }

    /**
     * Handle the requisicao veiculos "deleted" event.
     *
     * @param  \App\RequisicaoVeiculos  $requisicaoVeiculos
     * @return void
     */
    public function deleted(RequisicaoVeiculos $requisicaoVeiculos)
    {
        //
    }

    /**
     * Handle the requisicao veiculos "restored" event.
     *
     * @param  \App\RequisicaoVeiculos  $requisicaoVeiculos
     * @return void
     */
    public function restored(RequisicaoVeiculos $requisicaoVeiculos)
    {
        //
    }

    /**
     * Handle the requisicao veiculos "force deleted" event.
     *
     * @param  \App\RequisicaoVeiculos  $requisicaoVeiculos
     * @return void
     */
    public function forceDeleted(RequisicaoVeiculos $requisicaoVeiculos)
    {
        //
    }
}
