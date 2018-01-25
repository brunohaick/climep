function ListaRequisicaoServico(){
    
    var idRequisicao;
    var nomeRequisicao;
    var subtesteRequisicao;   
    var isCheck;
    var idCheck;    
    
    this.enviarPostRequisicao = function(opcao){        
        
        if(idRequisicao == 0 && opcao >= 2) opcao = 0;
        
        $.post("index.php?module=consultas&tmp=1", {
                idr: idRequisicao,                 
                idp: 1,
                nome: nomeRequisicao,
                subt: subtesteRequisicao,                
                opcao: opcao,                                                
                flag: 'atualizaRequisicao'
        }, function(data) {
                if(data){                    
                    data = data.replace(/\\/g, '');
                    //console.log(data);
                    $("tbody#tabela_Resultados_Requisicao").html(data);                    
                    atualReqs.resetFunctions();                      
                } else {
                    console.log("Erro");
                    $("tbody#tabela_Resultados_Requisicao").html('');
                }
        }); 
                
    }
    
    this.novaRequisicao = function(){             
        $("#nome-modal-editarrequisicao").val('');           
        $("#text-modal-editarrequisicao").val('');        
        
        $("#fechar-modal-inserirrequisicao").unbind().click(function(){
            $("#nome-modal-editarrequisicao").val('');           
            $("#text-modal-editarrequisicao").val('');
            fechar_modal("modal-editarrequisicao");
        });
        
        $("#salvar-modal-editarrequisicao").unbind().click(function(){
            if($("#nome-modal-editarrequisicao").val() != ''){
                nomeRequisicao = $("#nome-modal-editarrequisicao").val();           
                subtesteRequisicao = $("#text-modal-editarrequisicao").val();
                atualReqs.enviarPostRequisicao(1);
                fechar_modal("modal-editarrequisicao");                
            } else {
                alert("Preencha todos os campos");
            }
        });                        
        abrir_modal("modal-editarrequisicao");  
    }
    
    this.editarRequisicao = function(){
        $("#nome-modal-editarrequisicao").val('');           
        $("#text-modal-editarrequisicao").val('');          
        
        $("#fechar-modal-inserirrequisicao").unbind().click(function(){
            $("#nome-modal-editarrequisicao").val('');           
            $("#text-modal-editarrequisicao").val('');           
            fechar_modal("modal-editarrequisicao");
        });
        
        $("#salvar-modal-editarrequisicao").unbind().click(function(){
            if($("#nome-modal-editarrequisicao").val() != ''){
                nomeRequisicao = $("#nome-modal-editarrequisicao").val();           
                subtesteRequisicao = $("#text-modal-editarrequisicao").val();                                
                atualReqs.enviarPostRequisicao(2);
//                var html = "Prestador:" + nomePrestador +
//                        "\nTelefone:" + telPrestador +
//                        "\nEmail:" + emailPrestador +
//                        "\nEndereço:" + endPrestador +
//                        "\nServico:" + "null" +
//                        "\nSubtestes:" + "null";                                
//                $("#textarea-requisicao").val(html);
                fechar_modal("modal-editarrequisicao");                
            } else {
                alert("Preencha todos os campos");
            }
        });                    
        if(idRequisicao != 0){
            
            $.post("index.php?module=consultas&tmp=1", {
                idPrestador: 0,
                idServico: idRequisicao,
                flag: 'textoReqServico'
            }, function(result) {                 
                $("#nome-modal-editarrequisicao").val(result['servico']);           
                $("#text-modal-editarrequisicao").val(result['subtestes']);                                           
            }, "json");
            
            abrir_modal("modal-editarrequisicao");
        }  
    }
    
    this.removerRequisicao = function(){
        if(idRequisicao != 0){
            con = confirm("Deseja remover os dados?");
            if(con)
                atualReqs.enviarPostRequisicao(3);
        }
    }
    
    this.resetFunctions = function(){
        
        $("input#reqservico[value='"+idCheck+"']").prop("checked", true); 
        
        $(".servicoRow").unbind();
     
        $(".servicoRow").contextMenu({
            menu: 'list-menu-context-requisicao'            
        });
        
        $("tr.servicoRow").mousedown(function(e){            
            idRequisicao = parseInt($(this).attr("id"));              
            isCheck = $("input#reqservico[value='"+idRequisicao+"']").is(":checked");                        
            if(e.which == 1){                                
                if(!isCheck)$("input#reqservico[value='"+idRequisicao+"']").prop("checked", !isCheck);                
                if(!isCheck)
                    idCheck = idRequisicao;             
            }            
        });     
        $(".servicoRow").hover(
                function() {
                    $(this).css("background", "#C0D1E7");
                },
                function() {
                    $(this).css("background", "");
                }
        );
    }
    
    this.marcarFavorito = function(){        
        atualReqs.enviarPostRequisicao(4);
    }
    
    this.desmarcarFavorito = function(){        
        atualReqs.enviarPostRequisicao(5);
    }
}