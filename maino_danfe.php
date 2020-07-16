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
        $this->creditsIntegratorFooter("Emitida por Mainô - www.maino.com.br");
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
            parent::statusNFe();
        }

    }
}