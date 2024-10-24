<?php
namespace App\traits;

trait FaturaDanfe{
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