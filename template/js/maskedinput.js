/* 
 * Máscara no campos input
 */

/**
 * Máscaras do formulario de Cadastro
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#cad-nascimento").mask("99/99/9999");
    $("#dep-nascimento").mask("99/99/9999");
    $("#cad-pamp").mask("99/99/9999");
    $("#cad-cep").mask("99999-999");
    $("#fone-residencial").mask("(99) 9999-9999");
    $("#fone-comercial").mask("(99) 9999-9999");
    $("#fone-apoio").mask("(99) 9999-9999");
    $("#doc-nf").mask("999.999.999-99");


});

/**
 * Máscaras do formulario de sala de espera
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#data-estoque").mask("99/99/9999");
});

/**
 * Máscaras do formulario de Nota Fiscal
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#nf-data-emissao").mask("99/99/9999");
    $("#nf-data-entrada").mask("99/99/9999");
    $("#em-validade").mask("99/99/9999");
});

jQuery(function($){
    $("#he-dataInicio").mask("99/99/9999");
    $("#he-dataFim").mask("99/99/9999");
});

/**
 * Máscaras do formulario de Ultimas Entradas
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#ue-dataInicio").mask("99/99/9999");
    $("#ue-dataFim").mask("99/99/9999");
});

/**
 * Máscaras do formulario de mapa de consumo
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#mc-dataInicio").mask("99/99/9999");
    $("#mc-dataFim").mask("99/99/9999");
});

/**
 * Máscaras do formulario de Agenda Medica
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#agenda-calendario").mask("99/99/9999");
});

/**
 * Máscaras do formulario de Guia Tiss
 * e Guia Tiss Consulta
 * @author Bruno Haick
 *
 */
jQuery(function($){
    $("#guia-tiss-data_autorizacao").mask("99/99/9999");
    $("#guia-tiss-cep").mask("99999-999");
    $("#guia-tiss-data_validade_carteira").mask("99/99/9999");

    $("#guia-tiss-consulta-data_autorizacao").mask("99/99/9999");
    $("#guia-tiss-consulta-cep").mask("99999-999");
    $("#guia-tiss-consulta-data_validade_carteira").mask("99/99/9999");
});


/**
 * Máscaras do formulario de Triagem em Consulta 
 * @author Marcus Dias
 *
 */
jQuery(function($){
    $("#linguinha-reavaliacao").mask("99/99/9999");
    $("#olhinho-reteste-data").mask("99/99/9999");
    $("#O1-reavaliacao").mask("99/99/9999");
    $("#O2-reavaliacao").mask("99/99/9999");
    $("#data-retorno-agenda").mask("99/99/9999");
    $("#hora-retorno-agenda").mask("99:99");

});

/*Ficha Vacina*/
jQuery(function($){
    $("#ficha-vacina-data").mask("99/99/9999");
    $("#vaci-nasc").mask("99/99/9999");

});

/* CAIXA */
jQuery(function($){
    $("#cep-cliente-recibo").mask("99999-999");

});

/* CAIXA */
jQuery(function($){
    $("#arc-horario-celular").mask("(99)9999-9999");
    $("#arc-horario-contato").mask("(99)9999-9999");

});

/* Estoque historico entrada */
jQuery(function($){
    $("#hist-entrada-data-inicio").mask("99/99/9999");
    $("#hist-entrada-data-fim").mask("99/99/9999");

});

