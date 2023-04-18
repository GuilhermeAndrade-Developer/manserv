<?php

namespace App\Observers;

use App\Models\AprovacaoRequisicaoVeiculoUt;
use App\Models\RequisicaoVeiculos;
use App\Models\DetalhesRequisicaoVeiculos;
use App\Models\GestoresUt;
use Carbon\Carbon;

class AprovacaoObserver
{
    /**
     * Handle the aprovacao requisicao veiculo ut "created" event.
     *
     * @param  AprovacaoRequisicaoVeiculoUt  $aprovacao
     * @return void
     */
    public function created(AprovacaoRequisicaoVeiculoUt $aprovacao)
    {
        $requisicaoVeiculos = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->first();  
       
        $aprovacoes =  AprovacaoRequisicaoVeiculoUt::where('id_num_requisicao',$aprovacao->id_num_requisicao)->count();
        $max = 3;

        $gerente = GestoresUt::where('id_ut_cc',$aprovacao->id_ut_cc)->where('id_gestor','<>',$aprovacao->id_gestor)->where('tipo_gestor','G')->first();
        $diretor = GestoresUt::where('id_ut_cc',$aprovacao->id_ut_cc)->where('id_gestor','<>',$aprovacao->id_gestor)->where('tipo_gestor','D')->first();
        $vice = GestoresUt::where('id_ut_cc',$aprovacao->id_ut_cc)->where('id_gestor','<>',$aprovacao->id_gestor)->where('tipo_gestor','V')->first();

       if($aprovacao->tipo_gestor=='F' && $requisicaoVeiculos->tipo!='F'){
           $atualizar = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->update(['status'=>8]);
       }
       elseif($aprovacao->tipo_gestor=='F' && $requisicaoVeiculos->tipo=='F')
       {
            $detalhes = DetalhesRequisicaoVeiculos::where('id_requisicao',$aprovacao->id_num_requisicao)->get();
            $ultima = RequisicaoVeiculos::all()->last();
            $ultima = ($ultima==null)? null: $ultima->numero;

            $clone = new Requisicao();
            $clone->numero = numeroRequisicao($ultima);
            $clone->coligada_id = $requisicaoVeiculos->coligada_id;
            $clone->id_ut_cc = $requisicaoVeiculos->id_ut_cc;
            $clone->previsto_fpv = $requisicaoVeiculos->previsto_fpv;
            $clone->id_requisitante = $requisicaoVeiculos->id_requisitante;
            $clone->data_requisicao = $requisicaoVeiculos->data_requisicao;
            $clone->status = $requisicaoVeiculos->status;
            $clone->observacao = $requisicaoVeiculos->observacao;
            $clone->tipo = $requisicaoVeiculos->tipo;
            $clone->id_relacionada = $aprovacao->id_num_requisicao;
            $clone->save();
            $atualizar = null;
        
            foreach($detalhes as $detalhe){
                if($atualizar == null){
                    $atualizar[] = ['data_retirada'=>Carbon::parse($detalhe->data_retirada)->add('90 days'),'prazo_locacao'=>($detalhe->prazo_locacao-3),'status'=>6];
                }
            
                $detalheClone = new DetalhesRequisicaoVeiculos();
                $detalheClone->id_requisicao =  $clone->id;
                $detalheClone->categoria_veiculo =  $detalhe->categoria_veiculo;
                $detalheClone->id_condutor =  $detalhe->id_condutor;
                $detalheClone->data_retirada =  $detalhe->data_retirada;
                $detalheClone->prazo_locacao =  3;
                $detalheClone->data_devolucao =  carbon::parse($detalhe->data_retirada)->add('90 days');
                $detalheClone->cidade_retirada =  $detalhe->cidade_retirada;
                $detalheClone->estado_retirada =  $detalhe->estado_retirada;
                $detalheClone->local_rodagem =  $detalhe->local_rodagem;
                $detalheClone->km_mensal =  $detalhe->km_mensal;
                $detalheClone->Limite_cartao_combustível =  $detalhe->Limite_cartao_combustível;

            }
        
            $updateRequisicao = DetalhesRequisicaoVeiculos::where('id_requisicao',$aprovacao->id_num_requisicao)->update($atualizar);       
     
       }else
       {
        if($aprovacao->status==0)
        {
            $atualizar = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->update(['status'=>11]);
            
        }
        
        if($gerente==null && $diretor==null && $vice==null)
        {
            $atualizar = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->update(['status'=>7]);
        }

        if($gerente!=null && $requisicaoVeiculos->status < 2)
        {
            $atualizar = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->update(['status'=>2]);
        }

        if($diretor!=null && $requisicaoVeiculos->status == 2)
        {
            $atualizar = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->update(['status'=>3]);
        }

        if($vice!=null && $requisicaoVeiculos->status == 3)
        {
            $atualizar = RequisicaoVeiculos::where('id',$aprovacao->id_num_requisicao)->update(['status'=>7]);
        }

       }       
        
    }

    /**
     * Handle the aprovacao requisicao veiculo ut "updated" event.
     *
     * @param  AprovacaoRequisicaoVeiculoUt  $aprovacao
     * @return void
     */
    public function updated(AprovacaoRequisicaoVeiculoUt $aprovacao)
    {
        //
    }

    /**
     * Handle the aprovacao requisicao veiculo ut "deleted" event.
     *
     * @param  AprovacaoRequisicaoVeiculoUt  $aprovacao
     * @return void
     */
    public function deleted(AprovacaoRequisicaoVeiculoUt $aprovacao)
    {
        //
    }

    /**
     * Handle the aprovacao requisicao veiculo ut "restored" event.
     *
     * @param  AprovacaoRequisicaoVeiculoUt  $aprovacao
     * @return void
     */
    public function restored(AprovacaoRequisicaoVeiculoUt $aprovacao)
    {
        //
    }

    /**
     * Handle the aprovacao requisicao veiculo ut "force deleted" event.
     *
     * @param  AprovacaoRequisicaoVeiculoUt  $aprovacao
     * @return void
     */
    public function forceDeleted(AprovacaoRequisicaoVeiculoUt $aprovacao)
    {
        //
    }
}
