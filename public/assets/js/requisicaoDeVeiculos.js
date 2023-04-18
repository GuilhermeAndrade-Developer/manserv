/**************************************************/
/* FUNÇÕES RELACIONADAS A REQUISIÇÃO DE VEÍCULOS */
/**************************************************/

//INICIO - ADICIONAR CONDUTOR
$(document).on('click', '#addCondutor', function(e) {
    $('.listaCondutores').append(`
        <tr>
            <input type="hidden" class="altReCondutor" value="` + $("#reCondutor").val() + `">
            <input type="hidden" class="altCnhCondutor" value="` + $("#cnhCondutor").val() + `">
            <input type="hidden" class="altPrazoLocacao" value="` + $("#prazoLocacao").val() + `">
            <input type="hidden" class="altDataDevolucao" value="` + $("#dataDevolucao").val() + `">
            <input type="hidden" class="altLocalRodagem" value="` + $("#localRodagem").val() + `">
            <input type="hidden" class="altKmMensal" value="` + $("#kmMensal").val() + `">
            <input type="hidden" class="altLimiteCartaoCombustivel" value="` + $("#limiteCartaoCombustivel").val() + `">

            <td class="condutor">` + $("#nomeCondutor").val() + `</td>
            <td class="cidade">` + $("#cidadeRetirada").val() + `</td>
            <td class="categoria">` + $("#categoriaVeiculo").val() + `</td>
            <td class="retirada">` + ($("#dataRetirada").val()).replace(/(\d*)-(\d*)-(\d*).*/, '$3/$2/$1')  + `</td>
            <td>
                <img src="/assets/icons/ic-editar.svg" title="Editar" Alt="Editar" class="editar">
                <img src="/assets/icons/ic-excluir.svg" title="Excluir" Alt="Excluir" class="excluir">
            </td>
        </tr>
    `);

    $(".add-condutor").val(''); 
    notify.info('ADICIONADO SUCESSO');
});
//FIM - ADICIONAR CONDUTOR

//INICIO - EDITAR CONDUTOR CADASTRADO
$(document).on("click", ".editar", function(e){
    data = $(this).parent().parent().find(".retirada").text(); 

    $("#categoriaVeiculo").val($(this).parent().parent().find(".categoria").text());
    $("#cidadeRetirada").val($(this).parent().parent().find(".cidade").text());
    $("#nomeCondutor").val($(this).parent().parent().find(".condutor").text());
    $("#dataRetirada").val(dataParaBanco(data));

    $("#reCondutor").val($(this).parent().parent().find(".altReCondutor").val());
    $("#cnhCondutor").val($(this).parent().parent().find(".altCnhCondutor").val());
    $("#prazoLocacao").val($(this).parent().parent().find(".altPrazoLocacao").val());
    $("#dataDevolucao").val($(this).parent().parent().find(".altDataDevolucao").val());
    $("#localRodagem").val($(this).parent().parent().find(".altLocalRodagem").val());
    $("#kmMensal").val($(this).parent().parent().find(".altKmMensal").val());
    $("#limiteCartaoCombustivel").val($(this).parent().parent().find(".altLimiteCartaoCombustivel").val());

    $(this).closest('tr').remove();
});
//FIM - EDITAR CONDUTOR CADASTRADO

//INICIO - EXCLUIR CONDUTOR CADASTRADO
$(document).on("click", ".excluir", function(e){
    $(this).closest('tr').remove();
    notify.info('EXCLUIDO SUCESSO');
});
//FIM - EXCLUIR CONDUTOR CADASTRADO


/**************************************************/
/* FUNÇÕES RELACIONADAS AO RELÁTORIO DE APROVAÇÃO */
/**************************************************/

//INICIO - ABRE MODAL DE APROVAÇÃO
$(document).on("click", ".aprovar", function(e){
    $("#aprovarModal").modal("show")
});
//FIM - ABRE MODAL DE APROVAÇÃO

//INICIO - ABRE MODAL DE MAIS INFORMAÇÕES DO VEÍCULO
d = new Date();
$('#dataAprovacao').val(d.getFullYear() + "-" + (d.getMonth()+1) + "-" + ((d.getDate()[1]?d.getDate():"0"+ d.getDate())) );
//FIM - ABRE MODAL DE MAIS INFORMAÇÕES DO VEÍCULO

//INICIO - APROVAR OU REPROVAR REQUISIÇÃO
function aprovacao(status){
    tooltip = '';

    if(status == 'reprovado'){
        tooltip = 'data-toggle="tooltip" data-placement="top" title="' + $("#justificaReprovacao").val() + '"';
    }

    $('.listaAprovacoes').append(`
        <tr>
            <td>0000-000.000</td>
            <td>` + $("#nomeRequisitante").val() + `</td>
            <td>Gerente</td>
            <td><b  class="` + status + `" ` + tooltip + `>` + status[0].toUpperCase() + status.substr(1) + `<b class="reprovado"></td>
            <td>` + ($("#dataAprovacao").val()).replace(/(\d*)-(\d*)-(\d*).*/, '$3/$2/$1')  + `</td>
        </tr>
    `);

}

$(document).on('click', '#aprovar', function(e) {
    aprovacao('aprovado');
    notify.info('APROVADO SUCESSO');
});

$(document).on('click', '#reprovar', function(e) {
    aprovacao('reprovado');
    notify.info('REPROVADO SUCESSO');
});

$(document).on('click', '#limpar', function(e) {
    $('#justificaReprovacao').val('');
    $('#justificaReprovacao').trigger('focus');
});
//FIM - ADICIONAR CONDUTOR

$('#cnhCondutor').on('blur', function() {
    cnh = $(this).val();

    if (!valida_cnh(cnh) && cnh !== "") {
        alertas("CNH Inválida!");
        $(this).focus();
    }
});

$('#reCondutor').on('blur', function() {
    cpf = $(this).val();

    if (!valida_cpf(cpf) && cpf !== "") {
        alertas('CPF Inválido!');
        $(this).focus();
    }
});

$('#reCondutor').on('input', function() {
    let MIN_LENGTH = 3;
    let cpf = $(this).val();

    if (cpf.length >= MIN_LENGTH) {
        $.ajax({
            type: 'GET',
            url: uri + '/api/requisicao-condutor?cpf=' + cpf,
            headers: {
                'Accept': 'application/json',
                'Authorization': 'bearer ' + token
            },
        })
            .done(function(response) {
                $('#reCondutor').autocomplete({
                    source: response.condutores,
                })
            });
    }
});

$('#prazoLocacao').on('change', function() {
    let dataRetirada = $("#dataRetirada").val();
    let prazoLocacao = $(this).val();

    dataRetirada = Date.parse(dataRetirada);
});

$('.cpf').mask('000.000.000-00');

$('.cnh').mask('00000000000');
