<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use App\Models\Condutores;
use App\Models\Checklists;
use App\Models\ChecklistsImagens;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function index()
    {       
        try {
            $tempCheckList = Checklists::with('veiculo')->with('condutor')->with('imagens')->get();
            $Checklist = null;
            foreach ($tempCheckList as $chk) {
                if($chk->veiculo == null){
                    $placa = "Não deu entrada do Veiculo";
                }else{
                    $placa = $chk->veiculo->placa;
                }
                $Checklist[] = [
                    'placa' => $placa,
                    'data_chklist' => $chk->data_chklist,
                    'tipo_chklist' => ["numero"=>$chk->tipo_chklist,"texto"=>pendenciaTipo($chk->tipo_chklist)],
                    "condutor" => $chk->condutor,
                    "veiculo" => $chk->veiculo,
                    "observacao" => $chk->observacao,
                    "images"=>$chk->imagens
                ];                
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar CheckLists', 
                'code' => $e->getCode(). $e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Checklists!',
            'status' => 'OK',
            'data' => $Checklist
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @ param int $id
     */
    public function exibir($placa, $id_condutor)
    {
        try {
            //$checklist =  Checklists::with('veiculo')->with('condutor')->with('imagens')->where('placa',$placa)->where('condutor_id',$idCondutor)->get(); 
            $checklist =  Checklists::with('veiculo')->with('condutor')->where('placa',$placa)->where('id_condutor',$id_condutor)->get();           
            if($checklist->veiculo == null){
                    $placa = "Não deu entrada do Veiculo";
            }else{
                    $placa = $checklist->placa;
            }
            $Checklists = [
                    'placa' => $placa,
                    'data_chklist' => $checklist->data_chklist,
                    'tipo_chklist' => ["numero"=>$checklist->tipo_chklist,"texto"=>pendenciaTipo($checklist->tipo_chklist)],
                    "condutor" => $checklist->condutor,
                    "veiculo" => $checklist->veiculo,
                    "observacao" => $checklist->observacao,
                    "checklists" => json_decode($checklist->checklist),
                    "images"=>$checklist->imagens
                ];                
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Não foi possivel mostrar Checklists', 
                'code' => $e->getCode().$e->getMessage(),
                'status' => 'ERROR',
            ], 400);
        }

        return response()->json([
            'message' => 'Listagem de Checklists por Veiculo e Condutor!',
            'status' => 'OK',
            'data' =>  $checklists
        ], 200);
    }

   
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       try
       {   
        DB::beginTransaction();       
        $tempChk = $request->all();
        $checklistExist = CheckLists::where('placa',$request->placaVeiculo)->where('id_condutor',$request->id_condutor)->where('tipo_chklist',$request->tipoCheck)->where('id_ut_cc',$request->id_ut_cc)->first();
        $checklist = [
                        "id_condutor"=>$tempChk['id_condutor'],
                        "placa"=>$tempChk['placaVeiculo'],
                        "kilometragem"=>$tempChk['kilometragem'],
                        "data_chklist"=>$tempChk['dataCadastro'],
                        "tipo_chklist"=>$tempChk['tipoCheck'],
                        "id_ut_cc"=>$tempChk['id_ut_cc'],
                        "checklist"=>"",
                        "observacao"=>$tempChk['observacao']
                    ];
                    
        unset($tempChk['id_condutor']);
        unset($tempChk['observacao']);
        unset($tempChk['nomeCondutor']);
        unset($tempChk['placaVeiculo']);
        unset($tempChk['dataCadastro']);
        unset($tempChk['tipoCheck']);
        unset($tempChk['id_ut_cc']);
        unset($tempChk['kilometragem']);

        $checklist['checklist'] = json_encode($tempChk);
       
        if($checklistExist==null){
            Checklists::create($checklist);
        }
        else
        {
            Checklists::where('id',$checklistExist->id)->update($checklist);
        }
       
        
        DB::commit(); 
     } catch (QueryException $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Ocorreu um erro ao Cadastrar Checklist!',
            'code' => $e->getCode().$e->getMessage(),
            'status' => 'ERROR'
        ], 406);
    }  catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'mensagem' => 'Não foi possivel cadastrar Checklist', 
            'code' => $e->getCode().$e->getMessage(),
            'status' => 'ERROR',
        ], 400);
    }

    return response()->json([
        'message' => 'Checklist gravada com sucesso!',
        'status' => 'OK',      
    ], 200);
    }


 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function files(Request $request)
    {
       try
       {
        DB::beginTransaction();  
        $foto_link = null;        
        $foto = $request->file('foto');
        $dados = $request->all();
       
        if($foto!=null){
            $destino = 'upload';
            $fotoName = $foto->getClientOriginalName();
            $foto_link = url('storage/upload/'.$request->placaVeiculo.'/'.$fotoName);
            $foto->move($destino,$fotoName);
        }

        $checklist = CheckLists::where('placa',$request->placaVeiculo)->where('id_condutor',$request->id_condutor)->where('tipo_chklist',$request->tipoCheck)->where('id_ut_cc',$request->id_ut_cc)->first();
        
        if($checklist==null){           
            $checlistNew = new CheckLists();
            $checlistNew->placa = $request->placaVeiculo;
            $checlistNew->id_condutor = $request->id_condutor;
            $checlistNew->id_ut_cc = $request->id_ut_cc;
            $checlistNew->checklist = '{}';
            $checlistNew->tipo_chklist = $request->tipoCheck;
            $checlistNew->data_chklist = $request->data;
            $checlistNew->save();
          
            $checklistImages = [
                'id_checklist'=>$checlistNew->id,
                //'id_chklst_item'=>$request->id_chklst_item,
                'id_chklst_item'=>null,
                'item'=> 1,
                'nome'=>$fotoName,
                'path'=>$foto_link
            ];
        }else{
            $count = ChecklistsImagens::where('id_checklist',$checklist['id'])->get()->count();
            $checklistImages = [
                'id_checklist'=>$checklist['id'],
               // 'id_chklst_item'=>$request->id_chklst_item,
                'id_chklst_item'=>null,
                'item'=>$count,
                'nome'=>$fotoName,
                'path'=>$foto_link
            ];
        }
        
       

        $checklistImages = ChecklistsImagens::create($checklistImages);

        DB::commit(); 
     } catch (QueryException $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Não foi possivel cadastrar Foto do Checklist!',
            'code' => $e->getCode().$e->getMessage(),
            'status' => 'ERROR'
        ], 406);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'mensagem' => 'Não foi possivel cadastrar Foto do Checklist', 
            'code' => $e->getCode().$e->getMessage(),
            'status' => 'ERROR',
        ], 400);
    }

    return response()->json([
        'message' => 'Foto do Checklist gravada com sucesso!',
        'status' => 'OK',
        'data' => $checklist     
    ], 200);
}

public function destroy($id_checklist)
{   
    try
    {     
     CheckLists::where('id',$id_checklist)->update('status','I');   
    }
    catch (\Exception $e) {
     return response()->json([
         'mensagem' => 'Não foi possivel inativar o Checklist', 
         'code' => $e->getCode(),
         'status' => 'ERROR',
     ], 400);
 }

 return response()->json([
     'message' => 'Checklist inativado com Sucesso!',
     'status' => 'OK',
     'data' => $checklist     
 ], 200);
}
}