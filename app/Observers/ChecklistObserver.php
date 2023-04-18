<?php

namespace App\Observers;

use App\Models\Checklists;
use App\Models\Pendencia;
use App\Models\Veiculos;
use App\Models\VouchersLocadoras;
use App\Models\DetalhesRequisicaoVeiculos;
use Carbon\Carbon;

class ChecklistObserver
{
    /**
     * Handle the checklists "created" event.
     *
     * @param  \App\Checklists  $checklists
     * @return void
     */

     /*
                        id_condutor"=>$tempChk['id_condutor'],
                        "placa"=>$tempChk['placaVeiculo'],
                        "kilometragem"=>$tempChk['kilometragem'],
                        "data_chklist"=>$tempChk['dataCadastro'],
                        "tipo_chklist"=>$tempChk['tipoCheck'],
                        "id_ut_cc"=>$tempChk['id_ut_cc'],
                        "checklist"=>"",
                        "observacao"=>$tempChk['observacao']
     */
    public function created(Checklists $checklists)
    {
        $json_checklist = json_decode($checklists->checklist);
        $veiculo = Veiculos::where('placa',$checklists->placa)->first();
        if($veiculo==null){
            $veiculo = new Veiculos();
            $veiculo->placa = $checklists->placa;
            $veiculo->id_ut_cc =  $checklists->id_ut_cc;  
            $veiculo->save();

            //$voucher = VouchersLocadoras::where('id_ut_cc',$checklists->id_ut_cc)->where('id_condutor',$checklists->id_condutor)->get()->last();
           // DetalhesRequisicaoVeiculos::where('id_condutor',$checklists->id_condutor)->where('id_condutor',$checklists->id_condutor)->where('id_voucher',$voucher->id)->update('status_veiculo',1);
        }
    }

    /**
     * Handle the checklists "updated" event.
     *
     * @param  \App\Checklists  $checklists
     * @return void
     */
    public function updated(Checklists $checklists)
    {
        $pendencia = Pendencia::where('id_condutor',$checklists)->where('tipo',$checklists)->where('fechamento',null)->get();
        if($pendencia!=null){
            Pendencia::where('id',$pendencia->id)->update(['fechamento'=>Carbon::now()->format('Y-m-d H:i:s'),'status'=>0]);
        }
    }

    /**
     * Handle the checklists "deleted" event.
     *
     * @param  \App\Checklists  $checklists
     * @return void
     */
    public function deleted(Checklists $checklists)
    {
        //
    }

    /**
     * Handle the checklists "restored" event.
     *
     * @param  \App\Checklists  $checklists
     * @return void
     */
    public function restored(Checklists $checklists)
    {
        //
    }

    /**
     * Handle the checklists "force deleted" event.
     *
     * @param  \App\Checklists  $checklists
     * @return void
     */
    public function forceDeleted(Checklists $checklists)
    {
        //
    }
}
