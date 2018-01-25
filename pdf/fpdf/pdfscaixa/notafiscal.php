<?php

require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

    function AfastaCell($espacamento) {
        $this->Cell($espacamento, 0, '', '');
    }
    
    function notafiscal() {
        
        $rps = $_SESSION['notafiscal']['rps'];
//        die(print_r($rps));
        $this->SetY("-1");
        $this->AddPage();
        $this->Image("pdf/fpdf/pdfscaixa/nf_parte1.png", 0, 0, 210, 93);
        
        $this->Ln(7);        
        $this->AfastaCell(165);
        $this->Cell(35, 5, $rps['numero_nfse'], 0, 0, 'C'); //Numero da Nota
        $this->Ln(9);        
        $this->AfastaCell(165);
        $this->Cell(25, 5, $rps['dataEmissaoRPS'] . ' 00:00:00', 0, 0, 'C'); //Data e hora de Emissao
        $this->Ln(9);        
        $this->AfastaCell(165);
        $this->Cell(35, 5, '', 0, 0, 'C'); //Codigo de Verificacao
        $this->Ln(18.5);        
        $this->AfastaCell(47);
        $this->Cell(150, 5, utf8_decode('Avenida Braz de Aguiar, 123'), 0, 0, 'L'); //Endereco Prestador de Servico
        $this->Ln(12);        
        $this->AfastaCell(31);
        $this->Cell(160, 5, utf8_decode($rps['RazaoSocialTomador']), 0, 0, 'L'); //Tomador Nome
        $this->Ln(3.5);        
        $this->AfastaCell(21);
        $this->Cell(160, 5, $rps['CPFCNPJTomador'], 0, 0, 'L'); //Tomador CPF
        $this->Ln(3.8);        
        $this->AfastaCell(19);
        $this->Cell(150, 5, utf8_decode($rps['LogradouroTomador'] . ', ' . $rps['NumeroEnderecoTomador']), 0, 0, 'L'); //Endereco Tomador de Servico
        $this->Ln(3.8);        
        $this->AfastaCell(19);
        $this->Cell(17, 5, utf8_decode($rps['CidadeTomadorDescricao']), 0, 0, 'L'); //Municipio Tomador
        $this->AfastaCell(7);
        $this->Cell(10, 5, utf8_decode($rps['EstadoTomador']), 0, 0, 'L'); //UF Tomador
        $this->AfastaCell(26);
        $this->Cell(90, 5, utf8_decode($rps['EmailTomador']), 0, 0, 'L'); //email Tomador
        $this->Ln(9);        
        $this->AfastaCell(20);
        $this->Multicell(0,5,utf8_decode("Descrição: SERVIÇOS MEDICOS PRESTADOS DE VACINAÇÃO"));
        
        $this->SetY(88);
        $this->Ln();

        $this->SetFont('Arial', 'B', 7);
        $this->AfastaCell(6);
        $this->Cell(138, 5, 'Item', 0, 0, 'L');
        $this->Cell(20, 5, 'Qtde', 0, 0, 'L');
        $this->Cell(28, 5, utf8_decode('Unitário R$'), 0, 0, 'L');
        $this->Cell(30, 5, 'Total R$', 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Ln();

        $rectSize = 40;                
        $total = 0;
        
        $servicos = pegaServicosPorRPS($rps['id']);        
        foreach($servicos as $s){
            $this->AfastaCell(6);
            $this->Cell(130, 5, $s['material_nome'], 0, 0, 'L');
            $this->AfastaCell(7);
            $this->Cell(8, 5, 1, 0, 0, 'R');
            $this->AfastaCell(6);
            $this->Cell(22, 5, number_format($s['preco_por_dose'], 2,',','.'), 0, 0, 'R');
            $this->AfastaCell(3);
            $this->Cell(22, 5, number_format(1*$s['preco_por_dose'], 2,',','.'), 0, 0, 'R');
            $this->Ln(4);  
            $total += 1*$s['preco_por_dose'];
        }
        
        $this->Rect(4, 93, 136, $rectSize*4+8);
        $this->Rect(140, 93, 13, $rectSize*4+8);
        $this->Rect(153, 93, 27, $rectSize*4+8);
        $this->Rect(180, 93, 24.7, $rectSize*4+8);

        $this->Image("pdf/fpdf/pdfscaixa/nf_parte2.png", 0, 235, 210, 65);
        
        $this->SetFont('Arial', '', 7);
        
        $this->SetX(0);
        $this->SetY(234);
        $this->Ln(1.2);
        $this->AfastaCell(21);
        $this->Cell(10, 5, number_format($rps['AliquotaPIS'], 2,',','.').'%', 0, 0, 'C'); //Porcentagem PIS
        $this->AfastaCell(35.5);
        $this->Cell(10, 5, number_format($rps['AliquotaCOFINS'], 2,',','.').'%', 0, 0, 'C'); //Porcentagem COFINS
        $this->AfastaCell(30);
        $this->Cell(10, 5, number_format($rps['AliquotaINSS'], 2,',','.').'%', 0, 0, 'C'); //Porcentagem INSS
        $this->AfastaCell(29.5);
        $this->Cell(10, 5, number_format($rps['AliquotaIR'], 2,',','.').'%', 0, 0, 'C'); //Porcentagem IR
        $this->AfastaCell(28.5);
        $this->Cell(10, 5, number_format($rps['AliquotaCSLL'], 2,',','.').'%', 0, 0, 'C'); //Porcentagem CSLL
        
        $this->SetFont('Arial', 'B', 9);
        
        $this->Ln();
        $this->AfastaCell(10);
        $this->Cell(30, 5, 'R$ '.number_format($rps['ValorPIS'], 2,',','.'), 0, 0, 'C'); //Valor PIS
        $this->AfastaCell(10);
        $this->Cell(30, 5, 'R$ '.number_format($rps['ValorCOFINS'], 2,',','.'), 0, 0, 'C'); //Valor COFINS
        $this->AfastaCell(15);
        $this->Cell(30, 5, 'R$ '.number_format($rps['ValorINSS'], 2,',','.'), 0, 0, 'C'); //Valor INSS
        $this->AfastaCell(10);
        $this->Cell(30, 5, 'R$ '.number_format($rps['ValorIR'], 2,',','.'), 0, 0, 'C'); //Valor IR
        $this->AfastaCell(5);
        $this->Cell(30, 5, 'R$ '.number_format($rps['ValorCSLL'], 2,',','.'), 0, 0, 'C'); //Valor CSLL
        
        $this->SetFont('Arial', 'B', 11);
        
        $this->Ln();
        $this->AfastaCell(110);
        $this->Cell(40, 5, 'R$ '.number_format($total, 2,',','.'), 0, 0, 'L'); //Valor Total da Nota
        
        $this->SetFont('Arial', 'B', 9);
        
        $this->Ln(8);
        $this->AfastaCell(7);
        $this->Cell(50, 5, 'R$ ' . number_format($rps['ValorTotalDeducoes'], 2,',','.'), 0, 0, 'R'); //Valor total das deducoes
        $this->AfastaCell(10);
        $this->Cell(40, 5, 'R$ ' . number_format($rps['ValorTotalDeducoes'], 2,',','.'), 0, 0, 'R'); //Base de Calculo
        $this->AfastaCell(2);
        $this->Cell(48, 5, number_format($rps['AliquotaAtividade'], 2,',','.') . '%', 0, 0, 'R'); //Aliquota
        $this->AfastaCell(3);
        $this->Cell(40, 5, 'R$ ' . number_format($rps['valorISS'], 2,',','.'), 0, 0, 'R'); //Valor ISS
        
        $MesCompetenciaNF = explode('/', $rps['dataEmissaoRPS']);
        
        $this->Ln(13);
        $this->AfastaCell(50);
        $this->Cell(15, 5, $MesCompetenciaNF[1].'/'.$MesCompetenciaNF[2], 0, 0, 'L'); //Mes de Competencia da Nota Fiscal
        $this->AfastaCell(60);
        $this->Cell(40, 5, utf8_decode($rps['CidadeTomador'].'/'.$rps['EstadoTomador']), 0, 0, 'L'); //Local de Prestacao de Servico - FIXO
        $this->Ln(3.5);
        $this->AfastaCell(23);
        $this->Cell(30, 5, $rps['TipoRecolhimento'], 0, 0, 'L'); //Recolhimento -
        $this->AfastaCell(48);
        $this->Cell(30, 5, utf8_decode('Tributável'), 0, 0, 'L'); //Tributacao - FIXO
        $this->Ln(3);
        $this->AfastaCell(13);
        $this->Cell(60, 5, $rps['numero_nfse'].' / NF ('.$rps['dataEmissaoRPS'].')', 0, 0, 'L'); //RPS
        $this->Ln(3.8);
        $this->AfastaCell(15);
        $this->Cell(60, 5, '863050600', 0, 0, 'L'); //CNAE - FIXO
        $this->AfastaCell(42);
        $this->Cell(60, 5, utf8_decode("Serviços de vacinação e imunização humana."), 0, 0, 'L'); //Descricao - FIXO
    }
}

$pdf = new PDF();
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetMargins(0, 0, 10);
$pdf->SetAutoPageBreak(true, 0);
$pdf->SetLineWidth(0.48);
$pdf->notafiscal();
$pdf->Output("notafiscal.pdf", "I");
