<?php

if (isset($_POST['add_fila']) && $_POST['add_fila'] > 0) {
	if (!usuarioExisteNaFilaEsperaVacina($_POST['cliente_id'])) {
		$dados['num_ord'] = buscaNovoNumeroOrdemFilaEsperaVacina();
		$dados['cliente_id'] = $_POST['cliente_id'];
		$dados['hora_recepcao'] = date("H:i:s");
		$dados['data'] = date("Y-m-d");
		$dados['usuario_id_recepcao'] = $_SESSION['usuario']['id'];
		inserir('fila_espera_vacina', $dados);

		$matricula = $_GET['matricula'];
		die(saidaJson($matricula));
	} else {
		echo saidaJson(999999999999);
	}
} else if ($_POST['confirma_atendimento'] == true) {
	$dados['id'] = $_POST['filaId'];
	confirmaHorariodeAtendimento($dados['id'], $_SESSION['usuario']['id']);
	die('atendido');
} else if (isset($_GET['at_fila']) && $_GET['at_fila'] == 1) {

	/*
	 * Easy set variables
	 * 
	 * Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('num_ord','nome', 'hora_recepcao', 'hora_atendimento', 'tempo_espera','recepcao_login');

	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";

	/* DB table to use */
	$sTable = "(SELECT * FROM (
				SELECT
					d.*,
					a.*, 
					u.login AS 'recepcao_login',
					g.login AS 'atendente_login',
					TIMEDIFF(a.hora_atendimento, a.hora_recepcao) AS 'tempo_espera'
				FROM
					(
					SELECT
						s.*, u.login
					FROM
						(
						SELECT
							f.*, p.nome
						FROM
							fila_espera_vacina AS f
							INNER JOIN cliente AS c ON c.cliente_id = f.cliente_id
							INNER JOIN pessoa p
						WHERE
							f.cliente_id = c.cliente_id
							AND
							c.cliente_id = p.id
							AND
							f.data = CURDATE()
						) AS s
					LEFT JOIN usuario u ON u.usuario_id = s.usuario_id_recepcao
					) a
					LEFT JOIN usuario u ON a.usuario_id_recepcao = u.usuario_id
					LEFT JOIN usuario g ON a.usuario_id_atendente = g.usuario_id
					LEFT JOIN dependente d ON d.dependente_id = a.cliente_id
				) as a ORDER BY a.num_ord DESC) as b
	";

	/*
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */

	/*
	 * Paging
	 */
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
		$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
	}

	/*
	 * Ordering
	 * 
	 * 
	 * BRUNO HAICK (Mudanca...)
	 * 
	 * Fiz a Mudança no trecho:
	 * ($_GET['sSortDir_' . $i] === 'asc' ? 'desc' : 'asc') . ", ";
	 * 
	 * Que antes era assim :
	 * ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
	 * 
	 * foi invertido a ordem de asc e desc, para que no inicio, a tabela seja ordenada em forma DESC 
	 * de numero de Ordem.
	 * 
	 */
	$sOrder = "";
	if (isset($_GET['iSortCol_0'])) {
		$sOrder = "ORDER BY  ";
		for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
			if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
				$sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " . ($_GET['sSortDir_' . $i] === 'asc' ? 'desc' : 'asc') . ", ";
			}
		}

		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") {
			$sOrder = "";
		}
	}

	/*
	 * Filtering
	 * 
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
		$sWhere = "WHERE (";
		for ($i = 0; $i < count($aColumns); $i++) {
			$sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}

	/* Individual column filtering */
	for ($i = 0; $i < count($aColumns); $i++) {
		if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
			if ($sWhere == "") {
				$sWhere = "WHERE ";
			} else {
				$sWhere .= " AND ";
			}
			$sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
		}
	}

	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
				SELECT *
				FROM
					$sTable
				$sWhere
				$sOrder
				$sLimit
			";

	$rResult = mysqli_query(banco::$connection,$sQuery) or die(mysqli_error(banco::$connection));

	/* Data set length after filtering */
	$sQuery = "SELECT FOUND_ROWS()";

	$rResultFilterTotal = mysqli_query(banco::$connection, $sQuery);
	$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];

	/* Total data set length */
	$sQuery = "
				SELECT
					COUNT(*)
				FROM
					$sTable
			";
	$rResultTotal = mysqli_query(banco::$connection, $sQuery);
	$aResultTotal = mysqli_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];

	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	while ($aRow = mysqli_fetch_array($rResult)) {
		$row = array();
		for ($i = 0; $i < count($aColumns); $i++) {
			if ($aColumns[$i] == "version") {
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
			} else if ($aColumns[$i] != ' ') {
				/* General output */
				$row[] = $aRow[$aColumns[$i]];
				$row['DT_RowId']= $aRow['cliente_id'];
				if(isset($aRow['hora_atendimento']) && $aRow['hora_atendimento'] != ''){
//						$row['DT_RowClass'] = $row['DT_RowClass'] .' UsuarioQueAtendeu';
					$row['rel']= 'popover';
					$row['title']= 'Atendido por:'.$aRow['atendente_login'];
					$row['data-content']= 'popover';
				}
				if(isset($aRow['vacina_casa']) && $aRow['vacina_casa'] != ''){
					$row['DT_RowClass'] = 'red';
				}
			}
		}
		$output['aaData'][] = $row;
	}

	die(saidaJson($output));

} else {
	include('view/template/fila_espera.phtml');
}