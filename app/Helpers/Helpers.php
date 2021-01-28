<?php
if (!function_exists('numeroRequisicao')) {
    function numeroRequisicao(string $ultima = null): string
    {
       if($ultima==null)
        {
            $ano = \Carbon\Carbon::now('Y')->year;
            return 'RV'.$ano.'-0001';
        }
        $prefix = substr($ultima,0,2);
        $ano = substr($ultima,2,4);
        $sep = "-";   
        $seq = (int) substr($ultima,7,9); 

        $anoAtual = \Carbon\Carbon::now('Y');
        $numero = null;
        if($ano != $anoAtual->year){
            $seq = "-0001";
            $ano = $anoAtual->year;      
        }
        else 
        {
            $seq = $seq+1;
            if($seq < 10){
                $seq = "-000".$seq;
            }elseif($seq < 100){
                $seq = "-00".$seq;
            }elseif($seq < 1000){
                $seq = "-0".$seq;
            }else{
                $seq;
            }
                
        }
        $numero = $prefix.$ano.$seq;
        return $numero;
    }
}

if (!function_exists('tipoGestor')) {
    function tipoGestor(string $tipo)
    {
        switch($tipo){
            case 'R':
                return 'Coordenador';
            break;
            case 'P':
                return 'Presidente';
            break; 
            case 'V':
                return 'Vice Presidente';
            break; 
            case 'D':
                return 'Diretor';
            break;
            case 'G':
                return 'Gerente';
            break;
            case 'A':
                return 'Administrador';
            break;
            case 'F':
                return 'Frota';
            break;      
        }
    }
}

if (!function_exists('tipoGestorStatus')) {
    function tipoGestorStatus(string $tipo)
    {
        switch($tipo){
            case 'R':
                return 1;
            break;
            case 'P':
                return 5;
            break; 
            case 'V':
                return 4;
            break; 
            case 'D':
                return 3;
            break;
            case 'G':
                return 2;
            break;
            case 'A':
                return 0;
            break;
            case 'F':
                return 6;
            break;      
        }
    }
}

if (!function_exists('gestorStatus')) {
    function gestorStatus(string $tipo)
    {
        switch($tipo){
            case 'C':
                return 2;
            break;
            case 'G':
                return 3;
            break; 
            case 'D':
                return 4;
            break; 
            case 'V':
                return 5;
            break;
            case 'P':
                return 6;
            break;
            case 'F':
                return 8;
            break;
        }
    }
}
if (!function_exists('pendenciaTipo')) {
   function pendenciaTipo(int $tipo)
    {  
       switch($tipo)
        {
            case 1:
                return 'Checklist de Retirada';
            break;
            case 2:
                return 'Checklist de Utilização';
            break;
            case 3:
                return 'Checklist de Retorno';
            break;
            case 4:
                return 'Checklist de Devolução';
            break;
            case 5:
                return 'Checklist de Auditoria';
            break;
            case 6:
                return 'Troca de Óleo';
            break;
            case 7:
                return 'Informar Km Atual';
            break;
            case 8:
                return 'Manutenção Preventiva';
            break;
        }

    }
}