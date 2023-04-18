$('#spinner').hide()


//consulta api Login
document.getElementById("btnLogin").addEventListener("click", ()=>{
    let cpf01 = $("#inputCpf").val()

    cpf01 = cpf01.replace("-", "");
    cpf01 = cpf01.replace(".", "");
    cpf01 = cpf01.replace(".", "");

    let objLogin = {
        "usuario": cpf01,
        "senha": $("#inputPass").val()
    }

    $.ajax({
        type: "POST",
        url: uri + "/authenticate",
        headers: {
            "Manserv": 'Manserv',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
        data: objLogin,
        beforeSend: function (){
            $('#spinner').show();
            $('#btnLogin').hide();
        },
        success : function (response){
            if(response.status === "OK")
            {
                sessionStorage.ManservToken = response.token;
                sessionStorage.userId = response.usuario.id;
                sessionStorage.userName = response.usuario.nome;

                sessionStorage.userFuncao = response.funcao;
                sessionStorage.userUt = response.ut;
                window.location.replace("/");
            }
        },
        error: function(xhr, error){
            let message = xhr.responseJSON.message;
            notify.alert(message);
        },
        complete: function (response) {
            $('#btnLogin').show();
            $('#spinner').hide();
        }
    })
});

//Máscara de CPF
$('.cpf').mask('000.000.000-00');