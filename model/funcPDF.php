<?php
function ResumoCaixaPDF(){

	$dataInicio = converteData($_SESSION['resumoCaixa']['data_inicio']);
	$dataFim = converteData($_SESSION['resumoCaixa']['data_fim']);        
        
//	unset($_SESSION['resumoCaixa']);
	$array = carregaResumoCaixa($dataInicio,$dataFim);
        
	$i = 0;
	$dados['total']['dinheiro'] = 0;
	$dados['total']['cheque'] = 0;
	$dados['total']['outros'] = 0;
	$dados['total']['cartao'] = 0;
	$dados['total']['debito'] = 0;
	$dados['total']['convenio'] = 0;
	$dados['total']['cortesia'] = 0;
	$dados['total']['pendente'] = 0;
	$dados['total']['desconto'] = 0;
	$dados['cartoes']['VisaEletron'] = 0;
	$dados['cartoes']['RedeShop'] = 0;
	$dados['cartoes']['Visa'] = 0;
	$dados['cartoes']['Mastercard'] = 0;
	$dados['cartoes']['Dinners'] = 0;
	$dados['cartoes']['Yamada'] = 0;
	$dados['cartoes']['AMEX'] = 0;
	foreach ($array as $array2) {
		
		$dados[$i]['matricula'] = $array2['cliente_cliente_id'];
		$dados[$i]['data'] = converteData($array2['data']);
		$dados[$i]['responsavel'] = $array2['nomeCliente']." ".$array2['sobrenomeCliente'];
		$dados[$i]['operador'] = $array2['nomeOperador'];
		$dados[$i]['nota_fiscal'] = $array2['nota_fiscal'];
		$dados[$i]['controle'] = $array2['id'];
		$dados[$i]['valor_total'] = 0;
		$dados[$i]['atividades_caixa'] = carregaResumoCaixa_3($array2['id']);
		$cartoes = carregaResumoCaixa_4($array2['id']);
		foreach($cartoes as $cartao){
			$dados['cartoes'][$cartao['nome']] += $cartao['valor'];
		}
		$arrayzao = carregaResumoCaixa_2($array2['id']);
		foreach( $arrayzao as $arrayzinho){
			$dados[$i]['valor_total'] += $arrayzinho['valor'];
			switch($arrayzinho['forma_pagamento_id']) {
				case '1' :
					$dados[$i]['dinheiro'] = $arrayzinho['valor'];
					$dados['total']['dinheiro'] += $arrayzinho['valor'];
					break;
				case '2' :
					$dados[$i]['cheque'] = $arrayzinho['valor'];
					$dados['total']['cheque'] += $arrayzinho['valor'];
					break;
				case '3' :
					$dados[$i]['outros'] = $arrayzinho['valor'];
					$dados['total']['outros'] += $arrayzinho['valor'];
					break;
				case '4' :
					$dados[$i]['cheque'] = $arrayzinho['valor'];
					$dados['total']['cheque'] += $arrayzinho['valor'];
					break;
				case '5' :
					$dados[$i]['cartao'] = $arrayzinho['valor'];
					$dados['total']['cartao'] += $arrayzinho['valor'];
					break;
				case '6' :
					$dados[$i]['debito'] = $arrayzinho['valor'];
					$dados['total']['debito'] += $arrayzinho['valor'];
					break;
				case '7' :
					$dados[$i]['convenio'] = $arrayzinho['valor'];
					$dados['total']['convenio'] += $arrayzinho['valor'];
					break;
				case '8' :
					$dados[$i]['cortesia'] = $arrayzinho['valor'];
					$dados['total']['cortesia'] += $arrayzinho['valor'];
					break;
				case '9' :
					$dados[$i]['pendente'] = $arrayzinho['valor'];
					$dados['total']['pendente'] += $arrayzinho['valor'];
					break;
				case '10' :
					$dados[$i]['desconto'] = $arrayzinho['valor'];
					$dados['total']['desconto'] += $arrayzinho['valor'];
					break;
			}
		
		}
		$i++;
	}

	return $dados;
}

