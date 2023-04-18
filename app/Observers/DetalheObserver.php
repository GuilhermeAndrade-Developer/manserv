<?php

namespace App\Observers;

use App\Modals\DetalheVeiculo;
use App\Modals\Pendencia;

class DetalheObserver
{
    /**
     * Handle the detalhe veiculo "created" event.
     *
     * @param  \App\DetalheVeiculo  $detalheVeiculo
     * @return void
     */
    public function created(DetalheVeiculo $detalheVeiculo)
    {
        $manutencao = DetalheVeiculo::where('veiculo_id',$detalheVeiculo->veiculo_id)->where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo','Manutencao')->where('valor_final', null)->first();
        $oleo = DetalheVeiculo::where('veiculo_id',$detalheVeiculo->veiculo_id)->where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo','Oleo')->where('valor_final', null)->first();
        $km = DetalheVeiculo::where('veiculo_id',$detalheVeiculo->veiculo_id)->where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo','KM')->where('valor_final', null)->first();
        $combustivel = DetalheVeiculo::where('veiculo_id',$detalheVeiculo->veiculo_id)->where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo','Combustivel')->where('valor_final', null)->first();
        
    
            if($detalheVeiculo->tipo == "KM" & $km->valor_inicial < $detalheVeiculo->valor_inicial){
             
               $update = DetalheVeiculo::where('id',$km->id)->update(['valor_final'=>$detalheVeiculo->valor_inicial,'atualizado'=>Carbon::now()->toDateString()]);
               
               
               if($oleo->valor_inicial > $detalheVeiculo->valor_inicial)
               {
                    $pendencia = new Pendencia();
                    $pendencia->tipo =  6;
                    $pendencia->condutor_id = $detalheVeiculo->condutor_id;                    
                    $pendencia->abertura = Carbon::now()->toDateString();
                    $pendencia->fechamento =  null;
                    $pendencia->status =  1;
                    $pendencia->save();
               }
               if($manutencao->valor_inicial < $detalheVeiculo->valor_inicial)
               {
                    $pendencia = new Pendencia();
                    $pendencia->tipo =  8;
                    $pendencia->condutor_id = $detalheVeiculo->condutor_id;                    
                    $pendencia->abertura = Carbon::now()->toDateString();
                    $pendencia->fechamento =  null;                  
                    $pendencia->status =  1;
                    $pendencia->save();
               }
            }elseif($detalheVeiculo->tipo == "Oleo" && $oleo->valor_inicial < $detalheVeiculo->valor_inicial){

                $pendencia = Pendencia::where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo',6)->where('fechamento',null)->get();
                $updatePen = Pendencia::where('id',$pendencia->id)->update(['fechamento'=>Carbon::now()->format('Y-m-d H:i:s'),'status'=>0]);

                $update = DetalheVeiculo::where('id',$oleo->id)->update(['valor_final'=>$detalheVeiculo->valor_inicial,'atualizado'=>Carbon::now()->toDateString()]); 

            }elseif($detalheVeiculo->tipo == "Manuencao" && $manutencao->valor_inicial < $detalheVeiculo->valor_inicial){

                $pendencia = Pendencia::where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo',8)->where('fechamento',null)->get();
                $updatePen = Pendencia::where('id',$pendencia->id)->update(['fechamento'=>Carbon::now()->format('Y-m-d H:i:s'),'status'=>0]);

                $update = DetalheVeiculo::where('id',$manutencao->id)->update(['valor_final'=>$detalheVeiculo->valor_inicial,'atualizado'=>Carbon::now()->toDateString()]); 

            }elseif($detalheVeiculo->tipo == "Combustivel" && $combustivel->valor_inicial < $detalheVeiculo->valor_inicial){
                
                $pendencia = Pendencia::where('condutor_id',$detalheVeiculo->condutor_id)->where('tipo',7)->where('fechamento',null)->get();
                $updatePen = Pendencia::where('id',$pendencia->id)->update(['fechamento'=>Carbon::now()->format('Y-m-d H:i:s'),'status'=>0]);

                $update = DetalheVeiculo::where('id',$combustivel->id)->update(['valor_final'=>$detalheVeiculo->valor_inicial,'atualizado'=>Carbon::now()->toDateString()]);

            }
    }

    /**
     * Handle the detalhe veiculo "updated" event.
     *
     * @param  \App\DetalheVeiculo  $detalheVeiculo
     * @return void
     */
    public function updated(DetalheVeiculo $detalheVeiculo)
    {
        /*$pendencia = PendenciasVeiculos::where('origem','detalhe')->where('tipo','D')->where('id_origem',$detalhe->id)->get();
        if($pendencia!=null){
            $pendencia->fechamento = $detalheVeiculo->atualizado;
            $pendencia->status = true;
            $pendencia->save();
        }*/
    }

    /**
     * Handle the detalhe veiculo "deleted" event.
     *
     * @param  \App\DetalheVeiculo  $detalheVeiculo
     * @return void
     */
    public function deleted(DetalheVeiculo $detalheVeiculo)
    {
        //
    }

    /**
     * Handle the detalhe veiculo "restored" event.
     *
     * @param  \App\DetalheVeiculo  $detalheVeiculo
     * @return void
     */
    public function restored(DetalheVeiculo $detalheVeiculo)
    {
        //
    }

    /**
     * Handle the detalhe veiculo "force deleted" event.
     *
     * @param  \App\DetalheVeiculo  $detalheVeiculo
     * @return void
     */
    public function forceDeleted(DetalheVeiculo $detalheVeiculo)
    {
        //
    }
}
