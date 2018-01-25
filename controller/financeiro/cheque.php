<?php

$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "cheque_busca") {

	$arr['fornecedores'] = buscaNomeFornecedores();
	$arr['fornecedores'][] = "TODOS";

	echo saidaJson($arr);

} else if($tipoForm == "busca_por_codigo") {

	$cod = _INPUT('cod','int','post');

	if($cod == '00') {
		$nome = "TODOS";
	} else {
		$nome = buscaNomeFornecedorPorCodigo($cod);
	}

	echo saidaJson($nome);
} else {
	include('view/financeiro/cheque.phtml');
}
?>
