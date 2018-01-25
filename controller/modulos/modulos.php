<?php

require_once 'model/banco.php';
//Aqui entra o codigo dos modulos.

$look = $_GET['look'];

if ($look == "preco_geral") {

	$vacinas = $_POST['vacinas'];
	for ($i = 0; $i < sizeof($vacinas); $i++) {
		$resultadoVista = vacinapreco_vista($vacinas[$i]);
		$resultadoCartao = vacinapreco_cartao($vacinas[$i]);
		$valores[$i][0] = $resultadoVista;
		$valores[$i][1] = $resultadoCartao;
	}
	echo saidaJson($valores);
	die();
} else {
	if ($look == "preco_vista") {
		$vacina = $_GET['vacina'];
		conectar();
		$resultado = vacinapreco_vista($vacina);
		if ($resultado >= 0) {
			die($resultado);
		} else 
			die(0);
	} else if ($look == "preco_cartao") {
		$vacina = $_GET['vacina'];
		$resultado = vacinapreco_cartao($vacina);
		if ($resultado >= 0) {
			die($resultado);
		} else
			die(0);
	} else if ($look == "listavacina") {
		conectar();
		$vacinas = $_POST['vacinas'];
		$vacinasArray = listavacinasDisponiveis($vacinas);
//    var_dump($vacinasArray);die();  
		for ($i = 0; $i < count($vacinasArray); $i++) {
			$resultado = $resultado . '|' . $vacinasArray[$i]["nome"];
		}
		die($resultado);
	} else if ($look == "sessao") {
		//Inserir modulo,
		//Inserir por loop as variaveis da sessao;
		include_once 'model/funcGerais.php';
		$posicaoHorizontal = $_POST['posicaoHorizontal'];
		$posicaoVertical = $_POST['posicaoVertical'];
		$nomevacina = $_POST['vacina'];
		$valor = isset($_POST['resultado']) ? '0' : $_POST['resultado'];
		$idVacina = dadosVacinaPorNome($nomevacina);
		$_SESSION ['modulos'][$posicaoVertical][$posicaoHorizontal] = $idVacina['id'] . '||' . $valor;
		die();
	} else if ($look == "sessaoKill") {

		//Inserir por loop as variaveis da sessao;
		include_once 'model/funcGerais.php';
		$posicaoHorizontal = $_POST['posicaoHorizontal'];
		$posicaoVertical = $_POST['posicaoVertical'];
		unset($_SESSION ['modulos'][$posicaoVertical][$posicaoHorizontal]);
		//print_r($_SESSION ['modulos']);sessaoSave
		die();
	} else if ($look == "sessaoKillAll") {
		unset($_SESSION ['modulos']);
		die();
	} else if ($look == "sessaoSave") {
		$tipo = 0;
		$contador = 0;
		foreach ($_SESSION['modulos'] as $i => $value) {
			foreach ($value as $j => $materialid) {
				$contador++;
				$tipo = $tipo + verificaMaterialModulo($materialid);
			}
		}
//		var_dump($tipo);
		if ($tipo != $contador) {
			$tipo = '0';
		} else {
			$tipo = '1';
		}

		$preco = $_POST['precomodulo']; //Preço escolhido
		$preco_cartao = $_POST['precomodulo_cartao'];
		$preco_vista = $_POST['precomodulo_vista'];
		$parcelas = $_POST['parcela'];
		$clienteid = $_POST['clienteid'];
		$midiaid = $_POST['midiaid'];
		$medicoid = $_POST['medicoid'];
		$climepid = $_POST['climepid'];
		$descBCG = $_POST['descontoBCG'] == 'true' ? '1' : '0';
		$descMedico = $_POST['descontoMedico'] == 'true' ? '1' : '0';
		$descPromocional = $_POST['descontoPromocional'] == 'true' ? '1' : '0';
		$dados = array(
			'cliente_id' => $clienteid,
			'indicacao_medico_id' => $medicoid,
			'midias_id' => $midiaid,
			'usuario_id' => $climepid,
			'data' => date('Y-m-d'),
			'preco' => $preco,
			'preco_cartao' => $preco_cartao,
			'preco_vista' => $preco_vista,
			'parcelas' => $parcelas,
			'finalizado' => '0',
			'descontoBCG' => $descBCG,
			'descontoMedico' => $descMedico,
			'descontoPromocional' => $descPromocional,
			'tipo' => $tipo
		);
		insereModulos($dados);
		$moduloid = mysqli_insert_id(banco::$connection);
		echo $moduloid;
		foreach ($_SESSION['modulos'] as $i => $value) {
			foreach ($value as $j => $materialid) {
				$material = explode('||', $materialid); // Modificaç�o de emergencia feita para salvar no banco o valor do produto
//                                die(print_r($material));
				insereModulosItem($moduloid, $material[0], $material[1], $i, $j, 1);
			}
		}
		die();
	} else {
		if ($look == 'DependenteFamiliaExistente') {
			$idDependente = $_POST['idDependente'];
			$idTitular = $_POST['idTitular'];
			Transferencia_AdicionarDependenteFamilia($idDependente, $idTitular);
			die('1');
		} else {
			if ($look == 'TitularFamiliaExistente') {
				$idDependente = $_POST['idDependente'];
				$idTitular = $_POST['idTitular'];
				Transferencia_TrocaTitularFamilia($idDependente, $idTitular);
				die('1');
			} else {
				if ($look == 'TitularNovaFamilia') {
					$idDependente = $_POST['idDependente'];
					Transferencia_NovaFamilia($idDependente);
					die('1');
				} else {
					if ($look == 'TitularPropriaFamilia') {
						$idDependente = $_POST['idDependente'];
						Transferencia_TitularDaPropriaFamilia($idDependente);
						die('1');
					} else {
						if ($look == 'DeletarModulo') {
							$cliente = $_POST['cliente'];
							$sqlRemoveModulos = 'UPDATE `modulos` SET finalizado = \'1\' WHERE cliente_id = \'' . $cliente . '\' AND tipo = \'0\'';
							mysqli_query(banco::$connection, $sqlRemoveModulos);
							die('1');
						}
					}
				}
			}
		}
	}
}
die("Nothing to do");
