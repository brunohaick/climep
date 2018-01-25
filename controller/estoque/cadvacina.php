<?php

$tipoForm = _INPUT('tipoForm','String','post');
if($tipoForm == "insere-material-vacina") {

	$nomeTipoMaterial = "vacina";
	$idTipoMaterial = idTipoMaterialByNome($nomeTipoMaterial);

	$dadosMaterial['tipo_material_id'] = $idTipoMaterial;
	$dadosMaterial['codigo'] = _INPUT('codigo','int','post');
	$dadosMaterial['quantidade_doses'] = 1;
	$dadosMaterial['nome'] = _INPUT('nome','String','post');
	$dadosMaterial['preco'] = _INPUT('valor_dinheiro','float','post');
	$dadosMaterial['qtd_ml_por_dose'] = _INPUT('qtd_ml_dose','int','post');
	$dadosMaterial['preco_por_dose'] = _INPUT('valor_dinheiro','float','post');
	$dadosMaterial['descricao'] = _INPUT('descricao','String','post');
	$dadosMaterial['descricao_ingles'] = _INPUT('descricao_ingles','String','post');
	$dadosMaterial['descricao_lembrete'] = _INPUT('descricao_lembrete','String','post');
	$dadosMaterial['preco_cartao'] = _INPUT('valor_cartao','float','post');

	if(insereMaterial($dadosMaterial)) {
		echo saidaJson(1);
	}
} else {
	include('view/estoque/cadvacina.phtml');
}
