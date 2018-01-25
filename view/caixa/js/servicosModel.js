function servicoModel(array) {
    this.id;
    this.materialid;
    this.materialNome;
    this.materialPrecoVista =0;
    this.materialPrecoVistaOriginal;
    this.materialPrecoCartao =0;
    this.materialPrecoCartaoOriginal;
    this.materialTipo;
    this.data;
    this.hora;
    this.status;
    this.modulo;
    this.BCGmarcado = false;
    this.Medicomarcado = false;
    this.HPVmarcado = false;
    this.cliente_nome;
    this.cliente_membro;
    this.cliente_data;
    this.cliente_id;
    this.parcelas;
    this.forma_pagamento;
    this.japago = 0;
    this.isfrasco = 0;
    //Modificaç�o, ao salvar,verificar se � um frasco
    //Se for um frasco o caixa deve verificar o status_id =15
    //Se for ele dever� alterar o nome do servico para dose e ao efetuar o pagamento marcar como pago somento o primeiro id do historico do servico
    this.Construtor = function Construtor(array) {
        console.log("array::");
        console.log(array);
        
        this.id = array['servico_id'];
        this.materialid = array['material_id'];
        this.materialTipo = array['tipo_material_id'];
        this.isfrasco = array['tipo_servico'];
        if(array['tipo_servico'] == '0' ){
          this.materialNome = array['material_nome'] + "- (Dose)";
          this.materialPrecoVista = array['preco_por_dose'];
          this.materialPrecoCartao = array['preco_por_dose'];
        }else {
            this.materialNome = array['material_nome'];
            if (array['servico_preco'] > 0) {
                this.materialPrecoVista = array['servico_preco'];
				//console.log("Set preco a vista = servico_preco");
				//console.log("Set preco a vista = servico_preco");
            } else {
                if (array['preco_por_dose'] !== null && array['preco_por_dose'] !== "null") {
                    this.materialPrecoVista = array['preco_por_dose'];
                } else {
                    this.materialPrecoVista = 0;
                }
            }
            if (array['preco_cartao'] > 0 && array['preco_cartao'] !== null && array['preco_cartao'] !== "null") {
                this.materialPrecoCartao = array['preco_cartao'];
            } else {
                this.materialPrecoCartao = 0;
            }
        }
		this.materialPrecoVistaOriginal = this.materialPrecoVista;
		this.materialPrecoCartaoOriginal = this.materialPrecoCartao;
        this.data = array['data'];
        this.hora = array['hora'];
        this.status = array['status'];
        if(array['status_id'] == 15){
            this.japago =1;
        }
        this.modulo = array['modulo'];
        if (this.modulo > 0) {
            if (array['mod.descontoBCG'] != null && array['mod.descontoBCG'] != "null" && array['mod.descontoBCG'] == '1') {
                this.BCGmarcado = true;
            }
            if (array['mod.descontoMedico'] != null && array['mod.descontoMedico'] != "null" && array['mod.descontoMedico'] == '1') {
                this.Medicomarcado = true;
            }
            if (array['mod.descontoPromocional'] != null && array['mod.descontoPromocional'] != "null" && array['mod.descontoPromocional'] == '1') {
                this.HPVmarcado = true;
            }
            this.parcelas = array['parcelas_modulo'];
            $("#span_parcela_modulo").html("parcela em " + this.parcelas + " vezes");
            this.filtroModulo();
        }
        this.cliente_nome = array['cliente_nome'];
        this.cliente_membro = array['membro'];
//        console.log(array);
        this.cliente_data = array['data_nascimento'];
        this.cliente_id = array['cliente_id'];
    };

    this.filtroModulo = function() {
        if (this.BCGmarcado && (this.materialNome === "BCG id" || this.materialNome === "BCG pc")) {
            if (this.Medicomarcado) {
                this.materialPrecoVista = 89 * 0.80;
                this.materialPrecoCartao = 89 * 0.85;
            } else {
                this.materialPrecoVista = 81;
                this.materialPrecoCartao = 89 * 0.94;
            }
        } else {
            if (this.HPVmarcado && this.materialNome === "HPV MSD") {
                if (this.Medicomarcado) {
                    this.materialPrecoVista = 380 * 0.80;
                    this.materialPrecoCartao = 380 * 0.85;
                } else {
                    this.materialPrecoVista = 350;
                    this.materialPrecoCartao = 380;
                }
            } else {
                if (this.Medicomarcado) {
                    this.materialPrecoVista = parseFloat(this.materialPrecoVista * 0.80);
                    this.materialPrecoCartao = parseFloat(this.materialPrecoCartao * 0.85);
                } else {
                    this.materialPrecoVista = parseFloat(this.materialPrecoVista);
                    this.materialPrecoCartao = parseFloat(this.materialPrecoCartao);
                }
            }
        }

    };
//N�o modificar a linha abaixo, pois a mesma configura o construtor para o model
    servicoModel.constructor = this.Construtor(array);
}
