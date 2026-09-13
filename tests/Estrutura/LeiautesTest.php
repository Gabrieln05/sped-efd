<?php

namespace NFePHP\EFD\Tests\Estrutura;

use NFePHP\EFD\Common\Vigencia;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Coerência entre blocos, classes de registro e JSONs, para cada leiaute
 * disponível no vigencias.json. Leiaute novo entra aqui sozinho.
 */
final class LeiautesTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function leiautes(): iterable
    {
        foreach ([Vigencia::ICMSIPI, Vigencia::CONTRIBUICOES] as $grupo) {
            foreach (array_keys(Vigencia::leiautes($grupo)) as $layout) {
                yield "$grupo $layout" => [$grupo, (string) $layout];
            }
        }
    }

    #[DataProvider('leiautes')]
    public function testTodoRegistroDosBlocosTemClasseEJson(string $grupo, string $layout): void
    {
        $vigencia = Vigencia::carregar($grupo, $layout);
        $falhas = [];
        foreach (self::blocos($grupo) as $classeBloco) {
            $bloco = new $classeBloco($layout);
            foreach ($bloco->elements as $metodo => $definicao) {
                $classe = $definicao['class'];
                if (!class_exists($classe)) {
                    $falhas[] = "$classeBloco::$metodo aponta para $classe, que não existe";
                    continue;
                }
                $reg = $classe::REG;
                if (strtolower($reg) !== ltrim($metodo, 'z')) {
                    $falhas[] = "$classeBloco::$metodo aponta para o registro $reg";
                }
                if (!is_file("{$vigencia->path}/v$layout/$reg.json")) {
                    $falhas[] = "$reg ($classeBloco::$metodo) sem JSON em v$layout";
                }
            }
        }
        $this->assertSame([], $falhas);
    }

    /**
     * A validação posterior das classes é a mesma para todos os leiautes:
     * todo campo que ela lê tem de existir no JSON de cada leiaute.
     */
    #[DataProvider('leiautes')]
    public function testCamposLidosNaValidacaoExistemNoJson(string $grupo, string $layout): void
    {
        $vigencia = Vigencia::carregar($grupo, $layout);
        $falhas = [];
        foreach (self::blocos($grupo) as $classeBloco) {
            foreach ((new ReflectionClass($classeBloco))->getDefaultProperties()['elements'] as $definicao) {
                $rc = new ReflectionClass($definicao['class']);
                $reg = $rc->getConstant('REG');
                $json = json_decode((string) file_get_contents("{$vigencia->path}/v$layout/$reg.json"), true);
                $campos = array_map('strtolower', array_keys($json));
                preg_match_all('/\$this->(?:values|std)->([A-Za-z_0-9]+)/', (string) file_get_contents($rc->getFileName()), $m);
                foreach (array_unique($m[1]) as $campo) {
                    if (!in_array(strtolower($campo), $campos, true)) {
                        $falhas[] = "$reg lê [$campo], que não existe no JSON v$layout";
                    }
                }
            }
        }
        $this->assertSame([], $falhas);
    }

    public function testNenhumaClasseDeRegistroTemEstruturaEmbutida(): void
    {
        $falhas = [];
        foreach (glob(dirname(__DIR__, 2) . '/src/Elements/*/*.php') as $arquivo) {
            $classe = 'NFePHP\\EFD\\Elements\\' . basename(dirname($arquivo)) . '\\' . basename($arquivo, '.php');
            if ((new ReflectionClass($classe))->getDefaultProperties()['parameters'] !== null) {
                $falhas[] = $classe;
            }
        }
        $this->assertSame([], $falhas, 'A estrutura do registro vive só no JSON do leiaute.');
    }

    /**
     * @return list<string>
     */
    private static function blocos(string $grupo): array
    {
        $classes = [];
        foreach (glob(dirname(__DIR__, 2) . "/src/Blocks/$grupo/*.php") as $arquivo) {
            $classes[] = "NFePHP\\EFD\\Blocks\\$grupo\\" . basename($arquivo, '.php');
        }
        return $classes;
    }
}
