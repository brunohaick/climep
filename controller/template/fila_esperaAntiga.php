<?php

if(isset($_POST['add_fila'])) {
	if($_POST['add_fila'] > 0) {

		$idCliente = $_POST['cliente_id'];
		if(!usuarioExisteNaFilaEsperaVacina($idCliente)) {
			$dados['cliente_id'] = $_POST['cliente_id'];
			$dados['hora_recepcao'] = date("H:i:s");
			$dados['data'] = date("Y-m-d");
			$dados['usuario_id_recepcao'] = $_SESSION['usuario']['id'];
			inserir('fila_espera_vacina',$dados);

			$matricula = $_GET['matricula'];
			die(saidaJson($matricula));
		} else { // usuario já existe na fila de vacina sem ter sido atendido
			echo saidaJson(999999999999);
		}
		exit;
	}
}

//die(print_r($_POST[]));
//if(isset($_POST['confirma_atendimento'])){
	if($_POST['confirma_atendimento']) {
		$dados['id'] = $_POST['filaId'];
		confirmaHorariodeAtendimento($dados['id'], $_SESSION['usuario']['id']);
		die();
        }
//   }

//$esperaVacina = listaFilaDeEspera();

if(isset($_POST['at_fila']))
	if($_POST['at_fila'] == 1) {
		die(saidaJson($esperaVacina));
	} 

include('view/template/fila_espera.phtml');
