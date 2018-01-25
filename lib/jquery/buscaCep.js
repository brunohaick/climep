function getEndereco(cep) {
    cep = cep.replace("-","");
    cep = cep.replace("________", "");
    if($.trim(cep) != ""){
        $('#loadingCep').hide();
         $('#loadingCep').removeClass("alert alert-error");
        $('#loadingCep').addClass("alert alert-success");
        $('#loadingCep').show();
        $("#loadingCep").html('Pesquisando CEP...');
        $.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+cep, function(){            
            if (resultadoCEP["resultado"] != 0) {
                $("input#cidade").val(unescape(resultadoCEP["cidade"]));
                $("input#cidadeuf").val(unescape(resultadoCEP["cidade"]));
                $("input#estado").val(unescape(resultadoCEP["uf"]));
                $("input#bairro").val(unescape(resultadoCEP["bairro"]));
                $("input#endereco").val(unescape(resultadoCEP["tipo_logradouro"]) +" "+unescape(resultadoCEP["logradouro"]));
                
                $("#loadingCep").html('');
                 $('#loadingCep').fadeOut(200).hide();
            }else{
                $("#loadingCep").html(unescape(resultadoCEP["resultado_txt"]));                
            }            
        });
        $('#num_endereco').focus();
    }
    else{
        $('#loadingCep').hide();
        $('#loadingCep').removeClass("alert alert-success");
        $('#loadingCep').addClass("alert alert-error");
        $('#loadingCep').fadeOut(200).show();
        $("#loadingCep").html('Informe o CEP');
        
    }
}
