const optionsNotify = {
  position: "top-right",
  maxNotifications: 6,
  durations: {
    global: 3000,
    alert: 5000
  },
  labels: {
    tip: 'Dica!',
    info: 'Informação!',
    success: 'Sucesso!',
    warning: 'Atenção!',
    alert: 'Erro!',
    async: 'Processando...',
    confirm: 'Responda!',
    confirmOk: 'Enviar',
    confirmCancel: 'Voltar'
  }
};

var notify = new AWN(optionsNotify);

if (userFirstName !== undefined) {
  $("#nomeUsuario").append(userFirstName.charAt(0).toUpperCase() + (userFirstName.toLowerCase()).substr(1));
}

$("#info-user").append(`
  <b>Nome:</b> `+userName+`<br>
  <b>Função:</b> `+userFuncao+`<br>
  <b>UT:</b> `+userUt+`
`);

function infoUser() {
    $("#infoUser").modal("show")
}

var numeroUt = sessionStorage.getItem("userUt").split('-')[0].split('9.')[1];

//INICIO - BOTÃO SAIR
function sair() {
  sessionStorage.removeItem("ManservToken");
  sessionStorage.removeItem("userId");
  sessionStorage.removeItem("userFuncao");
  sessionStorage.removeItem("userUt");
  sessionStorage.removeItem("userName");

  window.location.replace("/logout");
}
//FIM - BOTÃO SAIR

//INICIO - FUNÇÃO DE TRATAMENTO DE ALERTAS
function alertas(tipo){
  if(tipo === 'Token has expired'){
    notify.alert("Token expirado! Entre novamente com seu login.");
    window.location.replace("/login");
  }else if(tipo === 'sem acesso'){
    notify.warning("Você não tem permissão pra acessar essa tela!");
    window.history.back();
  }else{
    notify.alert(tipo);
  }
}
//FIM - FUNÇÃO DE TRATAMENTO DE ALERTAS