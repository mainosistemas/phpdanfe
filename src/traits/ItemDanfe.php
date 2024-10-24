<?php
namespace App\traits;

trait ItemDanfe{

    protected function itens($x, $y, &$nInicio, $hmax, $pag = 0, $totpag = 0, $hCabecItens = 7)
    {
        $oldX = $x;
        $oldY = $y;
        $totItens = $this->det->length;
        //#####################################################################
        //DADOS DOS PRODUTOS / SERVIÇOS
        $texto = "DADOS DOS PRODUTOS / SERVIÇOS";
        if ($this->orientacao === 'P') {
            $w = $this->wPrint;
        } else {
            if ($nInicio < 2) { // primeira página
                $w = $this->wPrint - $this->wCanhoto;
            } else { // páginas seguintes
                $w = $this->wPrint;
            }
        }
        $h     = 4;
        $aFont = ['font' => $this->fontePadrao, 'size' => 7, 'style' => 'B'];
        $this->pdf->textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        //desenha a caixa dos dados dos itens da NF
        $hmax  += 1;
        $texto = '';
        $this->pdf->textBox($x, $y, $w, $hmax);
        //##################################################################################
        // cabecalho LOOP COM OS DADOS DOS PRODUTOS
        //CÓDIGO PRODUTO
        $texto = "CÓDIGO PRODUTO";
        $w1    = round($w * 0.09, 0);
        $h     = 4;
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w1, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w1, $y, $x + $w1, $y + $hmax);

        $this->pdf->dashedVLine($x + $w1, $y, 0.1, $y + $hmax, 100);
        //DESCRIÇÃO DO PRODUTO / SERVIÇO
        $x     += $w1;
        $w2    = round($w * 0.25, 0);
        $texto = 'DESCRIÇÃO DO PRODUTO / SERVIÇO';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w2, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w2, $y, $x + $w2, $y + $hmax);
        $this->pdf->dashedVLine($x + $w2, $y, 0.1, $y + $hmax, 100);
        //NCM/SH
        $x     += $w2;
        $w3    = round($w * 0.06, 0);
        $texto = 'NCM/SH';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w3, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w3, $y, $x + $w3, $y + $hmax);
        $this->pdf->dashedVLine($x + $w3, $y, 0.1, $y + $hmax, 100);
        //O/CST ou O/CSOSN
        $x     += $w3;
        $w4    = round($w * 0.05, 0);
        $crt = $this->getTagValue($this->emit, 'CRT');
        // 1=Simples Nacional; 2=Simples Nacional, excesso sublimite de receita bruta;
        // 3=Regime Normal; 4=Simples Nacional - Microempreendedor Individual - MEI;
        $texto = in_array($crt, ['1', '4']) ? 'O/CSOSN' : 'O/CST';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w4, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w4, $y, $x + $w4, $y + $hmax);
        $this->pdf->dashedVLine($x + $w4, $y, 0.1, $y + $hmax, 100);
        //CFOP
        $x     += $w4;
        $w5    = round($w * 0.04, 0);
        $texto = 'CFOP';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w5, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w5, $y, $x + $w5, $y + $hmax);
        $this->pdf->dashedVLine($x + $w5, $y, 0.1, $y + $hmax, 100);
        //UN
        $x     += $w5;
        $w6    = round($w * 0.03, 0);
        $texto = 'UN';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w6, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w6, $y, $x + $w6, $y + $hmax);
        $this->pdf->dashedVLine($x + $w6, $y, 0.1, $y + $hmax, 100);
        //QUANT
        $x     += $w6;
        $w7    = round($w * 0.08, 0);
        $texto = 'QUANT';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w7, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w7, $y, $x + $w7, $y + $hmax);
        $this->pdf->dashedVLine($x + $w7, $y, 0.1, $y + $hmax, 100);
        //VALOR UNIT
        $x     += $w7;
        $w8    = round($w * 0.06, 0);
        $texto = 'VALOR UNIT';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w8, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w8, $y, $x + $w8, $y + $hmax);
        $this->pdf->dashedVLine($x + $w8, $y, 0.1, $y + $hmax, 100);
        //VALOR TOTAL
        $x     += $w8;
        $w9    = round($w * 0.06, 0);
        $texto = 'VALOR TOTAL';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w9, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w9, $y, $x + $w9, $y + $hmax);
        $this->pdf->dashedVLine($x + $w9, $y, 0.1, $y + $hmax, 100);
        //VALOR DESCONTO
        $x     += $w9;
        $w10   = round($w * 0.05, 0);
        $texto = 'VALOR DESC';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w10, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w10, $y, $x + $w10, $y + $hmax);
        $this->pdf->dashedVLine($x + $w10, $y, 0.1, $y + $hmax, 100);
        //B.CÁLC ICMS
        $x     += $w10;
        $w11   = round($w * 0.06, 0);
        $texto = 'B.CÁLC ICMS';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w11, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w11, $y, $x + $w11, $y + $hmax);
        $this->pdf->dashedVLine($x + $w11, $y, 0.1, $y + $hmax, 100);
        //VALOR ICMS
        $x     += $w11;
        $w12   = round($w * 0.06, 0);
        $texto = 'VALOR ICMS';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w12, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w12, $y, $x + $w12, $y + $hmax);
        $this->pdf->dashedVLine($x + $w12, $y, 0.1, $y + $hmax, 100);
        //VALOR IPI
        $x     += $w12;
        $w13   = round($w * 0.05, 0);
        $texto = 'VALOR IPI';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w13, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w13, $y, $x + $w13, $y + $hmax);
        $this->pdf->dashedVLine($x + $w13, $y, 0.1, $y + $hmax, 100);
        //ALÍQ. ICMS
        $x     += $w13;
        $w14   = round($w * 0.04, 0);
        $texto = 'ALÍQ. ICMS';
        $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $this->pdf->textBox($x, $y, $w14, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        //$this->pdf->line($x + $w14, $y, $x + $w14, $y + $hmax);
        $this->pdf->dashedVLine($x + $w14, $y, 0.1, $y + $hmax, 100);
        //ALÍQ. IPI
        $x     += $w14;
        $w15   = $w - ($w1 + $w2 + $w3 + $w4 + $w5 + $w6 + $w7 + $w8 + $w9 + $w10 + $w11 + $w12 + $w13 + $w14);
        $texto = 'ALÍQ. IPI';
        $this->pdf->textBox($x, $y, $w15, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($oldX, $y + $h + 1, $oldX + $w, $y + $h + 1);
        $y += 5;
        //##################################################################################
        // LOOP COM OS DADOS DOS PRODUTOS
        $i      = 0;
        $hUsado = $hCabecItens;
        $aFont  = ['font' => $this->fontePadrao, 'size' => 7, 'style' => ''];

        foreach ($this->det as $d) {
            if ($i >= $nInicio) {
                $thisItem = $this->det->item($i);
                //carrega as tags do item
                $prod         = $thisItem->getElementsByTagName("prod")->item(0);
                $imposto      = $this->det->item($i)->getElementsByTagName("imposto")->item(0);
                $ICMS         = $imposto->getElementsByTagName("ICMS")->item(0);
                $IPI          = $imposto->getElementsByTagName("IPI")->item(0);
                $textoProduto = $this->descricaoProduto($thisItem);
                //$veicProd     = $prod->getElementsByTagName("veicProd")->item(0);

                // Posição y dos dados das unidades tributaveis.
                $yTrib = $this->pdf->fontSize + .5;

                $uCom = $prod->getElementsByTagName("uCom")->item(0)->nodeValue;
                $vUnCom = $prod->getElementsByTagName("vUnCom")->item(0)->nodeValue;
                $uTrib = $prod->getElementsByTagName("uTrib")->item(0);
                $qTrib = $prod->getElementsByTagName("qTrib")->item(0);
                $cfop = $prod->getElementsByTagName("CFOP")->item(0)->nodeValue;
                $vUnTrib = !empty($prod->getElementsByTagName("vUnTrib")->item(0)->nodeValue)
                    ? $prod->getElementsByTagName("vUnTrib")->item(0)->nodeValue
                    : 0;
                // A Configuração serve para informar se irá exibir
                //   de forma obrigatória, estando diferente ou não,
                //   a unidade de medida tributária.
                // ========
                // A Exibição será realizada sempre que a unidade comercial for
                //   diferente da unidade de medida tributária.
                // "Nas situações em que o valor unitário comercial for diferente do valor unitário tributável,
                //   ambas as informações deverão estar expressas e identificadas no DANFE, podendo ser
                //   utilizada uma das linhas adicionais previstas, ou o campo de informações adicionais."
                // > Manual Integração - Contribuinte 4.01 - NT2009.006, Item 7.1.5, página 91.
                $mostrarUnidadeTributavel = (!$this->ocultarUnidadeTributavel
                    && !empty($uTrib)
                    && !empty($qTrib)
                    && number_format($vUnCom, 2, ',', '') !== number_format($vUnTrib, 2, ',', '')
                );

                // Informação sobre unidade de medida tributavel.
                // Se não for para exibir a unidade de medida tributavel, então
                // A Escrita irá começar em 0.
                if (!$mostrarUnidadeTributavel) {
                    $yTrib = 0;
                }
                $h = $this->calculeHeight($thisItem, $mostrarUnidadeTributavel);
                $hUsado += $h;

                $yTrib += $y;
                $diffH = $hmax - $hUsado;

                if (1 > $diffH && $i < $totItens) {
                    if ($pag == $totpag) {
                        $totpag++;
                    }
                        //ultrapassa a capacidade para uma única página
                        //o restante dos dados serão usados nas proximas paginas
                        $nInicio = $i;
                        break;
                }

                $y_linha = $y + $h;

                //corrige o x
                $x = $oldX;
                //codigo do produto
                $guup  = $i + 1;
                $texto = $prod->getElementsByTagName("cProd")->item(0)->nodeValue;
                $this->pdf->textBox($x, $y, $w1, $h, $texto, $aFont, 'T', 'C', 0, '');
                $x += $w1;

                //DESCRIÇÃO
                if ($this->orientacao === 'P') {
                    $this->pdf->textBox($x, $y, $w2, $h, $textoProduto, $aFont, 'T', 'L', 0, '', false);
                } else {
                    $this->pdf->textBox($x, $y, $w2, $h, $textoProduto, $aFont, 'T', 'L', 0, '', false);
                }
                $x += $w2;
                //NCM
                $texto = !empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue) ?
                    $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
                $this->pdf->textBox($x, $y, $w3, $h, $texto, $aFont, 'T', 'C', 0, '');
                $x += $w3;

                //GRUPO DE VEICULO NOVO
                $oldfont = $aFont;
                $veicnovo = $this->itemVeiculoNovo($prod);
                $aFont = ['font' => $this->fontePadrao, 'size' => 5, 'style' => ''];
                $this->pdf->textBox(
                    $x-$w3,
                    $y+4,
                    $this->wPrint-($w1+$w2)-2,
                    22,
                    $veicnovo,
                    $aFont,
                    'T',
                    'L',
                    0,
                    '',
                    true,
                    0,
                    0,
                    false
                );
                $aFont = $oldfont;
                //CST
                if (isset($ICMS)) {
                    $origem = $this->getTagValue($ICMS, "orig");
                    $cst    = $this->getTagValue($ICMS, "CST");
                    $csosn  = $this->getTagValue($ICMS, "CSOSN");
                    $texto  = $origem . "/" . $cst . $csosn;
                    $this->pdf->textBox($x, $y, $w4, $h, $texto, $aFont, 'T', 'C', 0, '');
                }
                //CFOP
                $x     += $w4;
                $texto = $prod->getElementsByTagName("CFOP")->item(0)->nodeValue;
                $this->pdf->textBox($x, $y, $w5, $h, $texto, $aFont, 'T', 'C', 0, '');
                //Unidade
                $x     += $w5;
                $texto = $uCom;
                $this->pdf->textBox($x, $y, $w6, $h, $texto, $aFont, 'T', 'C', 0, '');
                //Unidade de medida tributável
                $qTrib = $prod->getElementsByTagName("qTrib")->item(0)->nodeValue;
                if ($mostrarUnidadeTributavel) {
                    $texto = $uTrib->nodeValue;
                    $this->pdf->textBox($x, $yTrib, $w6, $h, $texto, $aFont, 'T', 'C', 0, '');
                }
                $x += $w6;
                if ($this->orientacao == 'P') {
                    $alinhamento = 'R';
                } else {
                    $alinhamento = 'R';
                }
                // QTDADE
                $qCom  = $prod->getElementsByTagName("qCom")->item(0);
                $texto = number_format($qCom->nodeValue, $this->qComCasasDec, ",", ".");
                $this->pdf->textBox($x, $y, $w7, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                // QTDADE Tributável
                if ($mostrarUnidadeTributavel) {
                    $qTrib = $prod->getElementsByTagName("qTrib")->item(0);
                    if (!empty($qTrib)) {
                        $texto = number_format($qTrib->nodeValue, $this->qComCasasDec, ",", ".");
                        $this->pdf->textBox($x, $yTrib, $w7, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                    }
                }
                $x += $w7;
                // Valor Unitário
                $vUnCom = $prod->getElementsByTagName("vUnCom")->item(0);
                $texto  = number_format($vUnCom->nodeValue, $this->vUnComCasasDec, ",", ".");
                $this->pdf->textBox($x, $y, $w8, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                // Valor Unitário Tributável
                if ($mostrarUnidadeTributavel) {
                    $vUnTrib = $prod->getElementsByTagName("vUnTrib")->item(0);
                    if (!empty($vUnTrib)) {
                        $texto = number_format($vUnTrib->nodeValue, $this->vUnComCasasDec, ",", ".");
                        $this->pdf->textBox($x, $yTrib, $w8, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                    }
                }
                $x += $w8;
                // Valor do Produto
                $texto = "";
                if (is_numeric($prod->getElementsByTagName("vProd")->item(0)->nodeValue)) {
                    $texto = number_format($prod->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
                }
                $this->pdf->textBox($x, $y, $w9, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                $x += $w9;
                //Valor do Desconto
                $vdesc = !empty($prod->getElementsByTagName("vDesc")->item(0)->nodeValue)
                    ? $prod->getElementsByTagName("vDesc")->item(0)->nodeValue : 0;

                $texto = number_format($vdesc, 2, ",", ".");
                $this->pdf->textBox($x, $y, $w10, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                //Valor da Base de calculo
                $x += $w10;
                if (isset($ICMS)) {
                    $texto = !empty($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue)
                        ? number_format(
                            $ICMS->getElementsByTagName("vBC")->item(0)->nodeValue,
                            2,
                            ",",
                            "."
                        )
                        : '0,00';
                    $this->pdf->textBox($x, $y, $w11, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                }
                //Valor do ICMS
                $x += $w11;
                if (isset($ICMS)) {
                    $texto = !empty($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue)
                        ? number_format(
                            $ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue,
                            2,
                            ",",
                            "."
                        )
                        : '0,00';
                    $this->pdf->textBox($x, $y, $w12, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                }
                //Valor do IPI
                $x += $w12;
                if (isset($IPI)) {
                    $texto = !empty($IPI->getElementsByTagName("vIPI")->item(0)->nodeValue)
                        ? number_format(
                            $IPI->getElementsByTagName("vIPI")->item(0)->nodeValue,
                            2,
                            ",",
                            "."
                        )
                        : '';
                } else {
                    $texto = '';
                }
                $this->pdf->textBox($x, $y, $w13, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                // %ICMS
                $x += $w13;
                if (isset($ICMS)) {
                    $texto = !empty($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue)
                        ? number_format(
                            $ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue,
                            2,
                            ",",
                            "."
                        )
                        : '0,00';
                    $this->pdf->textBox($x, $y, $w14, $h, $texto, $aFont, 'T', 'C', 0, '');
                }
                //%IPI
                $x += $w14;
                if (isset($IPI)) {
                    $texto = !empty($IPI->getElementsByTagName("pIPI")->item(0)->nodeValue)
                        ? number_format(
                            $IPI->getElementsByTagName("pIPI")->item(0)->nodeValue,
                            2,
                            ",",
                            "."
                        )
                        : '';
                } else {
                    $texto = '';
                }
                $this->pdf->textBox($x, $y, $w15, $h, $texto, $aFont, 'T', 'C', 0, '');


                // Dados do Veiculo Somente para veiculo 0 Km
                $veicProd = $prod->getElementsByTagName("veicProd")->item(0);
                // Tag somente é gerada para veiculo 0k, e só é permitido um veiculo por NF-e por conta do detran
                // Verifica se a Tag existe
                if (!empty($veicProd)) {
                    $y += $h - 10;
                    $this->dadosItenVeiculoDANFE($oldX + 3, $y, $nInicio, 3, $prod);
                    // linha entre itens
                    $this->pdf->dashedHLine($oldX, $y + 30, $w, 0.1, 120);
                    $y += 30;
                    $hUsado += 30;
                } else {
                    // linha entre itens
                    $this->pdf->dashedHLine($oldX, $y, $w, 0.1, 120);
                }
                $y += $h;
                $i++;
                //incrementa o controle dos itens processados.
                $this->qtdeItensProc++;
            } else {
                $i++;
            }
        }

        return $oldY + $hmax;
    }
}