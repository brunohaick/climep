function controleModel(array) {

    this.clienteTitular = "";
    this.id = "";
    this.controle = "";
    this.categoria = "";
    this.isconvenio = 0;
    this.data = "";
    this.servicos = new Array();
    this.modulo = 0
    this.usuario = "";
    this.valortotal = 0;
    this.pagamento = [];
    var THIS;
    /***
     * @author Luiz Cortinhas
     * @description Metodo construtor da model de controle, ele preenche automaticamente todo controle e os servicos
     * @param {type} array
     * @returns {undefined} not return
     */
    this.Construtor = function Construtor(array) {
        this.id = array['id_guia'];
        this.controle = array['numero_controle'];
        this.clienteTitular = new clienteModel(array);
		this.categoria = array['categoria_id'];
        this.data = array['data'];
        if(array['categoria'] != 'Cortesia' && array['categoria'] != 'Particular'){
            this.isconvenio = 1;
        }
        this.modulo = array['modulo'];
        this.usuario = array['usuario_nome'];
        THIS = this;
        $.post(
                'index.php?module=caixa&tmp=1',
                {flag: 'getServicosPorGuiaControle', controle_id: this.id},
        function(result) {
            for (key in result) {
                THIS.servicos[key] = new servicoModel(result[key]);
            }
        }, "json");
    };
    
    this.getPagamento = function(){
        return this.pagamento;
    }

    this.addModuleasServico = function(servicos, modulos) {
        THIS.servicos = new Array();
        for (key in servicos) {
            servicos[key]['data_nascimento'] = servicos[key]['cliente_data'];
            servicos[key]['membro'] = servicos[key]['cliente_membro'];
            servicos[key]['material_nome'] = servicos[key]['materialNome'];
            servicos[key]['precocartao'] = servicos[key]['materialPrecoCartao'];
            servicos[key]['preco'] = servicos[key]['materialPrecoVista'];
            servicos[key]['tipo_material_id'] = servicos[key]['materialTipo'];
            servicos[key]['codigo'] = servicos[key]['materialid'];
            if (servicos[key]['modulo'] == "1") {
                servicos[key]['modulo'] = null;
            } else {
                THIS.servicos.push(new servicoModel(servicos[key]));
            }
        }
        for (key in modulos) {
            modulos[key]['cliente_nome'] = modulos[key]['membro_nome'];
            modulos[key]['mod.descontoBCG'] = modulos[key]['descontoBCG'];
            modulos[key]['mod.descontoMedico'] = modulos[key]['descontoMedico'];
            modulos[key]['mod.descontoPromocional'] = modulos[key]['descontoPromocional'];
            modulos[key]['membro'] = modulos[key]['membro_id'];
            modulos[key]['data_nascimento'] = modulos[key]['membro_nascimento'];
            modulos[key]['preco'] = modulos[key]['valor_vista'];
            modulos[key]['servico_preco'] = modulos[key]['valor_vista'];
            modulos[key]['preco_cartao'] = modulos[key]['valor_cartao'];
            modulos[key]['tipo_material_id'] = modulos[key]['tipo'];
            modulos[key]['tipo_servico'] = '1';
            modulos[key]['codigo'] = modulos[key]['material_id'];
            modulos[key]['modulo'] = "1";
            modulos[key]['material_nome'] = modulos[key]['nome_material'];
            modulos[key]['status'] = "A REALIZAR (HOJE)";
            THIS.servicos.push(new servicoModel(modulos[key]));
        }
    };

    this.addVisitasServico = function(array) {                
        THIS.servicos.push(new servicoModel(array));
    };

    this.getServicos = function() {
        return this.servicos;
    };


    //N�o modificar a linha de demarcaç�o do construtor
    controleModel.constructor = this.Construtor(array);
}
