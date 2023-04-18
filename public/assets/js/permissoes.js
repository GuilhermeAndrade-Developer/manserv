var token = sessionStorage.getItem("ManservToken")

var condutores

$.ajax({
    type: "GET",
    url: uri + "/api/condutor/listacondutor",
    headers: {
        "Authorization": "bearer " + token,
        "Accept": "application/json"
    },
    beforeSend: function () {

    },
    success: function (response) {
        if (response.status = "OK") {
            condutores = response.data
            condutores.map((item) => {
                $("#listaCondutores").append(`
                <tr>
                    <td>${item.nome}</td>
                    <td class="cnh">${item.cnh}</td>
                    <td>${item.categoria_cnh}</td>
                    <td>${item.data_vencimento_cnh}</td>
                    <td>
                        <i class="material-icons" data-toggle="tooltip" onclick="editaCondutor(${item.id_usuario})" title="Edit">&#xe56b;</i>
                    </td>
                </tr>
                `)
            })
        }
    },
    error: function (xhr, error) {
        alertas(xhr.responseJSON.message);
    },
    complete: function () {

    }

})

function editarPermissoes() {
    $("#editModal").modal("show")
}

function editaCondutor(id_usr) {
    const condutor = condutores.find((element) => { return element.id_usuario == id_usr });

    $("#nomeCondutor").val(condutor.nome)
    $("#cnhCondutor").val(condutor.cnh)
    $("#categoriaCnhCondutor").val(condutor.categoria_cnh)
    $("#vencimentoCnhCondutor").val(condutor.data_vencimento_cnh)
    $("#rgCondutor").val(condutor.rg)
    $("#idCondutor").val(condutor.id_usuario)
    $("#statusCondutor").val(condutor.status == "A" || condutor.status == "a" ? "Ativo" : "Inativo")

    $("#cnhCondutor").attr("readonly", "true")
    $("#categoriaCnhCondutor").attr("readonly", "true")
    $("#vencimentoCnhCondutor").attr("readonly", "true")
    $("#statusCondutor").attr("readonly", "true")
    $("#rgCondutor").attr("readonly", "true")

    $("#criarCondutor").hide()
    $("#editarCondutor").show()

    $("#cpfCondutorfull").hide()

    $("#editModal").modal("show")
}



function cadastrarCondutor() {

    var condutor = {
        "cnh": $("#cnhCondutor").val(),
        "data_vencimento_cnh": $("#vencimentoCnhCondutor").val(),
        "categoria_cnh": $("#categoriaCnhCondutor").val(),
        "rg": $("#rgCondutor").val(),
        "id_usuario": $("#idCondutor").val(),
        "status": $("#statusCondutor").val()
    }

    if ($("#nomeCondutor").val() != "") {
        $.ajax({
            type: "POST",
            url: uri + "/api/condutor/registrarcondutor",
            headers: {
                "Authorization": "bearer " + token,
                "Accept": "application/json",
                "Content-Type":"application/json"

            },
            data: JSON.stringify(condutor),
            success: function (response) {
                if (response.status = "OK") {
                    alert(response.message)
                }
            },
            error: function (xhr, error) {
                alertas(xhr.responseJSON.message);
            },

        })

    }
    else {
        alert("Preencha um cpf válido.")
    }
   
     
}

$("#cpfCondutor").on("change", ()=>{
    let cpf01 = $("#cpfCondutor").val()

    cpf01 = cpf01.replace("-", "");
    cpf01 = cpf01.replace(".", "");
    cpf01 = cpf01.replace(".", "");

    var cpf = {
        "cpf": cpf01
    }

    $.ajax({
            type: "POST",
            url: uri + "/api/usuarios/consulta-cpf",
            headers: {
                "Authorization": "bearer " + token,
                "Accept": "application/json",
                "Content-Type":"application/json"

            },
            data: JSON.stringify(cpf),
            success: function (response) {
                if (response.status = "OK") {
                    $("#nomeCondutor").val(response.data.nome)
                    $("#idCondutor").val(response.data.id)
                }
            },
            error: function (xhr, error) {
                alertas(xhr.responseJSON.message);
            },

        })
})


$('.cpf').mask('000.000.000-00');