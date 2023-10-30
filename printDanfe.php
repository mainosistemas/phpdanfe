<?php
include_once './vendor/autoload.php';
include_once './maino_danfe.php';


try {
    $xml  = $_POST['xml'];
    $logo = '';

    $danfe = new MainoDanfe($xml);
    $danfe->debugMode(false);
    if (isset($_POST['logo_url'])) {
      $logo_url = explode('/', $_POST['logo_url']);
      $filename = end($logo_url);
      $logo     = realpath("../../logos/{$filename}");

      if (!is_file($logo)) {
        copy($_POST['logo_url'], $logo);
      }
    }

    if (isset($_POST['margSup'])) {
      $danfe->definirMargemSuperior(intval($_POST['margSup']));
    }

    $danfe->nfeCancelada = isset($_POST['status_nfe']) && intval($_POST['status_nfe']) == 1;
    $detalha_produtos_complemento = isset($_POST['detalha_produtos_complemento']) && $_POST['detalha_produtos_complemento'] == 'true';
    $exibir_pis_cofins = isset($_POST['exibir_pis_cofins']) && $_POST['exibir_pis_cofins'] == 'true';

    $danfe->habilitarImpressaoPisCofins($exibir_pis_cofins);
    $danfe->habilitarImpressaoComplementoProduto($detalha_produtos_complemento);

    //Gera o PDF
    $pdf = $danfe->render_com_logo($logo);
    header('Content-Type: application/pdf');
    echo $pdf;
} catch (InvalidArgumentException $e) {
    echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
}
