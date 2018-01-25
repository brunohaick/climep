function consulta(consultaID) {

    this.consultaID = consultaID;
    this.TextoDaConsulta = '';
    this.tamanho = 0;
    this.peso = 0;
    this.PA = 0;
    this.Esp = '';
    this.data = new Date();
    this.medico_id = 0;
    this.NomeDoMedico = '';

    this.cliente;

    var $tr = undefined;

    this.consulta = 1;

    var listerns = new Array();
    var THIS = this;

    this.addPropertiChangeListener = function(CallBack) {
        if (!listerns) {
            listerns = new Array();
        }
        if (typeof CallBack === 'function') {
            listerns.push(CallBack);
        }
        return this;
    };

    var executeListener = function(nome, valor) {
        for (var func in listerns) {
            func = listerns[func];
            func(nome, valor, THIS);
        }
    };

    this.atualizaConsultaServidor = function() {
        var novo;
        var consult = this;
        if (this.consultaID && this.consultaID !== 0) {
            novo = false;
        } else {
            novo = true;
        }
        var consulta_id = (!this.consultaID ? 0 : this.consultaID);        
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'atualizaConsulta',
                consulta_id: consulta_id,
                texto: this.TextoDaConsulta,
                Peso: this.peso,
                Tamanho: this.tamanho,
                PA: this.PA,
                data: this.getData().format('isoDate'),
                clienteID: this.cliente.clienteId,
                categoria_consultas_id: 6,
                novo: novo
            },
            success: function(resposta) {
                if (novo) {
                    resposta = $.parseJSON(resposta);
                    consult.consultaID = resposta;
                }
            }
        });
        this.getAsTableRow();
        return this;
    };

    this.getAsTableRow = function(force) {
        if (!$tr || force) {
            $tr = $('<tr/>');
        } else {
            $tr.html('');
        }
        $tr.append($('<th/>').html(this.Esp));
        $tr.append($('<th/>').html(this.getData().format('BR')));
        $tr.append($('<th/>').html(this.tamanho));
        $tr.append($('<th/>').html(this.peso));
        $tr.append($('<th/>').html(this.PA));
        $tr.data('cliente', this.cliente);
        $tr.data('consulta', this);
        $tr.attr('id', this.consultaID);
        return $tr;
    };

    this.getConsultaId = function() {
        return this.consultaID;
    }
    this.getData = function() {
        if (this.data) {
            return this.data;
        } else {
            this.data = new Date();
            return this.getData();
        }
    };

    this.setMedico = function(Medico) {
        this.NomeDoMedico = Medico;
        return this;
    };

    this.getMedicoId = function() {
        return this.medico_id;
    };

    this.setMedicoId = function(Medicoid) {
        this.medico_id = Medicoid;
        return this;
    };

    this.getMedico = function() {
        return this.NomeDoMedico;
    };
    this.setCliente = function(client) {
        if (!client || client.cliente !== 1) {
            throw "setCliente deve receber um objeto da instancia de cliente";
        }
        this.cliente = client;
        return this;
    };

    this.setConsultaID = function(id) {
        this.consultaID = id;
        executeListener('consultaID', id);
        return this;
    };

    this.setConsultaTexto = function(texto) {
        this.TextoDaConsulta = texto;
        executeListener('ConsultaTexto', texto);
        return this;
    };

    this.setPeso = function(peso) {
        this.peso = peso;
        executeListener('peso', peso);
        return this;
    };

    this.setEsp = function(Esp) {
        this.Esp = Esp;
        executeListener('Esp', Esp);
        return this;
    };

    this.setPA = function(PA) {
        this.PA = PA;
        executeListener('PA', PA);
        return this;
    };

    this.setTamanho = function(tamanho) {
        this.tamanho = tamanho;
        executeListener('tamanho', tamanho);
        return this;
    };

    this.setData = function(data) {
        if (typeof data === 'string') {
            var data = data.split('-');
            this.data = new Date(data[0], parseInt(data[1]) - 1, data[2]);
        } else {
            this.data = data;
        }
        executeListener('data', data);
        return this;
    };

}