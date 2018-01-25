function cliente(dados) {
    this.cliente = 1;

    this.nome;
    this.data_nascimento;
    this.medico;
    this.matricula;
    this.clienteId;
    this.antecedentesPessoais;
    this.antecedentesFamiliares;
    this.alergias;
    this.idadeGestacional;
    this.pesoNasc;
    this.apgar;
    this.partoId;
    this.gestacaoId;

    this.consultas = new Array();

    var listeners = undefined;

    this.addPropertiChangeListener = function(CallBack) {
        if (!listeners) {
            listeners = new Array();
        }
        if (typeof CallBack === 'function') {
            listeners.push(CallBack);
        }
        return this;
    };

    var executeListener = function(prop, value, client) {
        for (var func in listeners) {
            func = listeners[func];
            func(prop, value, client);
        }
    };

    this.atualizaClienteServidor = function() {
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'atualizaCliente',
                cliente_id: this.clienteId,
                ant_pessoa: this.antecedentesPessoais,
                ant_fimiliares: this.antecedentesFamiliares,
                alergia: this.alergias,
                apgar: this.apgar,
                idade_gestacional: this.idadeGestacional,
                pesoNasc: this.pesoNasc,
                partoId: this.partoId,
                gestacaoId: this.gestacaoId
            },
            success: function(resposta) {
                console.log(resposta);
            }
        });
        return this;
    };

    this.getAsTable = function(force) {
        var $consultasTrs = new Array();
        for (var cont in this.consultas) {
            cont = this.consultas[cont];
            var $tr = cont.getAsTableRow(force);
            $tr.dblclick(function() {
                consultasTela.setConsultaSelected($(this).data('consulta'));
            });
            $tr.attr('class', 'pointer-cursor');
            $tr.attr('id', cont['consultaID']);
            $consultasTrs.push($tr);
        }
        return $consultasTrs;
    };

    this.addConsulta = function(Consulta, primeiro) {
        if (!Consulta.consulta) {
            throw "Variavel passada errado, para adicionar uma consulta deve ser uma instancia de consulta";
        }
        Consulta.setCliente(this);
        if (primeiro)
            this.consultas.unshift(Consulta);
        else
            this.consultas.push(Consulta);
    };

    this.getClienteId = function() {
        return this.clienteId;
    }

    this.getIdadeString = function() {
        var dias = this.diasDecorridos(this.data_nascimento, new Date());
        var anos = dias / 365;
        var messes = (anos - parseInt(anos)) * 12;
        anos = parseInt(anos);
        messes = parseInt(messes);
        return anos + "a " + messes + "m";
    };

    this.diasDecorridos = function(dt1, dt2) {
        // variáveis auxiliares
        var minuto = 60000;
        var dia = minuto * 60 * 24;
        var horarioVerao = 0;

        // ajusta o horario de cada objeto Date
        dt1.setHours(0);
        dt1.setMinutes(0);
        dt1.setSeconds(0);
        dt2.setHours(0);
        dt2.setMinutes(0);
        dt2.setSeconds(0);

        // determina o fuso horário de cada objeto Date
        var fh1 = dt1.getTimezoneOffset();
        var fh2 = dt2.getTimezoneOffset();

        // retira a diferença do horário de verão
        if (dt2 > dt1) {
            horarioVerao = (fh2 - fh1) * minuto;
        }
        else {
            horarioVerao = (fh1 - fh2) * minuto;
        }

        var dif = Math.abs(dt2.getTime() - dt1.getTime()) - horarioVerao;
        return Math.ceil(dif / dia);
    };

    this.setIdadeGestacional = function(IdadeGestacional) {
        this.idadeGestacional = IdadeGestacional;
        executeListener('idadeGestacional', IdadeGestacional, this);
        return this;
    };

    this.setPesoNasc = function(PesoNasc) {
        this.pesoNasc = PesoNasc;
        executeListener('pesoNasc', PesoNasc, this);
        return this;
    };

    this.setApgar = function(Apgar) {
        this.apgar = Apgar;
        executeListener('apgar', Apgar, this);
        return this;
    };

    this.setParto = function(PartoId) {
        this.partoId = PartoId;
        executeListener('partoId', PartoId, this);
        return this;
    };

    this.setGestacao = function(GestacaoId) {
        this.gestacaoId = GestacaoId;
        executeListener('gestacaoId', GestacaoId, this);
        return this;
    };

    this.setClienteId = function(ClienteId) {
        this.clienteId = ClienteId;
        executeListener('clienteId', ClienteId, this);
        return this;
    };

    this.setMatricula = function(Matricula) {
        this.matricula = Matricula;
        executeListener('matricula', Matricula, this);
        return this;
    };

    this.setAntecedentes = function(Texto) {
        this.antecedentesPessoais = Texto;
        executeListener('antecedentesPessoais', Texto, this);
        return this;
    };

    this.setAntecedentesFamiliares = function(Texto) {
        this.antecedentesFamiliares = Texto;
        executeListener('antecedentesFamiliares', Texto, this);
        return this;
    };

    this.setAlergias = function(Texto) {
        this.alergias = Texto;
        executeListener('alergias', Texto, this);
        return this;
    };

    this.setMedico = function(nome) {
        this.medico = nome;
        executeListener('medico', nome);
        return this;
    };

    this.setDataNacimento = function(data) {
        if (typeof data === 'string') {
            var data = data.split('-');
            this.data_nascimento = new Date(data[0], parseInt(data[1]) - 1, data[2]);
        } else {
            this.data_nascimento = data;
        }
        executeListener('dataNacimento', data, this);
        return this;
    };

    this.setNome = function(nome) {
        if (typeof nome === 'string') {
            this.nome = nome;
        }
        executeListener('nome', this.nome, this);
        return this;
    };

    if (dados) {
        this.setClienteId(dados.clienteId)
                .setNome(dados.cliente)
                .setDataNacimento(dados.data_nascimento)
                .setMedico(dados.medico)
                .setAlergias(dados.alergias)
                .setAntecedentesFamiliares(dados.ant_familiar)
                .setAntecedentes(dados.ant_pessoal)
                .setMatricula(dados.matricula)
                .setIdadeGestacional(((dados.idade_gestacional) ? dados.idade_gestacional : ''))
                .setPesoNasc(((dados.peso_nascimento !== "null") ? dados.peso_nascimento : ''))
                .setApgar(((dados.apgar) ? dados.apgar : ''))
                .setParto(((dados.parto_id) ? dados.parto_id : ''))
                .setGestacao(((dados.gestacao_id) ? dados.gestacao_id : ''));
    }  

}

var salvarAntecedentesFamiliares = function(text) {
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'atualizarAntecedenteFamiliar',
                id: consultasTela.SelectedClient.getClienteId(),
                data: text
            },
            success: function(resposta) {
                $('div#areaDeTextoParaAtecedentesFamiliares').html(text);        
            }});
    }
var salvarAntecedentesPessoal = function(text) {
        $.ajax({
            url: 'index.php?module=consultas&tmp=1',
            type: 'POST',
            data: {
                flag: 'atualizarAntecedentePessoal',
                id: consultasTela.SelectedClient.getClienteId(),
                data: text
            },
            success: function(resposta) {
                $('div#areaDeTextoParaAntecedentesPessoais').html(text);
            }});
    }