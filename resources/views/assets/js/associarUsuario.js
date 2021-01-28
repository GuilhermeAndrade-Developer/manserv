
// Popula Lista do crud
apiGET('api/usuarios-representantes',null,listaAssoc);
function listaAssoc (retorno) { 
    $("#listaUts").empty();
    if(retorno.data.length > 0){        
        retorno.data.map((item) => {
                        $("#listaUts").append(`
                            <tr>
                                <td>${item.usuario.nome}</td>
                                <td>${item.ut.descricao}</td>
                                <td><a href='' onclick='DesassociarUsuarioUt(${item.id})'> <img src="/assets/icons/ic-excluir.svg" title="Excluir" Alt="Excluir" class="excluir"></a></td>
                            </tr>
                        `)
                    });
    }else{
        $("#listaUts").append(`
                            <tr>
                                <td colspan='2' align='center'>Não há Associações Feitas</td>
                            </tr>
        `)
    }
}
userId = sessionStorage.getItem('userId');
apiGET('api/usuarios/filtrar-uts-usuarios/'+userId, null, carregarDatalist);

function associarUsuario() { 
    
    $("#assocUsuarioUtModal").modal("show");    
}


function carregarDatalist(retorno){ 
    $("#us_input").val('');
    $("#usuario").empty();
    if(retorno.status=='OK'){
        retorno.data.usuarios.map((item) => {           
            $("#usuario").append(`
                <option value="${item.id}">${item.nome}</option>
            `)
        });
        retorno.data.uts.map((item) => {           
            $("#ut").append(`
                <option value="${item.id}">${item.numero_ut} - ${item.descricao}</option>
            `)
        });
    }else{
        notify.alert(retorno);
    } 
   
}

function associarUsuarioUt(){
    var usuario = $('[name="usuario"]').val();
    var ut = $('[name="ut"]').val(); 
    var objAssoc = {
        "id_usuario": usuario,
        "id_ut_permitida": ut,
        "status": true
    }
    apiPOST('api/usuarios-representantes/associar',JSON.stringify(objAssoc),message);    
}

function DesassociarUsuarioUt(id){         
    apiDELETE('api/usuarios-representantes/'+id,null,message);    
}

function message(message){
    if(message.status==='OK'){
        notify.success(message);
        apiGET('api/usuarios-representantes',null,listaAssoc);
    }else{
        notify.alert(message);
    }
}