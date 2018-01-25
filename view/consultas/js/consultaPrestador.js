function ListaPrestador(){
    
    var idPrestador;
    var nomePrestador;
    var endPrestador;
    var telPrestador;
    var emailPrestador;
    var isCheck;
    var idCheck;
    
    this.enviarPostPrestador = function(opcao){        
        
        if(idPrestador == 0 && opcao >= 2) opcao = 0;
        
        $.post("index.php?module=consultas&tmp=1", {
                id: idPrestador, 
                endereco: endPrestador,
                nome: nomePrestador,
                telefone: telPrestador,
                email: emailPrestador,
                opcao: opcao,                                                
                flag: 'atualizaPrestador'
        }, function(data) {
                if(data){                    
                    data = data.replace(/\\/g, '');
                    //console.log(data);
                    $("tbody#tabela_Resultados_Prestador").html(data);                    
                    atualPrest.resetFunctions();                      
                } else {
                    console.log("Erro");
                    $("tbody#tabela_Resultados_Prestador").html('');
                }
        }); 
                
    }
    
    this.novoPrestador = function(){        
        $("#nome-modal-editarprestador").val('');           
        $("#end-modal-editarprestador").val('');           
        $("#tel-modal-editarprestador").val('');           
        $("#email-modal-editarprestador").val('');           
        
        $("#fechar-modal-inserirhipoteseprestador").unbind().click(function(){
            $("#nome-modal-editarprestador").val('');           
            $("#end-modal-editarprestador").val('');           
            $("#tel-modal-editarprestador").val('');           
            $("#email-modal-editarprestador").val('');           
            fechar_modal("modal-editarprestador");
        });
        
        $("#salvar-modal-editarprestador").unbind().click(function(){
            if($("#nome-modal-editarprestador").val() != ''){
                nomePrestador = $("#nome-modal-editarprestador").val();           
                endPrestador = $("#end-modal-editarprestador").val();           
                telPrestador = $("#tel-modal-editarprestador").val();           
                emailPrestador = $("#email-modal-editarprestador").val();
                atualPrest.enviarPostPrestador(1);
                fechar_modal("modal-editarprestador");                
            } else {
                alert("Preencha todos os campos");
            }
        });                        
        abrir_modal("modal-editarprestador");  
    }
    
    this.editarPrestador = function(){
        $("#nome-modal-editarprestador").val('');           
        $("#end-modal-editarprestador").val('');           
        $("#tel-modal-editarprestador").val('');           
        $("#email-modal-editarprestador").val('');           
        
        $("#fechar-modal-inserirprestador").unbind().click(function(){
            $("#nome-modal-editarprestador").val('');           
            $("#end-modal-editarprestador").val('');           
            $("#tel-modal-editarprestador").val('');           
            $("#email-modal-editarprestador").val('');           
            fechar_modal("modal-editarprestador");
        });
        
        $("#salvar-modal-editarprestador").unbind().click(function(){
            if($("#nome-modal-editarprestador").val() != ''){
                nomePrestador = $("#nome-modal-editarprestador").val();           
                endPrestador = $("#end-modal-editarprestador").val();           
                telPrestador = $("#tel-modal-editarprestador").val();           
                emailPrestador = $("#email-modal-editarprestador").val();                                
                atualPrest.enviarPostPrestador(2);
//                var html = "Prestador:" + nomePrestador +
//                        "\nTelefone:" + telPrestador +
//                        "\nEmail:" + emailPrestador +
//                        "\nEndereço:" + endPrestador +
//                        "\nServico:" + "null" +
//                        "\nSubtestes:" + "null";                                
//                $("#textarea-requisicao").val(html);
                fechar_modal("modal-editarprestador");                
            } else {
                alert("Preencha todos os campos");
            }
        });                    
        if(idPrestador != 0){
            
            $.post("index.php?module=consultas&tmp=1", {
                idPrestador: idPrestador,
                idServico: 0,
                flag: 'textoReqServico'
            }, function(result) {                
                $("#nome-modal-editarprestador").val(result['prestador']);           
                $("#end-modal-editarprestador").val(result['endereco']);           
                $("#tel-modal-editarprestador").val(result['telefone']);           
                $("#email-modal-editarprestador").val(result['email']);                
            }, "json");
            
            abrir_modal("modal-editarprestador");
        }  
    }
    
    this.removerPrestador = function(){
        if(idPrestador != 0){
            con = confirm("Deseja remover os dados?");
            if(con)
                atualPrest.enviarPostPrestador(3);
        }
    }
    
    this.resetFunctions = function(){
        
        $("input#prestador[value='"+idCheck+"']").prop("checked", true); 
        
        $(".prestadorRow").unbind();
     
        $(".prestadorRow").contextMenu({
            menu: 'list-menu-context-prestador'            
        });
        
        $("tr.prestadorRow").mousedown(function(e){                        
            idPrestador = parseInt($(this).attr("id"));                        
            isCheck = $("input#prestador[value='"+idPrestador+"']").is(":checked");                        
            //console.log(isCheck);
            if(e.which == 1){                                
                if(!isCheck)$("input#prestador[value='"+idPrestador+"']").prop("checked", !isCheck);                
                if(!isCheck)
                    idCheck = idPrestador;             
            }            
        });     
        $(".prestadorRow").hover(
                function() {
                    $(this).css("background", "#C0D1E7");
                },
                function() {
                    $(this).css("background", "");
                }
        );
    }
    
    this.marcarFavorito = function(){        
        atualPrest.enviarPostPrestador(4);
    }
    
    this.desmarcarFavorito = function(){        
        atualPrest.enviarPostPrestador(5);
    }
}