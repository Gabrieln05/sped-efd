<?php

namespace NFePHP\EFD\Common;

use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

/**
 * Leiautes disponíveis por grupo, lidos de storage/layouts/{grupo}/vigencias.json.
 *
 * Um leiaute só é aceito se estiver no vigencias.json e tiver a pasta
 * storage/layouts/{grupo}/v{leiaute}/. Nunca cai em outro leiaute em silêncio.
 */
final class Vigencia
{
    public const ICMSIPI = 'ICMSIPI';
    public const CONTRIBUICOES = 'Contribuicoes';

    /**
     * @var array<string, array<string, array{versao: string, inicio: string, fim: string}>>
     */
    private static array $cache = [];

    /**
     * @return array<string, array{versao: string, inicio: string, fim: string}>
     */
    public static function leiautes(string $grupo): array
    {
        if (isset(self::$cache[$grupo])) {
            return self::$cache[$grupo];
        }
        if (!in_array($grupo, [self::ICMSIPI, self::CONTRIBUICOES], true)) {
            throw new InvalidArgumentException("Grupo de leiaute desconhecido [$grupo].");
        }
        $arquivo = self::pastaGrupo($grupo) . '/vigencias.json';
        $vigencias = json_decode((string) file_get_contents($arquivo), true);
        if (!is_array($vigencias) || $vigencias === []) {
            throw new RuntimeException("vigencias.json inválido ou vazio em [$arquivo].");
        }
        return self::$cache[$grupo] = $vigencias;
    }

    /**
     * Dados do leiaute repassados aos blocos e registros: path, layout e vigencia.
     *
     * @throws InvalidArgumentException leiaute fora do vigencias.json
     * @throws RuntimeException leiaute sem a pasta de JSONs
     */
    public static function carregar(string $grupo, string $layout): stdClass
    {
        $leiautes = self::leiautes($grupo);
        if (!isset($leiautes[$layout])) {
            throw new InvalidArgumentException(
                "Leiaute [$layout] não disponível para $grupo. Disponíveis: "
                . implode(', ', array_keys($leiautes)) . '.'
            );
        }
        $path = self::pastaGrupo($grupo);
        if (!is_dir("$path/v$layout")) {
            throw new RuntimeException("Leiaute [$layout] de $grupo está no vigencias.json, mas não tem a pasta v$layout.");
        }
        return (object) [
            'path' => $path,
            'layout' => $layout,
            'vigencia' => (object) $leiautes[$layout],
        ];
    }

    /**
     * Leiaute em vigor na data inicial do período da escrituração (DT_INI do 0000).
     *
     * @throws InvalidArgumentException nenhum leiaute disponível para a data
     */
    public static function paraPeriodo(string $grupo, DateTimeInterface $dtIni): string
    {
        $dia = $dtIni->format('Ymd');
        foreach (self::leiautes($grupo) as $layout => $vigencia) {
            $inicio = self::paraAnoMesDia($vigencia['inicio']);
            $fim = $vigencia['fim'] === '' ? null : self::paraAnoMesDia($vigencia['fim']);
            if ($dia >= $inicio && ($fim === null || $dia <= $fim)) {
                return (string) $layout;
            }
        }
        throw new InvalidArgumentException(
            "Nenhum leiaute de $grupo disponível para o período iniciado em " . $dtIni->format('d/m/Y') . '.'
        );
    }

    private static function paraAnoMesDia(string $ddmmaaaa): string
    {
        return substr($ddmmaaaa, 4, 4) . substr($ddmmaaaa, 2, 2) . substr($ddmmaaaa, 0, 2);
    }

    private static function pastaGrupo(string $grupo): string
    {
        return dirname(__DIR__, 2) . '/storage/layouts/' . $grupo;
    }
}
