apiGET('api/condutor/listacondutor',null,listaCondutor);
var condutores ='';
function listaCondutor(response){
    $("#listaCondutores").
    if (response.status = "OK") {
        condutores = response.data;
        condutores.map((item) => {
            $("#listaCondutores").append(`
            <tr>
            <td>${item.nome}</td>
            <td class="cnh">${item.cnh}</td>
            <td>${item.categoria_cnh}</td>
            <td>${item.data_vencimento_cnh}</td>
            <td>
           
            
        </td>
            <td>
                <i class="material-icons" data-toggle="tooltip" onclick="editaCondutor(${item.id_usuario})" title="Edit"> <img src="/assets/icons/ic-editar.svg" title="Editar" Alt="Editar" class="editar"></i>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="material-icons" data-toggle="tooltip" onclick="removerCondutor(${item.id})" title="Remover"><img src="/assets/icons/ic-excluir.svg" title="Excluir" Alt="Excluir" class="excluir"></i>
            </td>
        </tr>
            `);
        });
    }
}

function editaCondutor(id_usr) {    
    const condutor = condutores.find((element) => { return element.id_usuario == id_usr });
    //   alert(JSON.stringify(condutor))

    $("#nomeCondutor").val(condutor.nome);
    $("#cnhCondutor").val(condutor.cnh);
    $("#categoriaCnhCondutor").val(condutor.categoria_cnh);
    $("#vencimentoCnhCondutor").val(dataConvert(condutor.data_vencimento_cnh));
    $("#rgCondutor").val(condutor.rg);
    $("#idCondutor").val(condutor.id_usuario);
    $("#statusCondutor").val(condutor.status == "A" || condutor.status == "a" ? "Ativo" : "Inativo");

    $("#cnhCondutor").attr("readonly", "true");
    $("#categoriaCnhCondutor").attr("readonly", "true");
    $("#vencimentoCnhCondutor").attr("readonly", "true");
    $("#statusCondutor").attr("readonly", "true");
    $("#rgCondutor").attr("readonly", "true");

    $("#criarCondutor").hide();
    $("#editarCondutor").show();

    $("#cpfCondutorfull").hide();

    $("#modalCondutor").modal("show");
}

function adicionarCondutor() {

    $("#nomeCondutor").val("");
    $("#cnhCondutor").val("");
    $("#categoriaCnhCondutor").val("");
    $("#vencimentoCnhCondutor").val("");
    $("#statusCnhCondutor").val("");
    $("#rgCondutor").val("");
    $("#idCondutor").val("");
    $("#statusCondutor").val("");

    $("#cnhCondutor").removeAttr("readonly");
    $("#categoriaCnhCondutor").removeAttr("readonly");
    $("#vencimentoCnhCondutor").removeAttr("readonly");
    $("#statusCnhCondutor").removeAttr("readonly");
    $("#statusCondutor").removeAttr("readonly");
    $("#rgCondutor").removeAttr("readonly");

    $("#criarCondutor").show();
    $("#editarCondutor").hide();

    $("#cpfCondutorfull").show();

    $("#modalCondutor").modal("show");
}

function cadastrarCondutor() {

    var condutor = {
        "cnh": $("#cnhCondutor").val(),
        "data_vencimento_cnh": dataConvert($("#vencimentoCnhCondutor").val(),false),
        "categoria_cnh": $("#categoriaCnhCondutor").val(),
        "rg": $("#rgCondutor").val(),
        "id_usuario": $("#idCondutor").val(),
        "status": $("#statusCondutor").val()
    }
    apiPOST('api/condutor/registrarcondutor',JSON.stringify(condutor),message);
}

function removerCondutor(id) {

    apiDELETE('api/condutor/removercondutor/'+id,null,message);
    
}

$("#cpfCondutor").on("change", ()=>{
    let cpf = $("#cpfCondutor").val();
    cpf = cpf.replace("-", "").replace(".", "").replace(".", "");
    if(!valida_cpf(cpf)){
        alertas("CPF Inválido!");
        return;
    }    
 
    apiGET('api/usuarios/consulta-cpf/'+cpf,null,carrgarDados);
});

function carrgarDados(retorno){
    if (retorno.status = "OK") {
           $("#nomeCondutor").val(retorno.data.nome);
           $("#idCondutor").val(retorno.data.id);
    }
}



$('.cpf').mask('000.000.000-00');

function message(message){
    if(message.status==='OK'){
        notify.success('Solicitação Realizada Com Sucesso');
        apiGET('api/condutor/listacondutor',null,listaCondutor);
    }else{
        alertas('Não foi possível realizar a Solicitação')
    }
}


function dataConvert(data, br=true){
    var dataIn = new Date(data);
    var dia = dataIn.getDate().toString().padStart(2, '0');
    var mes = (dataIn.getMonth()+1).toString().padStart(2, '0');
    var ano = dataIn.getFullYear();
    if(br){
        return dia+'/'+mes+'/'+ano;
    }else{
        return ano+'-'+mes+'-'+dia;
    }
}