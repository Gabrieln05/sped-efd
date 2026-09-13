# Changelog

All Notable changes to `sped-efd` will be documented in this file.

Todas as atualizações a partir de 30/05/206 devem observar os principios [Mantendo o CHANGELOG](http://keepachangelog.com/).

## [Fork Artemis] Onda 1 — 2026-09-12

Fundação. O arquivo gerado continua idêntico ao do upstream `b9a874f`; o que
muda é o que antes falhava em silêncio.

### Changed
- **Leiaute obrigatório** em todos os blocos e registros. Leiaute fora do
  `vigencias.json`, ou sem a pasta `vNNN`, lança `InvalidArgumentException`
  (antes caía no último leiaute sem aviso).
- **O JSON é a única fonte da estrutura do registro:** saem os arrays
  `$parameters` embutidos em 414 classes. Registro sem JSON no leiaute lança
  `RuntimeException` (antes a classe gravava o JSON em `storage/` em runtime).
- `vigencias.json` lista só leiautes que têm pasta: ICMS/IPI 017 (01/01/2023 a
  31/12/2023) e Contribuições 006.
- PHP 8.5 sem deprecations: parâmetros nullable explícitos, `EFD` sem
  propriedade dinâmica, `number_format` com cast e valor ausente lido como
  `null` sem aviso (`Common\Valores`).
- `F200` (Contribuições) recebe a vigência como os demais registros.

### Added
- `Common\Vigencia`: `leiautes()`, `carregar()` e `paraPeriodo()`, que escolhe
  o leiaute pela data inicial do período.
- Conferência do `COD_VER` do registro 0000 contra o leiaute dos blocos; vazio
  assume o leiaute.
- JSON dos 24 registros que só existiam embutidos na classe (ICMS/IPI C595 e
  C597; Contribuições C489 a C890, F200 e F550).
- `C595` e `C597` no `BlockC` do ICMS/IPI (as classes existiam, mas nenhum bloco
  as expunha).
- Testes estruturais por leiaute (bloco × classe × JSON; campos lidos na
  validação × JSON; nenhuma estrutura embutida), de totalizadores e de
  vigência. O PHPUnit falha em qualquer deprecation, notice ou warning.

### Fixed
Achados pelos testes estruturais novos:
- `Block1` do ICMS/IPI: `z1250` gerava o registro 1255 e `z1255` gerava o 1250.
- Validações que liam campo inexistente e por isso nunca disparavam:
  `C510` (`vl_br_icms` → `vl_bc_icms`, e mensagens de `ALIQ_ICMS`/`VL_ICMS`
  trocadas), `C800` (`chv_nfe` → `chv_cfe`) e `P100` de Contribuições
  (`vl_rec_total_est` → `vl_rec_tot_est`).
- `@method` dos blocos com nome de classe errado (`Elements\z1001`) e
  `@method` do `D761` apontando para `D760`.

### Removed
- `Common\Parser` (quebrado: dependia de `ForceUTF8`, fora das dependências).
- Pasta `storage/layouts/ICMSIPI/v018` (cópia da v017, nunca carregada).
- 23 referências a classes inexistentes nos blocos C e F da EFD Contribuições.
- `Element::replaceParams()`.

## [Fork Artemis] Onda 0 — 2026-09-12

Sem mudança em `src/`: a saída continua idêntica ao upstream `b9a874f`.

### Changed
- PHP mínimo 8.4; CI em 8.4 e 8.5.
- PHPUnit 12 e PHPStan 2 (nível 7, com baseline dos erros herdados).
- `Z0001Test` no namespace PSR-4 correto.

### Added
- Teste de caracterização (golden) com o cenário de `examples/ICMS_IPI/EFDICMS.php`.
- NT EFD ICMS/IPI 2025.001 e Guias Práticos 3.2.2 e 3.2.3 em `docs/leiautes/`.

### Removed
- `scrutinizer/ocular` e `sebastian/phpcpd` (abandonados) e o include quebrado de `phpstan-safe-rule`.
- `clover.xml`, `phpunit.xml.dist.bak` e `microsoft.gpg`, commitados por engano.

## 0.1.0-dev 

### Added
- Nothing

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
