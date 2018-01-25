<?php
if(isset($_POST['idMaterial'])) {

	$idMaterial = _INPUT('idMaterial','int','post');
	$idLote = _INPUT('idLote','int','post');

	$material = materialById($idMaterial);
	$lote = loteById($idLote);
	$dados['material'] = $material['nome'];
	$dados['loteAtual'] = $lote['nome'];
	$dados['almoxarifado'] = quantidadeMaterialByLocalId($idMaterial,'1',$idLote);
	$dados['salaImunizacao'] = quantidadeMaterialByLocalId($idMaterial,'2',$idLote);
	$dados['validade'] = converteData($lote['validade']);
	$dados['codigo'] = $material['id'];
	$dados['quantidade'] = _INPUT('quantidade','int','post');
	$dados['tipo'] = _INPUT('tipo','string','post');
	echo saidaJson($dados);

} else if (isset($_POST['submitTransferencia'])) {
	
	$tableMovimentacao = _INPUT('data','int','post');

	foreach($tableMovimentacao as $tableTV) {

		$idMaterial = $tableTV[1]; // id da vacina
		$quantidade = $tableTV[3]; // quantidade
		$tipo = $tableTV[9]; // tipo
		$nomeLote = $tableTV[4]; // nome lote
		$validadeLote = converteData($tableTV[8]); // validade
		$idLote = idLoteByNomeValidade($nomeLote,$validadeLote);

		$material = materialById($idMaterial);
		$material2X = $material['quantidade_doses']*$material['qtd_ml_por_dose'];
		if($tipo == 'Frasco') {
			$quantidade = $quantidade*$material2X;
		} else if($tipo == 'Dose') {
			$quantidade = $quantidade*$material['qtd_ml_por_dose'];
			$material['qtd_ml_por_dose'];
		}

		$dadosMovimentacao['material_id'] = $idMaterial;
		$dadosMovimentacao['lote_id'] = $idLote; 
		$dadosMovimentacao['quantidade'] = $quantidade;
		$dadosMovimentacao['data'] = date('Y-m-d');
		$dadosMovimentacao['local_movimentacao_origem_id'] =  _INPUT('origemtransf', 'int', 'post'); //Almoxarifado
		$dadosMovimentacao['local_movimentacao_destino_id'] = _INPUT('destinotransf', 'int', 'post'); //sala imunização
		$dadosMovimentacao['usuario_id'] = $_SESSION['usuario']['id'];

		$dadosSaida['motivo_id'] = _INPUT('motivotransf', 'int', 'post');
		if($dadosSaida['motivo_id'] == 1) {
			$dadosMovimentacao['flag'] = 'T';
		} else {
			$dadosMovimentacao['flag'] = 'S';
		}

		if($quantidade > 0) {

			if($dadosMovimentacao['flag'] == 'T') {
				if($quantidade <= quantidadeMaterialByLocalId($idMaterial,$dadosMovimentacao['local_movimentacao_origem_id'],$idLote)*$material2X) {
					if(insereMovimentacaoMaterial($dadosMovimentacao)) {
						$dados['id'] = mysqli_insert_id(banco::$connection);
						insereTransferencia($dados);
						$alertaSucesso['transferencia'] = 1;
					} else {
						alertaErro('Ocorreu algum erro!');
					}
				} else {
					alertaErro('Quantidade está maior do que há no Almoxarifado!');
				}
			}
			else if($dadosMovimentacao['flag'] == 'S') {
				if($quantidade <= quantidadeMaterialByLocalId($idMaterial,$dadosMovimentacao['local_movimentacao_origem_id'],$idLote)*$material2X) {
					if(insereMovimentacaoMaterial($dadosMovimentacao)) {
						$dadosSaida['id'] = mysqli_insert_id(banco::$connection);
						insereSaida($dadosSaida);
						$alertaSucesso['saida'] = 1;
					} else {
						alertaErro('Ocorreu algum erro!');
					}
				} else {
					alertaErro('Quantidade está maior do que há na Sala de Imunização!');
				}
			}

		} else {
			alertaErro('Quantidade deve ser um valor maior que zero !');
		}
	}
	if($alertaSucesso['transferencia']) {
		alertaSucesso('Transferencia Realizada com Sucesso !!');
	} else if($alertaSucesso['saida']) {
		alertaSucesso('Saída Realizada com Sucesso !!');
	}
} else {
	include('view/estoque/transferenciavacina.phtml');
}
