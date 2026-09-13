<?php
namespace NFePHP\EFD\Common;

/**
 * Classe abstrata basica de onde cada bloco é cunstruido
 */
abstract class Block implements BlockInterface
{
    /**
     * @var array
     */
    public $errors = [];
    /**
     * @var array
     */
    public $elements = [];
    /**
     * @var string
     */
    protected $bloco = '';
    /**
     * Registro totalizador do bloco (x990)
     * @var string
     */
    protected $elementTotal;
    /**
     * @var string
     */
    protected $layout;
    /**
     * @var \stdClass
     */
    protected $vigencia;
    /**
     * @var string
     */
    protected $grupo;

    /**
     * @param string $layout código do leiaute com 3 dígitos (ex.: '020');
     *                       para escolher pela data use Vigencia::paraPeriodo()
     * @throws \InvalidArgumentException leiaute não disponível para o grupo
     */
    public function __construct(string $layout)
    {
        $this->vigencia = Vigencia::carregar($this->grupo, $layout);
        $this->layout = $layout;
    }

    /**
     * Call classes to build each EFD element
     * @param string $name
     * @param array<int, mixed> $arguments [std]
     * @return void
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $name = str_replace('-', '', strtolower($name));
        $realname = $name;
        if (!array_key_exists($realname, $this->elements)) {
            throw new \Exception("Não encontrada referencia ao método $name.");
        }
        $className = $this->elements[$realname]['class'];
        if (empty($arguments[0])) {
            throw new \Exception("Sem dados passados para o método [$name].");
        }
        /** @var Element $elclass */
        $elclass = new $className($arguments[0], $this->vigencia);
        if ($className::REG === '0000') {
            $this->conferirCodVer($elclass);
        }
        foreach ($elclass->errors as $err) {
            $this->errors[] = $err;
        }
        $this->bloco .= "{$elclass}\n";
    }

    /**
     * Totalizes the elements of the block and returns the complete block
     * in a string adding element 0990
     * @return string
     */
    public function get()
    {
        //fazer a montagem do elemento 0990 Totalizador
        $n = count(explode("\n", $this->bloco));
        $this->bloco .= "|" . $this->elementTotal . "|$n|\n";
        return $this->bloco;
    }

    /**
     * O COD_VER do registro 0000 tem de ser o leiaute dos blocos; vazio, assume o leiaute.
     */
    private function conferirCodVer(Element $registro): void
    {
        $codVer = $registro->std->cod_ver ?? null;
        if ($codVer === null || $codVer === '') {
            $registro->std->cod_ver = $this->layout;
            return;
        }
        if ((string) $codVer !== $this->layout) {
            $registro->errors[] = "[0000] campo: COD_VER [$codVer] diferente do leiaute dos blocos [{$this->layout}].";
        }
    }
}
