var token = sessionStorage.getItem("ManservToken");
var userId = sessionStorage.getItem("userId");
var userFuncao = sessionStorage.getItem("userFuncao");
var userUt = sessionStorage.getItem("userUt");
var userName = sessionStorage.getItem("userName");
var userFirstName = userName.split(" ")[0];

var temPermissao = 0;
var paginaAtual = uri;

$.ajax({
    type: "GET",
    url: uri + "/api/usuarios/me/"+ userId,
    headers: {
        "Authorization": "bearer " + token, 
        "Accept": "application/json"
    }, 
    success: function (response) {
        if (response.status = "OK") {
            permissaoAcesso = response.data.permissoes.data
            $('.geral').removeClass("hide");
        }
    },
    error: function (xhr, error) {
        if (xhr.status !== 200) {
            notify.alert(xhr.statusText);
            setTimeout(function() {
                window.location.replace('/login');
            }, 4000)
            window.location.replace('/login');
        }
        alertas(xhr.responseJSON.message);
    },
    complete: function () {

    }

})