<?php

$tipoForm = _INPUT('tipoForm', 'string', 'post');

if ($tipoForm == "extrato_financeiro") {
            
    
	$conta_corrente_id = _INPUT('conta_corrente', 'string', 'post');
	$data_inicio = converteData(_INPUT('data_inicio', 'string', 'post'));
	$data_fim = converteData(_INPUT('data_fim', 'string', 'post'));
        
//    $qtdDias = diferenca_data_dias($data_inicio, $data_fim);
	$data = $data_inicio;

	$Total = extratoFinanceiroAnterior($conta_corrente_id, $data_inicio);                
	$Fatura = extratoFinanceiroTudo($conta_corrente_id, $data_inicio, $data_fim);        

//    $x = 0;
//    for ($i = 0; $i < $qtdDias; $i++) {
//
//        if (empty($extratoFatura) && empty($extratoDuplicata) && empty($extratoLancamento_E) && empty($extratoLancamento_S)) {
//            
//        } else {
//            $extrato['parcelas'][$x]['data'] = converteData($data);
//            $extrato['parcelas'][$x]['fatura'] = $extratoFatura;
//            $extrato['parcelas'][$x]['duplicata'] = $extratoDuplicata;
//            $extrato['parcelas'][$x]['lanc_e'] = $extratoLancamento_E;
//            $extrato['parcelas'][$x]['lanc_s'] = $extratoLancamento_S;
//            $x++;
//        }
//
//		print_r($extrato);    
//        $data = converteData($data);
//        $data = somarData($data, 1, 'dia');
//        $data = converteData($data);
//    }
	$totalFatura['saldo'] = $Total;
	$totalFatura['fatura'] = $Fatura;
        
        unset($cabecalho);
        $cabecalho['data_inicio'] = $data_inicio;
        $cabecalho['data_fim'] = $data_fim;
        $cabecalho['ccid'] = $conta_corrente_id;
        
        $_SESSION['relatoriofatura'] = $totalFatura;
        $_SESSION['relatoriofatura']['cabecalho'] = $cabecalho;
        
	echo saidaJson($totalFatura);
} else if ($tipoForm == "imprime_extrato") {

	$_SESSION['extrato'] = _INPUT('strhtml', 'string', 'post');
} else {
	include('view/financeiro/extrato.phtml');
}
?>
