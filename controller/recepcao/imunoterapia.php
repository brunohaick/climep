<?php

include_once 'model/banco.php';

//Aqui entra o codigo dos modulos.


function buscarTodosServicobyClienteID($clienteid, $material) {
	$sql = "SELECT * FROM servico s WHERE s.cliente_cliente_id = '$clienteid' AND s.material_id = '$material' ORDER BY s.id DESC ;";
	//die($sql);
	$link = Database::query($sql);
	if (Database::isValidNumRows(1)) {
		return Database::fetchAll();
	} else {
		return false;
	}
}

function buscarPrecoporNome($nome) {
	if (strlen($nome) > 1) {
		$sqlBuscaPreco = 'SELECT preco_cartao FROM `material` WHERE nome LIKE \'' . retira_acentos($nome) . '\' OR id = \'' . $nome . '\'';
		//	var_dump($sqlBuscaPreco);die();
		$resultado = mysqli_query(banco::$connection, $sqlBuscaPreco);
		//var_dump($resultado);die();		
		$result = mysqli_fetch_assoc($resultado);
//var_dump($result);die();
		return $result['preco_cartao'];
	}
}

function buscarQuantMlporNome($nome) {
	if (strlen($nome) > 1) {
		$sqlBuscaPreco = 'SELECT qtd_ml_por_dose FROM `material` WHERE nome LIKE \'' . retira_acentos($nome) . '\' OR id = \'' . $nome . '\'';
		$resultado = mysqli_query(banco::$connection, $sqlBuscaPreco);
		$result = mysqli_fetch_assoc($resultado);
		return $result['qtd_ml_por_dose'];
	}
}

function buscarMeterialIDporNome($nome) {
	if (strlen($nome) > 1) {
		$sqlBuscaPreco = 'SELECT id FROM material WHERE nome LIKE \'' . retira_acentos($nome) . '\' OR id = \'' . $nome . '\'';
//		die($sqlBuscaPreco);
//		var_dump($sqlBuscaPreco);
		$resultado = mysqli_query(banco::$connection, $sqlBuscaPreco);
		$result = mysqli_fetch_assoc($resultado);
//		var_dump($result);
		return $result['id'];
	}
}

function buscarNomeMeterialporID($id) {
	mysqli_set_charset(banco::$connection, 'utf8');
	$sqlBuscaPreco = 'SELECT nome FROM material WHERE id = \'' . $id . '\'';
	$resultado = mysqli_query(banco::$connection, $sqlBuscaPreco);
	$result = mysqli_fetch_assoc($resultado);
	return $result['nome'];
}

function contagemPorColuna($array, $coluna) {
	$contador = 0;
	for ($i = 3; $i < sizeof($array); $i++) {
		if ($array[$i][$coluna] !== '') {
			$contador++;
		}
	}
	return $contador;
}

$look = $_GET['look'];

if ($look == 'SessionSave') {//Essa funçao deveria fazer update se for modulo, mas ela não sabe quando é modulo :D
	$imunoterapias = $_POST['imunoterapias']; //Array Contendo as Imunoterapias
	$clienteID = $_POST['cliente']; // idCliente !!!WARNING!!! GERALMENTE DEPENDENTE!
	$ismodulo = $_POST['modulo'];
	$Servicoid = null;
	$nomeImunotratamento = null;
	$contador = 0;
	//var_dump($imunoterapias);die();
	for ($i = 0; $i < sizeof($imunoterapias); $i = $i + 1) {
		for ($j = 0; $j < sizeof($imunoterapias[$i]); $j = $j + 1) {
			if ($imunoterapias[$i][$j] != '' || !empty($imunoterapias[$i][$j])) {
				if ($i == 0 && isset($ismodulo) && $ismodulo == true) {//Modulo sempre vai ser frasco
					echo "modulo";
					$nomeImunotratamento[$j] = $imunoterapias[0][$j];
					//echo 'nome:'.$nomeImunotratamento[$j].' - ';
					//echo 'Codigo Imuno:'.$nomeImunotratamento[$j];
					$count = contagemPorColuna($imunoterapias, $j); //Problema! #1 Vai funcionar, pois as doses sempre são adicionadas inicialmente em colunas diferentes
					$preco = buscarPrecoporNome($nomeImunotratamento[$j]);
					$materialID = $nomeImunotratamento[$j];//buscarMeterialIDporNome($nomeImunotratamento[$j]);
					$tipoServico = 0;

					$sql_imuno_frasco = "INSERT INTO imuno_frasco(material_id,cliente_id,data) VALUES ('$materialID','$clienteID',CURDATE())";
					mysqli_query(banco::$connection, $sql_imuno_frasco);
					$frasco_id = mysqli_insert_id(banco::$connection);
					// encontrar modulos_has_material_id 
					$sqlModulos_has_material = 'SELECT a.id as \'id\'FROM `modulos_has_material` a INNER JOIN `modulos` b ON a.modulos_id = b.id WHERE b.cliente_id = \'' . $clienteID . '\' ORDER BY b.id DESC';
					//die($sqlModulos_has_material);
					$queryLink_Modulos_has_material = mysqli_query(banco::$connection, $sqlModulos_has_material);
					if ($row = mysqli_fetch_row($queryLink_Modulos_has_material)) {
						$modulos_has_material_id = $row[0];
						//var_dump($row);die();
						$sqlServico = 'INSERT INTO `servico`'
								. '(modulo_has_material_id,cliente_cliente_id,qtd_doses,preco,'
								. 'material_id,status_id,finalizado,'
								. 'data,usuario_id, tipo_servico) VALUES(\'' . $modulos_has_material_id . '\',\'' . $clienteID . '\',\''
								. '' . $count . '\',\'' . $preco . '\',\'' . $materialID . '\''
								. ',\'2\',\'0\',DATE(NOW()),\'' . $_SESSION['usuario']['id'] . '\', \'' . $tipoServico . '\')';

						$link = mysqli_query(banco::$connection, $sqlServico);
						$Servicoid[$j] = mysqli_insert_id(banco::$connection);
						$sqlRelacao = "INSERT INTO imuno_controle (servico_id,frasco_id) VALUES('$Servicoid[$j]','$frasco_id');";
						mysqli_query(banco::$connection, $sqlRelacao);
						//die('1');/ vou te matar!
					} else {
						die('0');
						//die('Erro ao encontrar modulo em aberto para o CLIENTE_ID =' . $clienteID);
					}
				} else {
					//echo 'imuno';//ok
					if ($i === 0) {
						$nomeImunotratamento[$j] = $imunoterapias[0][$j];
						$count = contagemPorColuna($imunoterapias, $j); //Problema! #1 Vai funcionar, pois as doses sempre são adicionadas inicialmente em colunas diferentes
						$preco = buscarPrecoporNome($nomeImunotratamento[$j]);
						$materialID = buscarMeterialIDporNome($nomeImunotratamento[$j]);
						if (!buscaservicoJaexiste($materialID, $clienteID) || $count > 4) {

							/**
							 * Decide se eh dose ou frasco.
							 * 
							 * Se count for menor que 4 doses, é dose, senão é frasco
							 */
							if ($count < 4) {
								//echo '- dose';
								$tipoServico = 0; // dose
								//procura por frascos pre existentes:
								$sql_procura_frasco = "SELECT a.frasco as 'frasco' FROM (SELECT imuno_controle.frasco_id as 'frasco',IF(count(material_id) <= material.quantidade_doses,1,0) as 'contagem' FROM imuno_frasco "
										. " INNER JOIN imuno_controle "
										. " ON imuno_controle.frasco_id = imuno_frasco.id "
										. " INNER JOIN material "
										. " ON material.id = imuno_frasco.material_id"
										. " WHERE material_id = '$materialID' AND cliente_id = '$clienteID'"
										. " ORDER BY contagem DESC) as a"
										. " WHERE a.contagem = 1;";
								$resultado = mysqli_query(banco::$connection, $sql_procura_frasco);
								if ($row = mysqli_fetch_row($resultado)) {
									$sqlServico = 'INSERT INTO `servico`'
											. '(cliente_cliente_id,qtd_doses,preco,'
											. 'material_id,status_id,finalizado,'
											. 'data,usuario_id, tipo_servico) VALUES(\'' . $clienteID . '\',\''
											. '' . $count . '\',\'' . $preco . '\',\'' . $materialID . '\''
											. ',\'2\',\'0\',DATE(NOW()),\'' . $_SESSION['usuario']['id'] . '\', \'' . $tipoServico . '\')';
									$link = mysqli_query(banco::$connection, $sqlServico);
									$Servicoid[$j] = mysqli_insert_id(banco::$connection);
									$frasco_id = $row['frasco'];
									$sqlRelacao = "INSERT INTO imuno_controle (servico_id,frasco_id) VALUES('$Servicoid[$j]','$frasco_id');";
									mysqli_query(banco::$connection, $sqlRelacao);
								} else {
									//Insere frasco se nao encontrar
									$sql_imuno_frasco = "INSERT INTO imuno_frasco(material_id,cliente_id,data)VALUES('$materialID','$clienteID',CURDATE())";
									mysqli_query(banco::$connection, $sql_imuno_frasco);
									$frasco_id = mysqli_insert_id(banco::$connection);
									$sqlServico = 'INSERT INTO `servico`'
											. '(cliente_cliente_id,qtd_doses,preco,'
											. 'material_id,status_id,finalizado,'
											. 'data,usuario_id, tipo_servico) VALUES(\'' . $clienteID . '\',\''
											. '' . $count . '\',\'' . $preco . '\',\'' . $materialID . '\''
											. ',\'2\',\'0\',DATE(NOW()),\'' . $_SESSION['usuario']['id'] . '\', \'' . $tipoServico . '\')';
									$link = mysqli_query(banco::$connection, $sqlServico);
									$Servicoid[$j] = mysqli_insert_id(banco::$connection);
									$sqlRelacao = "INSERT INTO imuno_controle (servico_id,frasco_id) VALUES('$Servicoid[$j]','$frasco_id');";
									mysqli_query(banco::$connection, $sqlRelacao);
								}
							} else {
								/**
								 * FRASCO
								 */
								$tipoServico = 1; //frasco
								$sqlServico = 'INSERT INTO `servico`'
										. '(cliente_cliente_id,qtd_doses,preco,'
										. 'material_id,status_id,finalizado,'
										. 'data,usuario_id, tipo_servico) VALUES(\'' . $clienteID . '\',\''
										. '' . $count . '\',\'' . $preco . '\',\'' . $materialID . '\''
										. ',\'2\',\'0\',DATE(NOW()),\'' . $_SESSION['usuario']['id'] . '\', \'' . $tipoServico . '\')';
								$link = mysqli_query(banco::$connection, $sqlServico);
								$Servicoid[$j] = mysqli_insert_id(banco::$connection);
							}
						} else {
							//Acha o servico
							$sql_procura_frasco = "SELECT a.frasco FROM (SELECT @a1 := imuno_frasco.id as 'frasco',IF((SELECT count(*) FROM imuno_controle WHERE frasco_id = @a1) <= material.quantidade_doses,1,0) as 'Contagem'
												FROM imuno_frasco
												LEFT JOIN imuno_controle  ON imuno_controle.frasco_id = imuno_frasco.id  
												INNER JOIN material  ON material.id = imuno_frasco.material_id 
												WHERE material_id = '$materialID' AND cliente_id = '$clienteID' ) a WHERE a.contagem = 1";

							$resultado = mysqli_query(banco::$connection, $sql_procura_frasco);
							if ($row = mysqli_fetch_row($resultado)) {
								$sqlServico = 'INSERT INTO `servico`'
										. '(cliente_cliente_id,qtd_doses,preco,'
										. 'material_id,status_id,finalizado,'
										. 'data,usuario_id, tipo_servico) VALUES(\'' . $clienteID . '\',\''
										. '' . $count . '\',\'' . $preco . '\',\'' . $materialID . '\''
										. ',\'2\',\'0\',DATE(NOW()),\'' . $_SESSION['usuario']['id'] . '\', \'' . $tipoServico . '\')';
								$link = mysqli_query(banco::$connection, $sqlServico);
								$Servicoid[$j] = mysqli_insert_id(banco::$connection);
								$frasco_id = $row[0];
								$sqlRelacao = "INSERT INTO imuno_controle (servico_id,frasco_id) VALUES('$Servicoid[$j]','$frasco_id');";
								Database::query($sqlRelacao);
								$sqlServico = "SELECT "
										. "servico.id as 'servico',"
										. "material.tipo_material_id as 'tipo' "
										. "FROM "
										. "`servico` "
										. "INNER JOIN `material` ON material.id = servico.material_id "
										. "WHERE "
										. "servico.material_id = '$materialID' "
										. " AND servico.cliente_cliente_id='$clienteID' "
										. " AND material.tipo_material_id = '2'"
										. " AND servico.qtd_doses < material.quantidade_doses";
								Database::query($sqlServico);
								$resultado = Database::fetchAll();
								$Servicoid[$j] = $resultado[0]['servico'];
							} else {
								//Insere frasco se n�o encontrar
								$sql_imuno_frasco = "INSERT INTO imuno_frasco(material_id,cliente_id,data)VALUES('$materialID','$clienteID',CURDATE())";
								mysqli_query(banco::$connection, $sql_imuno_frasco);
								$frasco_id = mysqli_insert_id(banco::$connection);
								$sqlServico = 'INSERT INTO `servico`'
										. '(cliente_cliente_id,qtd_doses,preco,'
										. 'material_id,status_id,finalizado,'
										. 'data,usuario_id, tipo_servico) VALUES(\'' . $clienteID . '\',\''
										. '' . $count . '\',\'' . $preco . '\',\'' . $materialID . '\''
										. ',\'2\',\'0\',DATE(NOW()),\'' . $_SESSION['usuario']['id'] . '\', \'' . $tipoServico . '\')';
								$link = mysqli_query(banco::$connection, $sqlServico);
								$Servicoid[$j] = mysqli_insert_id(banco::$connection);
								$sqlRelacao = "INSERT INTO imuno_controle (servico_id,frasco_id) VALUES('$Servicoid[$j]','$frasco_id');";
								$resultadoRelacao = mysqli_query(banco::$connection, $sqlRelacao);
							}
							//Aqui podemos inserir a funçao de update acho
						}//Aqui pra cima é só a aparte de frasco e dose
						//echo ' - Aqui('.$i.') ';
					} else {
						//echo ' aqui no else ('.$i.') -';
						if ($i > 2) {//Aqui ficam os modulos de imuno.
							$data = $imunoterapias[$i][$j];
							$data = converteData($data);
							$hoje = date("Y-n-j");
							$status_id = 2;
							$data = explode('-', $data);
							if (10 - $data[1] > 0) {
								$data[1] = str_replace('0', '', $data[1]);
							}
							if (10 - $data[0] > 0) {
								$data[0] = str_replace('0', '', $data[0]);
							}
							$data = $data[0] . '-' . $data[1] . '-' . $data[2];

							if ($hoje == $data) {
								$status_id = 6;
							} else {
								$status_id = 2;
							}
							//esse é novo? eh acho ehuaheuahe eh 
//						var_dump($hoje,$data);
							$quant_ml = buscarQuantMlporNome($nomeImunotratamento[$j]);
							//Verifica Existencia do Historico pela data, se nao existir um novo eh criado.
							if (!buscahistoricoJaexiste($Servicoid[$j], $data)) {
								$sqlHistorico = 'INSERT INTO `historico`(servico_id,status_id,status_pagamento,data,qtd_ml) VALUES(\'' . $Servicoid[$j] . '\',\'' . $status_id . '\',\'16\',\'' . $data . '\',\'' . $quant_ml . '\')';
							///var_dump($sqlHistorico);
								$link = mysqli_query(banco::$connection, $sqlHistorico);
								atualizaQtdDoseServico($Servicoid[$j]);
							}
						}
					}
				}
			}
		}
	}
	//Criar Controles
	//Criar Guia
	die();
} else {
	if ($look == 'SessionGet') {
		
	} else {
		if ($look == 'ListGroup') {
			$clienteID = $_POST['cliente'];
			$sql = 'SELECT DISTINCT g.nomeGrupo,g.id FROM `material` m INNER JOIN `grupo_material` g ON m.grupo_material_id = g.id INNER JOIN `servico` s ON m.id = s.material_id INNER JOIN `cliente` c ON c.cliente_id=  s.cliente_cliente_id WHERE s.cliente_cliente_id = ' . $clienteID . ' ';
//			die($sql);
			$link = mysqli_query(banco::$connection, $sql);
			$result = Array();
			$contador = 0;
			while ($row = mysqli_fetch_assoc($link)) {
				$result[$contador] = $row;
				$contador++;
			}
			echo saidaJson($result);
			die();
		} else {
			if ($look == 'ShowGroup') {
				$clienteID = $_POST['cliente'];
				$grupoID = $_POST['grupo'];
				$sql = 'SELECT g.nomeGrupo,m.id,m.nome FROM `material` m INNER JOIN `grupo_material` g ON m.grupo_material_id = g.id INNER JOIN `servico` s ON m.id = s.material_id INNER JOIN `cliente` c ON c.cliente_id=  s.cliente_cliente_id WHERE g.id = ' . $grupoID . ' AND s.cliente_cliente_id = ' . $clienteID . ';';
				$link = mysqli_query(banco::$connection, $sql);
				$result = Array();
				$contador = 0;
				while ($row = mysqli_fetch_assoc($link)) {
					$result[$contador] = $row;
					$contador++;
				}
				echo saidaJson($result);
				die();
			} else {
				if ($look == 'SearchMaterialbyGroup') {
					$grupoID = $_POST['grupo'];
					$clienteID = $_POST['cliente'];
					$sqlBuscaMateriais = 'SELECT id FROM material m WHERE m.grupo_material_id =' . $grupoID . ' AND m.tipo_material_id =2';
					//var_dump($sqlBuscaMateriais);
					$link1 = mysqli_query(banco::$connection, $sqlBuscaMateriais);
					$result = Array();
					while ($linha = mysqli_fetch_assoc($link1)) { // Loop para percorer os materiais
						$materialID = $linha['id'];
						//var_dump($materialID);
						$Servicos = buscarTodosServicobyClienteID($clienteID, $materialID);
						if (!empty($Servicos)) {
							foreach ($Servicos as $servico) {//Loop dos servicos
								$servicoid = $servico['id'];
								$sqlMarcacoes = "SELECT f.cor_hex as 'Cor',m.id AS 'MaterialID', m.nome AS 'Nome', s.id AS 'ServicoID',"
										. " h.data AS 'Data' FROM `material` m INNER JOIN `servico` s ON s.material_id = m.id "
										. "INNER JOIN `historico` h ON h.servico_id = s.id INNER JOIN `status`"
										. " f ON f.id = h.status_id WHERE s.id = '$servicoid' AND m.grupo_material_id ='$grupoID'  AND s.cliente_cliente_id = '$clienteID' ";
								$link2 = mysqli_query(banco::$connection, $sqlMarcacoes);
								$contador = 1;
								while ($row = mysqli_fetch_assoc($link2)) {//Loop Historicos do servico
									$nome = buscarNomeMeterialporID($row['MaterialID']);

									$result[$servicoid]['nome'] = retira_acentos($nome);
									$result[$servicoid][$contador][0] = $row['Cor'];
									$result[$servicoid][$contador][1] = $row['Data'];

									$contador++;
								}
							}
						}
					}
					//die(print_r($result)); Array OK!
					die(saidaJson($result));
				} else {
					if ($look == 'clienteIndentificacao') {
						$clienteid = $_POST['cliente'];
//						var_dump($clienteid);die();
						$itensModulo = buscaItensModuloNaoFinalizadoCliente($clienteid, 0);
						$vetor = Array();
						$contador = 0;
						if (!empty($itensModulo)) {
							foreach ($itensModulo as $itemMod) {
								$vetor[$contador][0] = $dataItem = converteData($itemMod['data_item']);
								$data = somaPosicaoData($dataItem, ($itemMod['posicao_horizontal'] - 1));
								$vetor[$contador][1] = $data;
								$vetor[$contador][2] = $itemMod['item_id'];
								$vetor[$contador][3] = $itemMod['cliente_id'];
								$vetor[$contador][4] = $itemMod['id_material'];
								$vetor[$contador][5] = $itemMod['nome_material'];
								$vetor[$contador][6] = $itemMod['posicao_horizontal'];
								$vetor[$contador][7] = $itemMod['id'];
								//							$vetor[$contador][8] = $itemMod['Cor'];
								$contador++;
							}
						}
//						var_dump($vetor);die();
						echo saidaJson($vetor);
					}
				}
			}
		}
	}
}
