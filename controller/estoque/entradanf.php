<?php

$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "busca_data_parcelas") {

	$data = _INPUT('data_venc','string','post');
	$num_parcelas = _INPUT('num_parcelas','string','post');
	$intervalo = _INPUT('intervalo_parcelas','string','post');
	$tipo = 'dia';
	$qtd = 0;

	$arrData[] = somarData($data, $qtd, $tipo);

	for($i = 1; $i < $num_parcelas; $i++) {

		$qtd = $i * $intervalo;
		$arrData[] = somarData($data, $qtd, $tipo);
	}

	echo saidaJson($arrData);
	exit;

} else if($tipoForm == "busca_preco_material") {
	$idMat = $_POST['idMat'];
	$precoMat = buscaPrecoMaterial($idMat, 1); //preco cartao

	die(saidaJson(number_format($precoMat['preco'], 2, ',', '.')));
	
} else if($tipoForm == "busca_codigo_fornecedor") {

	$id = _INPUT('id','string','post');
	$cod = (int) buscaCodFornecedorPorFornecedorId($id);
	echo saidaJson($cod);
	exit;

} else if($tipoForm == "busca_fornecedor_por_codigo") {

	$codigo = _INPUT('codigo','string','post');
	$idFornecedor = (int) buscaIdFornecedorPorCodigo($codigo);
	echo saidaJson($idFornecedor);
	exit;
}

if(isset($_POST['idMaterial'])) {

	$idMaterial = _INPUT('idMaterial','int','post');
	
	$dadosLote['nome'] = 
	$dadosLote['validade'] = converteData();

	$material = materialById($idMaterial);
	$dados['codigo'] = $material['id'];
	$dados['material'] = $material['nome'];

	$dados['loteAtual'] = _INPUT('lote','int','post');
	$dados['validade'] = _INPUT('validade','int','post');
	$dados['quantidade'] = _INPUT('quantidade','int','post');
	$dados['tipo'] = _INPUT('tipo','string','post');
	$dados['custo'] = _INPUT('custo','string','post');

	echo saidaJson($dados);

} else if(isset($_POST['submitEntrada'])) {

	$dadosNF['fornecedores_id'] = _INPUT('nffornecedorlarge','int','post');
	$dadosNF['frete_id'] = _INPUT('nffrete','int','post');
	$dadosNF['valor_frete'] = trataValorMoeda(_INPUT('nfvalorfrete', 'string', 'post'));
	$dadosNF['nota_fiscal'] = _INPUT('numnf', 'string', 'post');
	$dadosNF['valor_nota_fiscal'] = trataValorMoeda(_INPUT('nftotalnota', 'string', 'post'));
	$dadosNF['data_entrada'] = converteData(_INPUT('nfdataentrada', 'string', 'post'));
	$dadosNF['data_emissao'] = converteData(_INPUT('nfdataemissao', 'string', 'post'));
	$dadosNF['tipo'] = _INPUT('tipo', 'string', 'post');
	$dadosNF['banco_id'] = _INPUT('banco','int','post');
	$dadosNF['usuario_id'] = $_SESSION['usuario']['id'];

	$parcelas = _INPUT('parcelas', 'string', 'post');

	if ($dadosNF['nota_fiscal'] && $dadosNF['frete_id']) {
		if (confereExistenciaNotaFiscal($dadosNF)) {
			$dadosEntrada['nota_fiscal_id'] = idNotaFiscalByDados($dadosNF);
			$alertaSucesso['nota_fiscal'] = 1;
		} else {
			if (insereNotaFiscal($dadosNF)){
				$dadosEntrada['nota_fiscal_id'] = mysqli_insert_id(banco::$connection);
				$dadosParcela['nota_fiscal_id'] = $dadosEntrada['nota_fiscal_id'];

				foreach($parcelas as $parcela) {
					$dadosParcela['numero'] = $parcela[1];
					$dadosParcela['vencimento'] = converteData($parcela[2]);
					$dadosParcela['valor'] = $parcela[3];
					insereNotaFiscalParcela($dadosParcela);
				}

				$alertaSucesso['nota_fiscal'] = 1;
			} else {
				alertaErro('Ocorreu algum erro na NF!');
				exit;
			}
		}
	} else {
		alertaErro($msgs['ERRO_PREENCHA_DADOS']);
		exit;
	}
	
	$tableTransf = _INPUT('data','int','post');

	foreach($tableTransf as $tableTV) {

		$idMaterial = $tableTV[1]; // id da vacina
		$quantidade = $tableTV[5]; // quantidade
		$dadosEntrada['valor_unit'] = trataValorMoeda($tableTV[7]);
		$tipo = $tableTV[6]; // tipo
		$dadosLote['nome'] = $tableTV[3]; // nome lote
		$dadosLote['validade'] = converteData($tableTV[4]); // validade
		$dadosLote['uso_id'] = 1; // tipo de uso
		$dadosLote['data'] = date('Y-m-d');

		if(confereExistenciaLote($dadosLote)) {
			$idLote = idLoteByNomeValidade($dadosLote['nome'],$dadosLote['validade']);
			$alertaSucesso['lote'] = 1;
		} else {
			if(insereLote($dadosLote)){
				$idLote = mysqli_insert_id(banco::$connection);
				$alertaSucesso['lote'] = 1;
			} else {
				alertaErro('Ocorreu algum erro no lote!');
				exit;
			}
		}

		$material = materialById($idMaterial);
		if($tipo == 'Frasco') {
			$quantidade = $quantidade*$material['quantidade_doses']*$material['qtd_ml_por_dose'];
		} else if($tipo == 'Dose') {
			$quantidade = $quantidade*$material['qtd_ml_por_dose'];
		}

		$dadosMovimentacao['material_id'] = $idMaterial;
		$dadosMovimentacao['lote_id'] = $idLote; 
		$dadosMovimentacao['data'] = date('Y-m-d');
		$dadosMovimentacao['local_movimentacao_origem_id'] = _INPUT('origemtransf', 'int', 'post');  // de fora por entrada
		$dadosMovimentacao['local_movimentacao_destino_id'] = _INPUT('destinotransf', 'int', 'post'); //Almoxarifado
		$dadosMovimentacao['flag'] = 'E'; //entrada
		$dadosMovimentacao['quantidade'] = $quantidade;
		$dadosMovimentacao['usuario_id'] = $_SESSION['usuario']['id'];

		if($quantidade > 0) {

			if(insereMovimentacaoMaterial($dadosMovimentacao)) {
				$dadosEntrada['id'] = mysqli_insert_id(banco::$connection);
				insereEntrada($dadosEntrada);
				$alertaSucesso['entrada'] = 1;
			} else {
				alertaErro('Ocorreu algum erro!');
				exit;
			}
		} else {
			alertaErro('Quantidade deve ser um valor maior que zero !');
		}
	}

	if($alertaSucesso['nota_fiscal'] && $alertaSucesso['lote'] && $alertaSucesso['entrada'] ) {
		alertaSucesso('Entrada Realizada com Sucesso !!');
	}

} else {
	include('view/estoque/entradanf.phtml');
}
