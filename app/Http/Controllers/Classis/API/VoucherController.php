<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use Carbon\Carbon;
//use App\Http\Requests\AprovacaoVoucherRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\VouchersLocadoras;
Use App\Models\VouchersAnexos;
use App\Models\RequisicaoVeiculos;
use App\Models\DetalhesRequisicaoVeiculos;
use App\Models\Usuarios;
use App\Models\Locadoras;
Use App\Models\Cidades;
Use App\Models\Condutores;


use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\{
    Request,
    JsonResponse
};

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function lista()
    {
        try {
            $manserv_vouchers = VouchersLocadoras::all();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Não foi possível mostrar os Vouchers.',
                'code' => $e->getMessage(),
                'status' => 'ERROR'
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Vouchers.',
            'status' => 'OK',
            'data' => $manserv_vouchers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    // public function store(VoucherRequest $request)
    public function store(Request $request)
    {

        try {
            $dodos_request = $request->all();
            
            $dadosBusca = [
            'locadora' => $request->locadora,
                'id_detalhes_requisicao' => $request->id_detalhes_requisicao,
                'cidade_retirada' => $request->cidade_retirada,
                'cidade_devolucao' => $request->cidade_devolucao,
            ];
            
            $dadosRelacionamento = $this->retornarRelacionamentosVoucher($dadosBusca);
            if(isset($dadosRelacionamento['erro'])) {
                return response()->json([
                    'message' => $dadosRelacionamento['erro'],
                    'status' => 'ERROR'
                ], 412);
            }
            $dadosRelacionamento['data_cadastro'] = Carbon::now()->toDateString();
            
            $dodos_request = array_merge($dodos_request, $dadosRelacionamento);
            DB::beginTransaction();

            $voucher = VouchersLocadoras::create($dodos_request);
            $nomeArquivo = $request->nome_arquivo;

            $pathArquivoTemp = "public/temp/$nomeArquivo";
            $pathArquivoVoucher = "public/voucher/$nomeArquivo";

            if(!Storage::exists($pathArquivoVoucher)) {
                Storage::move($pathArquivoTemp, $pathArquivoVoucher);
            }

            $path = public_path("storage/$pathArquivoVoucher");
            
            VouchersAnexos::create([
                'id_voucher' => $voucher->id, 
                'path' => $path, 
                'arquivo' => $nomeArquivo
                ]);

            $detalhes_requisicao = DetalhesRequisicaoVeiculos::find($request->id_detalhes_requisicao);
            $detalhes_requisicao->id_voucher = $voucher->id;
            $detalhes_requisicao->status_veiculo = 1;
            $detalhes_requisicao->save();
            $detalhes_requisicao_status = DetalhesRequisicaoVeiculos::where('id_requisicao', $request->id_requisicao)
            ->get();

            foreach($detalhes_requisicao_status as $status_detealhe) {
                $status[] = $status_detealhe->status_veiculo;
            }

            $requisicao_veiculo = RequisicaoVeiculos::find($request->id_requisicao);
            $requisicao_veiculo->status = 9;
            // $teste = [$detalhes_requisicao_status->count(), count(array_filter($status))];
            
            
            if($detalhes_requisicao_status->count() === count(array_filter($status))) {
                $requisicao_veiculo->status = 10;
            }
            $requisicao_veiculo->save();

            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Não foi possível efetivar sua solicitação!',
                'code' => $e->getCode(),
                'status' => 'ERROR'
                ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ooops! Erro ao processar voucher',
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

        return response()->json([
            'message' => 'Voucher registrado com sucesso!',
            'status' => 'OK',
            'data' => $voucher
        ], 200);
    }


    public function showVoucherPorRequisicao($id)
    {
        try{

         $vouchers = DetalhesRequisicaoVeiculos::with('voucher')
            ->where('id_requisicao', $id)
            ->get()->toArray();
            
            $data = [];
           
            foreach($vouchers as $atributo => $dados) {
               
                if($dados['voucher']){
                    $dados['voucher']['cidade_retirada'] = $this->montarCidadeRetirada($dados['voucher']['cidade_retirada']);
                    $dados['voucher']['condutor'] = $this->montarCondutor($dados['voucher']['id_condutor']);
                }
                
                $data[$atributo] = $dados;
            }
    
            return response()->json([
                'message' => 'Listagem processada com sucesso!',
                'status' => 'OK',
                'data' => $data
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'linha' => $e->getLine(),
                // 'message' => 'Ooops! Erro ao processar voucher',
                'code' => $e->getCode(),
                // 'linha' => $e->getLine(),
                'status' => 'ERROR'
            ], 201);
        }
    }

    private function montarCidadeRetirada($cidade_retirada) {
        $cidades = Cidades::findOrFail($cidade_retirada)->toArray();
        $data = [];
        foreach($cidades as $atributo => $dados) {
            $data[$atributo] = $dados;
        }
        return $data;
    }

    private function montarCondutor($id_condutor) {
        $condutor = Condutores::with('usuario')
        ->where('id_usuario', $id_condutor)
        ->get();
        $data = [];
        foreach($condutor as $atributo => $dados) {
            if($atributo === 'usuario') {
                $data['nome'] = $dados['nome'];
                $data['cpf'] = $dados['cpf'];
                continue;
            }
            
            $data[$atributo] = $dados;
            
        }
        
        return $data;
    }


    /**
     * Retorna atributos que precisam ser persistidos na tabela de VoucherLocadora
     */
    private function retornarRelacionamentosVoucher($dadosBusca)
    {
        extract($dadosBusca);
        $model_locadora = Locadoras::where('nome_fantasia', $locadora)->get()->first();
        if(!$model_locadora) {
            return [ 
                'erro' => "Locadora $locadora não encontrada"
            ];
            
        }
        
        $model_detalhes_requisicao = DetalhesRequisicaoVeiculos::find($id_detalhes_requisicao);
        $model_cidade_retirada = Cidades::where('nome', $cidade_retirada)->first();
        $model_cidade_devolucao = Cidades::where('nome', $cidade_devolucao)->first();
        
        
        
        $retorno = [
            'id_locadora' => $model_locadora->id,
            'grupo_veiculo' => $model_detalhes_requisicao->categoria_veiculo['id'],
            'duracao' => $model_detalhes_requisicao->prazo_locacao,
            'id_condutor' => $model_detalhes_requisicao->id_condutor,
            'estado_retirada' => $model_cidade_retirada->estado,
            'estado_devolucao' => $model_cidade_devolucao->estado,
            'cidade_retirada' => $model_cidade_devolucao->id,
            'cidade_devolucao' => $model_cidade_devolucao->id,
        ];
       
        return $retorno;
           
    }

}