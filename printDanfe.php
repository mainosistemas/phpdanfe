<?php
include_once './vendor/autoload.php';
include_once './maino_danfe.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

try {
    $xml  = $_POST['xml'];
    $logo = '';

    $danfe = new MainoDanfe($xml);
    $danfe->debugMode(false);

    if (isset($_POST['logo_url'])) {
      $logo_url = explode('/', $_POST['logo_url']);
      $filename = end($logo_url);
      $logo     =  "logos/{$filename}";

      if (!is_file($logo)) {
        copy($_POST['logo_url'], $logo);
      }
    }

    if (isset($_POST['margsup'])) {
      $danfe->definirMargemSuperior(intval($_POST['margsup']));
    }

    $danfe->nfeCancelada = isset($_POST['status_nfe']) && intval($_POST['status_nfe']) == 1;
    $detalhaProdutosComplemento = isset($_POST['detalha_produtos_complemento']) && $_POST['detalha_produtos_complemento'] == 'true';
    $exibirPisCofins = isset($_POST['exibir_pis_cofins']) && $_POST['exibir_pis_cofins'] == 'true';


    $exibir_afrmm = isset($_POST['exibir_afrmm']) && $_POST['exibir_afrmm'] == 'true';
    $valor_afrmm = $_POST['valor_afrmm'];

    $danfe->habilitarImpressaoPisCofins($exibirPisCofins);
    $danfe->habilitarImpressaoComplementoProduto($detalhaProdutosComplemento);

    $danfe->habilitarImpressaoAfrmm($exibir_afrmm, $valor_afrmm);

    //Gera o PDF
    $pdf = $danfe->render_com_logo($logo);
    header('Content-Type: application/pdf');
    echo $pdf;
} catch (InvalidArgumentException $e) {
    echo "Ocorreu um erro durante o processamento: " . $e->getMessage();
}
