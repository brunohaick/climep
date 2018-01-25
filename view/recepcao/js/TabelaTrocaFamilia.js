$(document).ready(function() {
trasferencia = new trasferencia();

});
trasferencia = function() {
	this.executarAlteracao = function() {
		var idDependente = parseInt($('#transf_select_familia_origem').val());
		var membroDependente = parseInt($('#transf_select_familia_origem').attr('membro'));
		var idTitular = parseInt($('#MatriculaTitular').val());
		var checkedTitularPropriaFamilia = $('#TitularPropriaFamilia').is(':checked');
		var checkedTitularNovaFamilia = $('#TitularNovaFamilia').is(':checked');
		var checkedTitularFamiliaExistente = $('#TitularFamiliaExistente').is(':checked');
		var checkedDependenteFamiliaExistente = $('#DependenteFamiliaExistente').is(':checked');

		if (checkedTitularPropriaFamilia) {
			$.ajax({
				url: 'index.php?module=modulos&look=TitularPropriaFamilia&tmp=0',
				type: 'POST',
				async: false,
				data:	{idDependente: idDependente,
						 membroDependente: membroDependente,
						 idTitular: idTitular},
				success: function(valor_vista) {
					alert("Alteração Realizada Com Sucesso");
					window.open('#');
				}});
		} else if (checkedTitularNovaFamilia) {
			$.ajax({
				url: 'index.php?module=modulos&look=TitularNovaFamilia&tmp=0',
				type: 'POST',
				async: false,
				data: {idDependente: idDependente,
					idTitular: idTitular},
				success: function(valor_vista) {
					alert("Alteração Realizada Com Sucesso");
					window.open('#');
				}});
		} else if (checkedTitularFamiliaExistente) {
			$.ajax({
				url: 'index.php?module=modulos&look=TitularFamiliaExistente&tmp=0',
				type: 'POST',
				async: false,
				data: {idDependente: idDependente,
					idTitular: idTitular},
				success: function(valor_vista) {
					alert("Alteração Realizada Com Sucesso");
					window.open('#');
				}});
		} else if (checkedDependenteFamiliaExistente) {
			$.ajax({
				url: 'index.php?module=modulos&look=DependenteFamiliaExistente&tmp=0',
				type: 'POST',
				async: false,
				data: {idDependente: idDependente,
					idTitular: idTitular},
				success: function(valor_vista) {
					alert("Alteração Realizada Com Sucesso");
					window.open('#');
				}});
		} else {
			alert("Selecione uma das operações listadas");
		}
	};
}