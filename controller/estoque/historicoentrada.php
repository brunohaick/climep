<?php

if($_POST['flag'] == 'histentrada'){

	$dados['dataInicio'] = converteData($_POST['dataInicio']);
	$dados['dataFim'] = converteData($_POST['dataFim']);
	$dados['fornecedor'] = $_POST['fornecedor'];
	$dados['ordenado'] = $_POST['ordenado'];

	$dadosNF = histEntradaNotaFiscal($dados);
	for($i = 0;$i < count($dadosNF);$i++) {
		$dadosNF[$i]['data_emissao'] = converteData($dadosNF[$i]['data_emissao']);
		$dadosNF[$i]['data_entrada'] = converteData($dadosNF[$i]['data_entrada']);
	}
	echo saidaJson($dadosNF);

} else if($_POST['flag'] == 'detalhamentoNF') {

	$notafiscal = $_POST['nota_fiscal'];
	$dados = detalhamentoNotaFiscal($notafiscal);
	for($i = 0;$i < count($dados);$i++) {
		$dados[$i]['validade'] = converteData($dados[$i]['validade']);
	}

	echo saidaJson($dados);

} else if($_POST['flag'] == 'removeNF') {

	$notafiscal = $_POST['nota_fiscal'];
	$entrada = listar('entrada','id', "nota_fiscal_id = '$notafiscal'");
	remover('entrada',"nota_fiscal_id ='$notafiscal'");
	foreach($entrada as $movimentacao) {
		remover('saida',"id ='{$movimentacao['id']}'");
		remover('transferencia',"id ='{$movimentacao['id']}'");
		remover('movimentacao',"id ='{$movimentacao['id']}'");
	}
	remover('parcelas_nota_fiscal',"nota_fiscal_id ='$notafiscal'");
	remover('nota_fiscal',"id ='$notafiscal'");

	$dados['resultado'] = 'sucesso';
	echo saidaJson($dados);

} else if($_POST['flag'] == 'detalhaItem') {

	$idMaterial = $_POST['material'];
	$material = ver('material','*',"id = $idMaterial");
	$array = totalEntradaMaterial($idMaterial);
	$total = $array['saldo'];

	$dados['saldo'] = estoqueMaterial($idMaterial,'consumo');
	$dados['consumo'] = estoqueMaterial($idMaterial,'total');
	$dados['media'] = frascosML($material['quantidade_doses'],$material['qtd_ml_por_dose'],$total/30);;
	$dados['total'] = $total;

	echo saidaJson($dados);
} else {
	include('view/estoque/historicoentrada.phtml');
}
