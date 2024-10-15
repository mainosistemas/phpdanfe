<?php
use NFePHP\DA\NFe\Danfe;

class MainoDanfe extends Danfe
{
    protected $exibirAFRMM = false;
    protected $vAFRMM = "0.00";
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

        date_default_timezone_set('America/Sao_Paulo');

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
        $this->margsup = $margem;
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
            return ['status' => false, 'message' => ['NFe CANCELADA'], 'submessage' => ''];
        } else {
            return parent::statusNFe();
        }

    }
    /**
    * habilitarImpressaoAfrmm
    * Ativa ou inativa a impressão do valor AFRMM
    */
    public function habilitarImpressaoAfrmm($habilitar = false, $valor = "0.00")
    {
      $this->exibirAFRMM = $habilitar;
      $this->vAFRMM = $valor;
    }

    /**
    * imposto
    * Monta o campo de impostos e totais da DANFE (retrato e paisagem)
    *
    * @param number $x Posição horizontal canto esquerdo
    * @param number $y Posição vertical canto superior
    *
    * @return number Posição vertical final
    */
    protected function imposto($x, $y)
    {
      $x_inicial = $x;
      //#####################################################################
      $campos_por_linha = 9;
      if (!$this->exibirPIS) {
          $campos_por_linha--;
      }
      if (!$this->exibirIcmsInterestadual) {
          $campos_por_linha -= 2;
      }

      if ($this->orientacao == 'P') {
          $maxW       = $this->wPrint;
          $title_size = 31;
      } else {
          $maxW       = $this->wPrint - $this->wCanhoto;
          $title_size = 40;
      }
      $w = $maxW / $campos_por_linha;

      $aFont = ['font' => $this->fontePadrao, 'size' => 7, 'style' => 'B'];
      $texto = "CÁLCULO DO IMPOSTO";
      $this->pdf->textBox($x, $y, $title_size, 8, $texto, $aFont, 'T', 'L', 0, '');
      $y += 3;
      $h = 7;

      $x = $this->impostoHelper($x, $y, $w, $h, "BASE DE CÁLC. DO ICMS", "vBC");
      $x = $this->impostoHelper($x, $y, $w, $h, "VALOR DO ICMS", "vICMS");
      $x = $this->impostoHelper($x, $y, $w, $h, "BASE DE CÁLC. ICMS S.T.", "vBCST");
      $x = $this->impostoHelper($x, $y, $w, $h, "VALOR DO ICMS SUBST.", "vST");
      $x = $this->impostoHelper($x, $y, $w, $h, "V. IMP. IMPORTAÇÃO", "vII");

      if ($this->exibirIcmsInterestadual) {
          $x = $this->impostoHelper($x, $y, $w, $h, "V. ICMS UF REMET.", "vICMSUFRemet");
          $x = $this->impostoHelper($x, $y, $w, $h, "V. FCP UF DEST.", "vFCPUFDest");
      }

      if ($this->exibirPIS) {
          $x = $this->impostoHelper($x, $y, $w, $h, "VALOR DO PIS", "vPIS");
      }

      $x = $this->impostoHelper($x, $y, $w, $h, "V. TOTAL PRODUTOS", "vProd");

      //

      $y += $h;
      $x = $x_inicial;

      $x = $this->impostoHelper($x, $y, $w, $h, "VALOR DO FRETE", "vFrete");
      $x = $this->impostoHelper($x, $y, $w, $h, "VALOR DO SEGURO", "vSeg");

      if ($this->exibirAFRMM) {
        $x = $this->impostoArbitrarioHelper($x, $y, $w, $h, "VALOR DO AFRMM", $this->vAFRMM);
      } else {
        $x = $this->impostoHelper($x, $y, $w, $h, "DESCONTO", "vDesc");
      }

      $x = $this->impostoHelper($x, $y, $w, $h, "OUTRAS DESPESAS", "vOutro");
      $x = $this->impostoHelper($x, $y, $w, $h, "VALOR TOTAL IPI", "vIPI");

      if ($this->exibirIcmsInterestadual) {
          $x = $this->impostoHelper($x, $y, $w, $h, "V. ICMS UF DEST.", "vICMSUFDest");
          $x = $this->impostoHelper($x, $y, $w, $h, "V. TOT. TRIB.", "vTotTrib");
      }
      if ($this->exibirPIS) {
          $x = $this->impostoHelper($x, $y, $w, $h, "VALOR DA COFINS", "vCOFINS");
      }
      $x = $this->impostoHelper($x, $y, $w, $h, "V. TOTAL DA NOTA", "vNF");

      return ($y + $h);
    } //fim imposto

    protected function impostoArbitrarioHelper($x, $y, $w, $h, $titulo, $valorImposto)
    {
        $valorImpostoFormatado = number_format($valorImposto, 2, ",", ".");

        $fontTitulo = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
        $fontValor  = ['font' => $this->fontePadrao, 'size' => 10, 'style' => 'B'];
        $this->pdf->textBox($x, $y, $w, $h, $titulo, $fontTitulo, 'T', 'L', 1, '');
        $this->pdf->textBox($x, $y, $w, $h, $valorImpostoFormatado, $fontValor, 'B', 'R', 0, '');

        $next_x = $x + $w;

        return $next_x;
    }
   /**
   * descricaoProduto
   * Monta a string de descrição de cada Produto
   *
   * @name   descricaoProduto
   *
   * @param DOMNode itemProd
   *
   * @return string descricao do produto
   */
  protected function descricaoProduto($itemProd)
  {
      $prod       = $itemProd->getElementsByTagName('prod')->item(0);
      $ICMS       = $itemProd->getElementsByTagName("ICMS")->item(0);
      $ICMSUFDest = $itemProd->getElementsByTagName("ICMSUFDest")->item(0);
      $impostos   = '';

      if (!empty($ICMS)) {
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vBCFCP", " BcFcp=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "pFCP", " pFcp=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vFCP", " vFcp=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "pRedBC", " pRedBC=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "pMVAST", " IVA/MVA=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "pICMSST", " pIcmsSt=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vBCST", " BcIcmsSt=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vICMSST", " vIcmsSt=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vBCFCPST", " BcFcpSt=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "pFCPST", " pFcpSt=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vFCPST", " vFcpSt=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vBCSTRet", " Retido na compra: BASE ICMS ST=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "pST", " pSt=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vICMSSubstituto", " vICMSSubstituto=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMS, "vICMSSTRet", " VALOR ICMS ST=%s");
      }
      if (!empty($ICMSUFDest)) {
          $impostos .= $this->descricaoProdutoHelper($ICMSUFDest, "pFCPUFDest", " pFCPUFDest=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMSUFDest, "pICMSUFDest", " pICMSUFDest=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMSUFDest, "pICMSInterPart", " pICMSInterPart=%s%%");
          $impostos .= $this->descricaoProdutoHelper($ICMSUFDest, "vFCPUFDest", " vFCPUFDest=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMSUFDest, "vICMSUFDest", " vICMSUFDest=%s");
          $impostos .= $this->descricaoProdutoHelper($ICMSUFDest, "vICMSUFRemet", " vICMSUFRemet=%s");
      }
      $infAdProd = !empty($itemProd->getElementsByTagName('infAdProd')->item(0)->nodeValue)
          ? substr(
              //$this->anfaveaDANFE($itemProd->getElementsByTagName('infAdProd')->item(0)->nodeValue),
              $itemProd->getElementsByTagName('infAdProd')->item(0)->nodeValue,
              0,
              500
          )
          : '';
      if (!empty($infAdProd)) {
          $infAdProd = trim($infAdProd);
          $infAdProd .= ' ';
      }
      $loteTxt = '';
      if ($this->descProdInfoLoteTxt) {
          $med = $prod->getElementsByTagName("med")->item(0);

          if (!empty($prod->getElementsByTagName("rastro"))) {
              $rastro = $prod->getElementsByTagName("rastro");
              if ($rastro->length === 1) {
                  $i = 0;
                  //while ($i < $rastro->length) {
                      $dFab = $this->getTagDate($rastro->item($i), 'dFab');
                      $datafab = " Fab: " . $dFab;
                      $dVal = $this->getTagDate($rastro->item($i), 'dVal');
                      $dataval = " Val: " . $dVal;
                      $loteTxt .= $this->getTagValue($rastro->item($i), 'nLote', ' Lote: ');
                      $loteTxt .= $this->getTagValue($rastro->item($i), 'qLote', ' Quant: ');
                      $loteTxt .= $datafab; //$this->getTagDate($rastro->item($i), 'dFab', ' Fab: ');
                      $loteTxt .= $dataval; //$this->getTagDate($rastro->item($i), 'dVal', ' Val: ');
                      $loteTxt .= $this->getTagValue($rastro->item($i), 'vPMC', ' PMC: ');
                      //$i++;
                  //}
              }
              if ($loteTxt != '') {
                  $loteTxt .= ' ';
              }
          }
      }
      $infAdProd .= $this->getTagValue($med, 'cProdANVISA', 'ANVISA: ');

      //NT2013.006 FCI
      $nFCI   = (!empty($itemProd->getElementsByTagName('nFCI')->item(0)->nodeValue)) ?
          ' FCI:' . $itemProd->getElementsByTagName('nFCI')->item(0)->nodeValue : '';

      $tmp_ad = $infAdProd . ($this->descProdInfoComplemento ? $loteTxt . $impostos . $nFCI : '');
//       $tmp_ad = $infAdProd . ($this->descProdInfoComplemento ? $loteTxt . $nFCI : '');
      $texto  = $prod->getElementsByTagName("xProd")->item(0)->nodeValue
          . (strlen($tmp_ad) != 0 ? "\n    " . $tmp_ad : '');
      //decodifica os caracteres html no xml
      $texto = html_entity_decode($texto);
      if ($this->descProdQuebraLinha) {
          $texto = str_replace(";", "\n", $texto);
      }

      if ($this->exibirNumeroItemPedido && !empty($itemProd->getElementsByTagName('nItemPed')->item(0)->nodeValue)) {
          $texto .= " (ITEM " . $itemProd->getElementsByTagName('nItemPed')->item(0)->nodeValue . ")";
      }

      return $texto;
  }

  protected function fatura($x, $y)
    {
        $linha       = 1;
        $h           = 8 + 3;
        $oldx        = $x;
        $textoFatura = $this->getTextoFatura();
        //verificar se existem duplicatas
        if ($this->dup->length > 0 || $textoFatura !== "") {
            //#####################################################################
            //FATURA / DUPLICATA
            $texto = "FATURA / DUPLICATA";
            if ($this->orientacao == 'P') {
                $w = $this->wPrint;
            } else {
                $w = 271;
            }
            $h     = 8;
            $aFont = ['font' => $this->fontePadrao, 'size' => 7, 'style' => 'B'];
            $this->pdf->textBox($x, $y, $w, $h, $texto, $aFont, 'T', 'L', 0, '');
            $y       += 3;
            $dups    = "";
            $dupcont = 0;
            $nFat    = $this->dup->length;

            // if ($nFat > 7) {
            //     $myH = 6;
            //     $myW = $this->wPrint;
            //     if ($this->orientacao == 'L') {
            //         $myW -= $this->wCanhoto;
            //     }
            //     $aFont = ['font' => $this->fontePadrao, 'size' => 9, 'style' => ''];
            //     $texto = "Existem mais de 7 duplicatas registradas, portanto não "
            //         . "serão exibidas, confira diretamente pelo XML.";
            //     $this->pdf->textBox($x, $y, $myW, $myH, $texto, $aFont, 'C', 'C', 1, '');

            //     return ($y + $h - 3);
            // }
            if ($textoFatura !== "" && $this->exibirTextoFatura) {
                $myH = 6;
                $myW = $this->wPrint;
                if ($this->orientacao == 'L') {
                    $myW -= $this->wCanhoto;
                }
                $aFont = ['font' => $this->fontePadrao, 'size' => 8, 'style' => ''];
                $this->pdf->textBox($x, $y, $myW, $myH, $textoFatura, $aFont, 'C', 'L', 1, '');
                $y += $myH + 1;
            }
            if ($this->orientacao != 'P') {
                $w = round($this->wPrint / 7.018, 0) - 1;
            } else {
                $w = 28;
            }
            $increm = 1;
            foreach ($this->dup as $k => $d) {
                $nDup  = !empty($this->dup->item($k)->getElementsByTagName('nDup')->item(0)->nodeValue)
                    ? $this->dup->item($k)->getElementsByTagName('nDup')->item(0)->nodeValue
                    : '';
                $dDup  = !empty($this->dup->item($k)->getElementsByTagName('dVenc')->item(0)->nodeValue)
                    ? $this->ymdTodmy($this->dup->item($k)->getElementsByTagName('dVenc')->item(0)->nodeValue)
                    : '';
                $vDup  = !empty($this->dup->item($k)->getElementsByTagName('vDup')->item(0)->nodeValue)
                    ? 'R$ ' . number_format(
                        $this->dup->item($k)->getElementsByTagName('vDup')->item(0)->nodeValue,
                        2,
                        ",",
                        "."
                    )
                    : '';
                $h     = 8;
                $texto = '';
                if ($nDup != '0' && $nDup != '') {
                    $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
                    $this->pdf->textBox($x, $y, $w, $h, 'Num.', $aFont, 'T', 'L', 1, '');
                    $aFont = ['font' => $this->fontePadrao, 'size' => 7, 'style' => 'B'];
                    $this->pdf->textBox($x, $y, $w, $h, $nDup, $aFont, 'T', 'R', 0, '');
                } else {
                    $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
                    $this->pdf->textBox($x, $y, $w, $h, ($dupcont + 1) . "", $aFont, 'T', 'L', 1, '');
                }
                $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
                $this->pdf->textBox($x, $y, $w, $h, 'Venc.', $aFont, 'C', 'L', 0, '');
                $aFont = ['font' => $this->fontePadrao, 'size' => 7, 'style' => 'B'];
                $this->pdf->textBox($x, $y, $w, $h, $dDup, $aFont, 'C', 'R', 0, '');
                $aFont = ['font' => $this->fontePadrao, 'size' => 6, 'style' => ''];
                $this->pdf->textBox($x, $y, $w, $h, 'Valor', $aFont, 'B', 'L', 0, '');
                $aFont = ['font' => $this->fontePadrao, 'size' => 7, 'style' => 'B'];
                $this->pdf->textBox($x, $y, $w, $h, $vDup, $aFont, 'B', 'R', 0, '');
                $x       += $w + $increm;
                $dupcont += 1;
                if ($this->orientacao == 'P') {
                    $maxDupCont = 6;
                } else {
                    $maxDupCont = 8;
                }
                if ($dupcont > $maxDupCont) {
                    $y       += 9;
                    $x       = $oldx;
                    $dupcont = 0;
                    $linha   += 1;
                }
                if ($linha == 5) {
                    $linha = 4;
                    break;
                }
            }
            if ($dupcont == 0) {
                $y -= 9;
                $linha--;
            }

            return ($y + $h);
        } else {
            $linha = 0;

            return ($y - 2);
        }
    }
}
