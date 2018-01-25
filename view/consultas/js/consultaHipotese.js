function ListaHipoteses(){
    
    var idDoenca = null;
    var idMedico = null;
    var filtro = "";
    var checked = new Array();
    var checkedText = new Array();
    var texto = "";
    var codigo = "";
    var isCheck;

    this.setCheck = function(val){
        checked = val;
    }
    this.getCheck = function(){
        return checked;
    }
    this.eraseCheck = function(){
        checked = new Array();
    }
    
    this.setCheckText = function(val){
        checkedText = val;
    }
    this.getCheckText = function(){
        return checkedText;
    }
    this.eraseCheckText = function(){
        checkedText = new Array();
    }
        
    this.getIDDoenca = function(){
        return idDoenca;
    }
    this.setIsCheck = function(val){
        isCheck = val;
    }
    this.getIsCheck = function(){
        return isCheck;
    }
    
    this.enviarPostHipotese = function(opcao){        
        
        if(idMedico!=''){
            if(idDoenca == 0 && opcao >= 2) opcao = 0;
            $.post("index.php?module=consultas&tmp=1", {
                    idDoenca: idDoenca,
                    idMedico: idMedico,
                    opcao: opcao,
                    filtro: filtro,
                    codigo: codigo,
                    texto: texto,
                    flag: 'atualizaHipotese'
            }, function(data) {
                    if(data){
                        console.log(data);
                        data = data.replace(/\\/g, '');
                        $("tbody#tabela_HD_doencas").html(data);                        
                        atualHipot.resetFunctions();            
                        var arrayCheck = atualHipot.getCheck();
                        for(var i = 0; i < atualHipot.getCheck().length; i++){                            
                            $("input#hipoteses[name='"+arrayCheck[i]+"']").prop("checked", true);
                        }  
                    } else {
                        $("tbody#tabela_HD_doencas").html('');
                    }
            }); 
        }        
    }
    
    this.novaHipotese = function(){        
        $("#cod-modal-inserirhipotese").val('');           
        $("#text-modal-inserirhipotese").val('');
        $("#fechar-modal-inserirhipotese").unbind().click(function(){
           $("#cod-modal-inserirhipotese").val('');           
           $("#text-modal-inserirhipotese").val('');           
           fechar_modal("modal-inserirhipotese");
        });
        $("#salvar-modal-inserirhipotese").unbind().click(function(){
           if($("#text-modal-inserirhipotese").val() != "" && $("#cod-modal-inserirhipotese").val() != ""){
                codigo = $("#cod-modal-inserirhipotese").val();
                texto = $("#text-modal-inserirhipotese").val();
                atualHipot.enviarPostHipotese(1); 
                fechar_modal("modal-inserirhipotese");                
           } else {
               alert("Preencha todos os campos");
           }
           
        });                        
        abrir_modal("modal-inserirhipotese");     
    }
    
    this.editarHipotese = function(){                
        $("#cod-modal-inserirhipotese").val('');
        $("#text-modal-inserirhipotese").val('');
        $("#fechar-modal-inserirhipotese").unbind().click(function(){
           $("#cod-modal-inserirhipotese").val('');
           $("#text-modal-inserirhipotese").val('');           
           fechar_modal("modal-inserirhipotese");
        });
        $("#salvar-modal-inserirhipotese").unbind().click(function(){
           if($("#text-modal-inserirhipotese").val() != "" && $("#cod-modal-inserirhipotese").val() != ""){
                codigo = $("#cod-modal-inserirhipotese").val();
                texto = $("#text-modal-inserirhipotese").val();                
                atualHipot.enviarPostHipotese(2); 
                fechar_modal("modal-inserirhipotese");
           } else {
               alert("Preencha todos os campos");
           }
           
        });                
        $("#cod-modal-inserirhipotese").val(codigo);           
        $("#text-modal-inserirhipotese").val($(".doencaRow[id="+idDoenca+"]").children('th').text().trim());
        if(idDoenca != 0) abrir_modal("modal-inserirhipotese");     
    }
    
    this.removerHipotese = function(){
        if(idDoenca != 0){
            con = confirm("Deseja remover os dados?");
            if(con)
                atualHipot.enviarPostHipotese(3);
        }
    }
    
    this.marcarFavorito = function(){        
        atualHipot.enviarPostHipotese(4);
    }
    
    this.desmarcarFavorito = function(){        
        atualHipot.enviarPostHipotese(5);
    }
    
    this.resetFunctions = function(){        
        $("tr.doencaRow").unbind();
        $("input#hipotese").unbind();
     
        $("tr.doencaRow").contextMenu({
        	menu: 'list-menu-doencas'
	}); 
        
        var cb = false;
        
        $("input#hipoteses").mousedown(function(e){
            cb = true;
        });
        
        $("tr.doencaRow").mousedown(function(e){            
            idDoenca = parseInt($(this).attr("id"));
            idMedico = $("#medico-hipotese").val();
            filtro = $("#filtro-hipotese").val().trim();
            codigo = $("tr.doencaRow[id="+idDoenca+"]").attr('value');
            isCheck = $("input#hipoteses[name='"+idDoenca+"']").is(":checked");
            if(e.which == 1){
                if(cb!=true){
                    $("input#hipoteses[name='"+idDoenca+"']").prop("checked", !isCheck);
                    cb = false;
                }
                if(!isCheck) cb = false;
                atualizaTextareaHipotese();
            }            
        });     
        $("tr.doencaRow").hover(
                function() {
                    $(this).css("background", "#C0D1E7");
                },
                function() {
                    $(this).css("background", "");
                }
        );
    }
    
}