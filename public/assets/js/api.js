const ManservToken = sessionStorage.getItem("ManservToken");
const headers =(ManservToken=null)=> {

    if(ManservToken==null){
       return {
                'Manserv':'Manserv',
                'Accept':'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        } 
    }else{
        return {
            'Authorization':'bearer '+ManservToken,
            'Manserv':'Manserv',
            'Accept':'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    } 
    }
    
};

const apiGET = (url_us,param=null,retorno)=>{
    var url = uri+'/'+url_us;
    $.ajax({
        url: url,
        headers:headers(ManservToken),
        dataType: "json",
  	    type: 'GET',
  	    data: param,
        async: true,
        success:(response)=>{
              retorno(response);
        },
        error: function(xhr, error){
          
            let message = xhr.responseJSON.message;
            retorno(message);
        },
      });
}

const apiPOST = (url_us,param=null,retorno)=>{
    var url = uri+'/'+url_us;
    $.ajax({
        url: url,
        headers:headers(ManservToken),
        dataType: "json",
        type: 'POST',
        data: param,
        async: true,
        success:(response)=>{
            retorno(response);
        },
        error: function(xhr, error){
           
            let message = xhr.responseJSON.message;
            retorno(message);
        },  
    });
}

const apiPUT = (url_us,param=null,retorno)=>{
    var url = uri+'/'+url_us;
    $.ajax({
        url: url,
        headers:headers(ManservToken),
        dataType: "json",
        type: 'PUT',
        data: param,
        async: true,
        success:(response)=>{
            retorno(response);
        },
        error: function(xhr, error){
         
            let message = xhr.responseJSON.message;
            retorno(message);
        },  
    });
}

const apiDELETE = (url_us,param=null,retorno)=>{
    var url = uri+'/'+url_us;
    $.ajax({
        url: url,
        headers:headers(ManservToken),
        dataType: "json",
        type: 'DELETE',
        data: param,
        async: true,
        success:(response)=>{
            retorno(response);
        },
        error: function(xhr, error){
            let message = xhr.responseJSON.message;
            retorno(message);
        },  
    });
}