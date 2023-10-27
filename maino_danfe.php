<?php
use NFePHP\DA\NFe\Danfe;

class MainoDanfe extends Danfe
{
    /**
     * Informa se a nota fiscal está cancelada, para imprimir como marca d'água.
     * @var bool
     */
    public $nfeCancelada = false;
     /**
     * __construct
     *
     * @name  __construct
     * @param string  $xml Conteúdo XML da NF-e (com ou sem a tag nfeProc)
     */
    public function __construct($xml)
    {
        parent::__construct($xml);

        // Opções padrão da Mainô
        $this->exibirIcmsInterestadual = false;
        $this->exibirValorTributos     = false;
        $this->exibirPIS               = false;
        $this->descProdInfoComplemento = false;
        $this->creditsIntegratorFooter("Emitida por Mainô - www.maino.com.br");
    }

    /**
     * Dados brutos do PDF, aceitando logo
     * @return string
     */
    public function render_com_logo($logo)
    {
        if (empty($this->pdf)) {
            $this->monta($logo);
        }
        return $this->pdf->getPdf();
    }

     /**
     * habilitarImpressaoPisCofins
     * Ativa ou inativa a impressão dos totais de PIS e Cofins
     */
    public function habilitarImpressaoPisCofins($habilitar = true)
    {
        $this->exibirPIS = $habilitar;
    }

     /**
     * habilitarImpressaoComplementoProduto
     * Ativa ou inativa a impressão dos detalhes dos produtos
     */
    public function habilitarImpressaoComplementoProduto($habilitar = true)
    {
        $this->descProdInfoComplemento = $habilitar;
    }

     /**
     * habilitarImpressaoComplementoProduto
     * Ativa ou inativa a impressão dos detalhes dos produtos
     */
    public function definirMargemSuperior($margem = 2)
    {
        $this->margSup = $margem;
    }

     /**
     * dadosItenVeiculoDANFE
     * Função sobrescrita em branco, pois sempre informamos esses dados nas
     * informações complementares
     */
    protected function dadosItenVeiculoDANFE($x, $y, &$nInicio, $h, $prod)
    {
    }

    /**
     * dadosItemVeiculoDANFE
     * Função criada por segurança, visto que é uma correção bem provável
     * no repositório do NFePHP
     */
    protected function dadosItemVeiculoDANFE($x, $y, &$nInicio, $h, $prod)
    {
    }

    /**
     * Verifica o status da NFe, aceitando que a NF-e esteja cancelada.
     *
     * @return array
     */
    protected function statusNFe()
    {
        if ($this->nfeCancelada) {
            return ['status' => false, 'message' => 'NFe CANCELADA'];
        } else {
            return parent::statusNFe();
        }

    }

    protected function itens($x, $y, &$nInicio, $hmax, $pag = 0, $totpag = 0, $hCabecItens = 7)
    {
        $oldX = $x;
        $oldY = $y;
        $totItens = $this->det->length;
        //#####################################################################
        //DADOS DOS PRODUTOS / SERVIÇOS
        $texto = "DADOS DOS PRODUTOS / SERVIÇOS ";
        if ($this->orientacao == 'P') {
            $w = $this->wPrint;
        } else {
            if ($nInicio < 2) { // primeira página
                $w = $this->wPrint - $this->wCanhoto;
            } else { // páginas seguintes
                $w = $this->wPrint;
            }
        }
        $h = 4;
        $aFont = ['font'=>$this->fontePadrao, 'size'=>7, 'style'=>'B'];
        $this->pdf->textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
        $y += 3;
        //desenha a caixa dos dados dos itens da NF
        $hmax += 1;
        $texto = '';
        $this->pdf->textBox($x, $y, $w, $hmax);
        //##################################################################################
        // cabecalho LOOP COM OS DADOS DOS PRODUTOS
        //CÓDIGO PRODUTO
        $texto = "CÓDIGO PRODUTO";
        $w1 = round($w*0.09, 0);
        $h = 4;
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w1, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w1, $y, $x+$w1, $y+$hmax);
        //DESCRIÇÃO DO PRODUTO / SERVIÇO
        $x += $w1;
        $w2 = round($w*0.25, 0);
        $texto = 'DESCRIÇÃO DO PRODUTO / SERVIÇO';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w2, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w2, $y, $x+$w2, $y+$hmax);
        //NCM/SH
        $x += $w2;
        $w3 = round($w*0.06, 0);
        $texto = 'NCM/SH';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w3, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w3, $y, $x+$w3, $y+$hmax);
        //O/CST ou O/CSOSN
        $x += $w3;
        $w4 = round($w*0.05, 0);
        $texto = 'O/CSOSN';//Regime do Simples CRT = 1 ou CRT = 2
        if ($this->getTagValue($this->emit, 'CRT') == '3') {
             $texto = 'O/CST';//Regime Normal
        }
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w4, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w4, $y, $x+$w4, $y+$hmax);
        //CFOP
        $x += $w4;
        $w5 = round($w*0.04, 0);
        $texto = 'CFOP';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w5, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w5, $y, $x+$w5, $y+$hmax);
        //UN
        $x += $w5;
        $w6 = round($w*0.03, 0);
        $texto = 'UN';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w6, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w6, $y, $x+$w6, $y+$hmax);
        //QUANT
        $x += $w6;
        $w7 = round($w*0.08, 0);
        $texto = 'QUANT';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w7, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w7, $y, $x+$w7, $y+$hmax);
        //VALOR UNIT
        $x += $w7;
        $w8 = round($w*0.06, 0);
        $texto = 'VALOR UNIT';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w8, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w8, $y, $x+$w8, $y+$hmax);
        //VALOR TOTAL
        $x += $w8;
        $w9 = round($w*0.06, 0);
        $texto = 'VALOR TOTAL';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w9, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w9, $y, $x+$w9, $y+$hmax);
        //VALOR DESCONTO
        $x += $w9;
        $w10 = round($w*0.05, 0);
        $texto = 'VALOR DESC';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w10, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w10, $y, $x+$w10, $y+$hmax);
        //B.CÁLC ICMS
        $x += $w10;
        $w11 = round($w*0.06, 0);
        $texto = 'B.CÁLC ICMS';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w11, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w11, $y, $x+$w11, $y+$hmax);
        //VALOR ICMS
        $x += $w11;
        $w12 = round($w*0.06, 0);
        $texto = 'VALOR ICMS';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w12, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w12, $y, $x+$w12, $y+$hmax);
        //VALOR IPI
        $x += $w12;
        $w13 = round($w*0.05, 0);
        $texto = 'VALOR IPI';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w13, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w13, $y, $x+$w13, $y+$hmax);
        //ALÍQ. ICMS
        $x += $w13;
        $w14 = round($w*0.04, 0);
        $texto = 'ALÍQ. ICMS';
        $aFont = ['font'=>$this->fontePadrao, 'size'=>6, 'style'=>''];
        $this->pdf->textBox($x, $y, $w14, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($x+$w14, $y, $x+$w14, $y+$hmax);
        //ALÍQ. IPI
        $x += $w14;
        $w15 = $w-($w1+$w2+$w3+$w4+$w5+$w6+$w7+$w8+$w9+$w10+$w11+$w12+$w13+$w14);
        $texto = 'ALÍQ. IPI';
        $this->pdf->textBox($x, $y, $w15, $h, $texto, $aFont, 'C', 'C', 0, '', false);
        $this->pdf->line($oldX, $y+$h+1, $oldX + $w, $y+$h+1);
        $y += 5;
        //##################################################################################
        // LOOP COM OS DADOS DOS PRODUTOS
        $i = 0;
        $hUsado = $hCabecItens;
        $aFont = ['font'=>$this->fontePadrao, 'size'=>7, 'style'=>''];
        foreach ($this->det as $d) {
            if ($i >= $nInicio) {
                $thisItem = $this->det->item($i);
                //carrega as tags do item
                $prod = $thisItem->getElementsByTagName("prod")->item(0);
                $imposto = $this->det->item($i)->getElementsByTagName("imposto")->item(0);
                $ICMS = $imposto->getElementsByTagName("ICMS")->item(0);
                $IPI  = $imposto->getElementsByTagName("IPI")->item(0);
                $textoProduto = trim($this->descricaoProduto($thisItem));

                $linhaDescr = $this->pdf->getNumLines($textoProduto, $w2, $aFont);
                $h = round(($linhaDescr * $this->pdf->fontSize)+ ($linhaDescr * 0.5), 2);
                $hUsado += $h;

                $diffH = $hmax - $hUsado;

                if ($pag != $totpag) {
                    if (1 > $diffH && $i < $totItens) {
                        //ultrapassa a capacidade para uma única página
                        //o restante dos dados serão usados nas proximas paginas
                        $nInicio = $i;
                        break;
                    }
                }
                $y_linha=$y+$h;
                // linha entre itens
                $this->pdf->dashedHLine($oldX, $y_linha, $w, 0.1, 120);
                //corrige o x
                $x=$oldX;
                //codigo do produto
                $texto = $prod->getElementsByTagName("cProd")->item(0)->nodeValue;
                $this->pdf->textBox($x, $y, $w1, $h, $texto, $aFont, 'T', 'C', 0, '');
                $x += $w1;
                //DESCRIÇÃO
                if ($this->orientacao == 'P') {
                    $this->pdf->textBox($x, $y, $w2, $h, $textoProduto, $aFont, 'T', 'L', 0, '', false);
                } else {
                    $this->pdf->textBox($x, $y, $w2, $h, $textoProduto, $aFont, 'T', 'L', 0, '', false);
                }
                $x += $w2;
                //NCM
                $texto = ! empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue) ?
                        $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
                $this->pdf->textBox($x, $y, $w3, $h, $texto, $aFont, 'T', 'C', 0, '');
                $x += $w3;
                //CST
                if (isset($ICMS)) {
                    $origem =  $this->getTagValue($ICMS, "orig");
                    $cst =  $this->getTagValue($ICMS, "CST");
                    $csosn =  $this->getTagValue($ICMS, "CSOSN");
                    $texto = $origem.$cst.$csosn;
                    $this->pdf->textBox($x, $y, $w4, $h, $texto, $aFont, 'T', 'C', 0, '');
                }
                //CFOP
                $x += $w4;
                $texto = $prod->getElementsByTagName("CFOP")->item(0)->nodeValue;
                $this->pdf->textBox($x, $y, $w5, $h, $texto, $aFont, 'T', 'C', 0, '');
                //Unidade
                $x += $w5;
                $texto = $prod->getElementsByTagName("uCom")->item(0)->nodeValue . $prod->getElementsByTagName("uTrib")->item(0)->nodeValue;
                $this->pdf->textBox($x, $y, $w6, $h, $texto, $aFont, 'T', 'C', 0, '');
                $x += $w6;
                if ($this->orientacao == 'P') {
                    $alinhamento = 'R';
                } else {
                    $alinhamento = 'R';
                }
                // QTDADE
                $texto = number_format($prod->getElementsByTagName("qCom")->item(0)->nodeValue, 4, ",", ".");
                $this->pdf->textBox($x, $y, $w7, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                $x += $w7;
                // Valor Unitário
                $texto = number_format($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue, 4, ",", ".");
                $this->pdf->textBox($x, $y, $w8, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                $x += $w8;
                // Valor do Produto
                $texto = "";
                if (is_numeric($prod->getElementsByTagName("vProd")->item(0)->nodeValue)) {
                    $texto = number_format($prod->getElementsByTagName("vProd")->item(0)->nodeValue, 2, ",", ".");
                }
                $this->pdf->textBox($x, $y, $w9, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                $x += $w9;
                //Valor do Desconto
                $texto = number_format($prod->getElementsByTagName("vDesc")->item(0)->nodeValue, 2, ",", ".");
                $this->pdf->textBox($x, $y, $w10, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                //Valor da Base de calculo
                $x += $w10;
                if (isset($ICMS)) {
                    $texto = ! empty($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue)
                    ? number_format(
                        $ICMS->getElementsByTagName("vBC")->item(0)->nodeValue,
                        2,
                        ",",
                        "."
                    )
                    : '0, 00';
                    $this->pdf->textBox($x, $y, $w11, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                }
                //Valor do ICMS
                $x += $w11;
                if (isset($ICMS)) {
                    $texto = ! empty($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue)
                    ? number_format(
                        $ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue,
                        2,
                        ",",
                        "."
                    )
                    : '0, 00';
                    $this->pdf->textBox($x, $y, $w12, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                }
                //Valor do IPI
                $x += $w12;
                if (isset($IPI)) {
                    $texto = ! empty($IPI->getElementsByTagName("vIPI")->item(0)->nodeValue)
                    ? number_format(
                        $IPI->getElementsByTagName("vIPI")->item(0)->nodeValue,
                        2,
                        ",",
                        "."
                    )
                    :'';
                } else {
                    $texto = '';
                }
                $this->pdf->textBox($x, $y, $w13, $h, $texto, $aFont, 'T', $alinhamento, 0, '');
                // %ICMS
                $x += $w13;
                if (isset($ICMS)) {
                    $texto = ! empty($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue)
                    ? number_format(
                        $ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue,
                        2,
                        ",",
                        "."
                    )
                    : '0, 00';
                    $this->pdf->textBox($x, $y, $w14, $h, $texto, $aFont, 'T', 'C', 0, '');
                }
                //%IPI
                $x += $w14;
                if (isset($IPI)) {
                    $texto = ! empty($IPI->getElementsByTagName("pIPI")->item(0)->nodeValue)
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
                    $this->dadosItenVeiculoDANFE($oldX, $y, $nInicio, $h, $prod);
                }


                $y += $h;
                $i++;
                //incrementa o controle dos itens processados.
                $this->qtdeItensProc++;
            } else {
                $i++;
            }
        }
        return $oldY+$hmax;
    }

}