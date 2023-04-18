<?php

namespace App\Http\Controllers\Classis\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class ConverterPDFController extends Controller
{
    private $pathPdf;

    private $pathTxt;

    private $urlPdf;

    public function converterPDF(Request $request)
    {
        try{
            if($request->hasFile('voucherPdf')) {
                
                $parse = new Parser();
         
                $numeroVoucher = $request->nrRequisicao;
                $nomeCondutor = implode('_',explode(' ', $request->nomeCondutor));
                $inputPdf = $request->file('voucherPdf')->getClientOriginalName();
                
                $nomeArquivoTemp = current(explode('.', $inputPdf));
                $arquivoPdf = "{$numeroVoucher}_{$nomeCondutor}.pdf";
                $this->pathPdf = $request->file('voucherPdf')->storeAs('public/temp', $arquivoPdf);
                $this->monatsrUrlArquivo($request);
                $pathArquivo = $this->retornaPublicPathArquivos($this->pathPdf);
                $content = $parse->parseFile($pathArquivo);
                
                $this->pathTxt = "public/temp/$nomeArquivoTemp.txt";
                Storage::put($this->pathTxt, $content->getText());
                
                $json = [];
                preg_match_all('/(UNIDAS)(\tINCLUSÃO)/', $content->getText(), $matches);
                $nome_empresa = $matches[1][0];
                preg_match_all('/(CONFIRMADA\tCENTRAL DE RESERVAS\t)([0-9]*)/', $content->getText(), $matches);
                $numero_voucher = $matches[2][0];
                preg_match_all('/(Dados do Cliente)(\t\n)([0-9]{3,3}\.[0-9]{3,3}\.[0-9]{3,3}\/[0-9]{4,4}-[0-9]{2,2})(\t)(.*)(-)/', $content->getText(), $matches);
                $nome_cliente = $matches[5][0];
                $cnpj_cliente = $matches[3][0];
                preg_match_all('/([a-zA-ZáàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]*)(\tCondutor)/', $content->getText(), $matches);
                $nome_condutor = $matches[1][0];
                
                $linhas = file($this->retornaPublicPathArquivos($this->pathTxt));
                
                foreach($linhas as $numero => $linha) {
                    if($numero === 12) {
                        extract($this->getRetiradaDevolucao($linha));
                        $endereco_devolucao = $parametro . " " . current($partes);
                        $endereco_retirada = $parametro . " " . end($partes);
                    }
                    
                    if($numero === 16) {
                        extract($this->getRetiradaDevolucao($linha));
                        $local_devolucao = $parametro . " " . current($partes);
                        $local_retirada = $parametro . " " . end($partes);
                    }
                    
                    if($numero === 17) {
                        extract($this->getRetiradaDevolucao($linha));
                        $referencia_devolucao = $parametro . " " . current($partes);
                        $referencia_retirada = $parametro . " " . end($partes);
                    }
                    
                    if($numero == 15) {
                        $partes = preg_split('/\t/',$linha);
                        array_pop($partes);
                        $email_devolucao = strtolower(current($partes));
                        $email_retirada = strtolower(end($partes));
                    }
                    
                    
                    if($numero === 14) {
                        $telefones = array_filter(preg_split('/([a-zA-Z \t]{3,})/', $linha));
                        $cidades = explode(current($telefones), $linha);
                        
                        extract($this->getRetiradaDevolucao(current($telefones)));
                        $telefone_devolucao = $parametro . " " . current($partes);
                        $telefone_retirada = $parametro . " " . end($partes);
                        
                        extract($this->getRetiradaDevolucao(current($cidades)));
                        $cidade_devolucao = $parametro . " " . current($partes);
                        $cidade_retirada = $parametro . " " . end($partes);
                    } 
                    
                    if($numero === 13) {
                        $horas = array_filter(preg_split('#([0-9]{2}/[0-9]{2}/[0-9]{4}\t[0-9]{2}/[0-9]{2}/[0-9]{4})#', $linha));
                        $datas = current(array_filter(explode(current($horas), $linha)));
                        
                        $partes = array_filter(preg_split('/\t/',current($horas)));
                        array_pop($partes);
                        
                        $hora_devolucao =  current($partes);
                        $hora_retirada = end($partes);
                        
                        $partes = preg_split("/\t/", $datas);
                        $data_devolucao =  current($partes);
                        $data_retirada =  end($partes);
                    }
                }
                $json = [
                    'nome' => $nome_empresa,
                    'numero_voucher' => $numero_voucher ,
                    'link' => $this->urlPdf,
                    'nome_arquivo' => $arquivoPdf,
                    'cliente' => [
                        'nome' => $nome_cliente,
                        'cnpj' => $cnpj_cliente,
                        'condutor' => $nome_condutor
                    ],
                    'retirada' => [
                        'local' => $local_retirada,
                        'endereco' => $endereco_retirada,
                        'cidade' => trim(str_replace('  ', ' ', $cidade_retirada)),
                        'referencia' => $referencia_retirada,
                        'telefone' => $telefone_retirada,
                        'email' => $email_retirada,
                        'data' => implode('-', array_reverse(explode('/',$data_retirada))),
                        'hora' => $hora_retirada
                    ],
                    'devolucao' => [
                        'local' => $local_devolucao,
                        'endereco' => $endereco_devolucao,
                        'cidade' => trim(str_replace('  ', ' ', $cidade_devolucao)),
                        'referencia' => $referencia_devolucao,
                        'telefone' => $telefone_devolucao,
                        'email' => $email_devolucao,
                        'data' => implode('-', array_reverse(explode('/', $data_devolucao))),
                        'hora' => $hora_devolucao
                    ]
                ];
                return response()->json([
                    'message' => 'Arquivo extraido com sucesso',
                    'data' => $json,
                    'status' => 'OK'
                ], 200);
            } else {
                return response()->json([
                    'message' => "O arquivo não foi enviado",
                    'status' => 'ERROR'
                ], 412);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'status' => 'ERROR'
            ], 404);
        }

    }

    private function monatsrUrlArquivo($request)
    {
        $host = $request->server('HTTP_HOST');
        $scheme = $request->server('REQUEST_SCHEME');
        $this->urlPdf = str_replace('public', 'storage', "{$scheme}://{$host}/{$this->pathPdf}"); 
    }

    private function getRetiradaDevolucao($linhasComposta) 
    {
        $primeiraQuebra = current(explode(' ', $linhasComposta));
        $divisaoLinha = array_filter(explode($primeiraQuebra, $linhasComposta));
        return ['parametro' => $primeiraQuebra, 'partes' => $divisaoLinha];
    }

    private function retornaPublicPathArquivos($path) 
    {
        return public_path(str_replace('storage/public', 'storage', "storage/$path"));
    }

    public function __destruct() 
    {
        Storage::delete([$this->pathTxt]);
    }
}