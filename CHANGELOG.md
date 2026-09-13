# Changelog

All Notable changes to `sped-efd` will be documented in this file.

Todas as atualizações a partir de 30/05/206 devem observar os principios [Mantendo o CHANGELOG](http://keepachangelog.com/).

## [Fork Artemis] Onda 5 — 2026-09-13

Leiaute 021 da EFD ICMS/IPI (a partir de 01/01/2027, Ato COTEPE/ICMS 69/2026).

### Added
- `storage/layouts/ICMSIPI/v021` e o 021 no `vigencias.json`: início em
  01/01/2027, sem fim, versão 1.20 (presumida).
- Derivado do 020 pelas mudanças que o Guia Prático 3.2.3 registra
  (`tools/leiaute/deltas/021.json`, cada uma com a fonte), porque o texto da
  NT 2026.001 não foi localizado. A lista foi cruzada com a extração do próprio
  Guia 3.2.3: as diferenças de tipo que sobram são divergências NT × Guia já
  conhecidas no 020, o 1320 e ruído de extração do E310.
  - CNPJ vira C 14 alfanumérico (`^[0-9A-Z]{12}[0-9]{2}$`) em 0000, 0100, 0150,
    C115, C175 e 1320. Os campos CNPJ/CPF combinados de C350, C800, D160 e D180
    aceitam CNPJ alfanumérico ou CPF.
  - 25 campos de chave de documento viram C 44, com letras só nas posições do CNPJ.
  - `IND_BENEFICIO` (0, 1, 2, 3, 9) no fim de C197, C597, D197, D737, E111, E220
    e 1921; `COD_PROD` no C180; `COD_SIT` 09 e 10 no C100.
- `Common\ChaveAcesso::valida()`: chave só com dígitos segue o dígito
  verificador da sped-common; chave com CNPJ alfanumérico é conferida no
  formato. As validações de chave dos registros (ICMS/IPI e Contribuições)
  passam a usá-la.
- Ferramentas: o delta aceita `incluir_campos` e `regex` definida pelo
  leiaute, e gerador e comparador respeitam essa regex.
- Arquivo de teste do 021 (`tests/fixtures/pva/efd-icms-ipi-021.txt`) e teste
  linha a linha dos campos novos, inclusive CNPJ alfanumérico recusado no 020.

### Pendente
- Texto oficial da NT 2026.001, para conferir a derivação.
- Dígito verificador da chave com CNPJ alfanumérico.
- Validação no PVA do leiaute 2027, quando for publicado.

## [Fork Artemis] Onda 3 — 2026-09-13

### Added
- Leiautes **018** (2024) e **019** (2025), derivados do 020 auditado por
  `tools/leiaute/derivar_referencia.php`, desfazendo só as mudanças
  documentadas (`tools/leiaute/deltas/`, cada uma com a fonte):
  - 019: sem `CAP_TANQUE` no 1310 e sem o valor 2 (DUIMP) no C120;
  - 018: sem `DED` no D700 e no D750, com `FIN_DOCe`/`TIP_FAT` opcionais e
    `VL_PIS`/`VL_COFINS` do D750 obrigatórios.
- Referências `docs/leiautes/referencia/018.json` e `019.json`, conferidas na
  suíte como a do 020.
- Arquivo para o PVA de cada leiaute (`tests/fixtures/pva/efd-icms-ipi-NNN.txt`);
  no 018 e no 019 a importação usa DI.

### Removed
- Leiaute 017 (pasta e vigência): não auditado e contaminado com campos de
  leiautes posteriores. `Vigencia::paraPeriodo()` recusa períodos anteriores a 2024.
- `examples/cria_lista_layout_vigencia.php`, que regravava o `vigencias.json`
  com a lista antiga.

### Changed
- O golden do exemplo do upstream passou a usar o 020. O TXT é o mesmo; os
  erros ganharam `H001.IND_MOV` e `H010.IND_PROP` recusando inteiro, porque o
  020 corrigiu esses campos para o tipo C.

## [Fork Artemis] Onda 2 — 2026-09-13

Leiaute 020 da EFD ICMS/IPI (períodos de 01/01/2026 a 31/12/2026), gerado a
partir da NT 2025.001 v1.0 e do Guia Prático 3.2.2, não por cópia do 017.

### Added
- `storage/layouts/ICMSIPI/v020` (257 registros) e o 020 no `vigencias.json`.
- Ferramentas em `tools/leiaute/`: extrator da referência a partir do texto
  dos PDFs, gerador da pasta do leiaute e comparador campo a campo (ver o
  README de lá). Referência versionada em `docs/leiautes/referencia/020.json`
  e decisões manuais, cada uma com a fonte, em `tools/leiaute/ajustes/020.json`.
- Registros que existem no leiaute e faltavam na biblioteca: `C181`, `C186`,
  `C855`, `C857`, `C895`, `C897`, `D731`, `D735`, `D737` (classe, entrada no
  bloco e JSON no 020 e no 017).
- Teste que confere os JSONs de cada leiaute auditado contra a referência
  oficial; teste linha a linha dos registros que mudaram; arquivo completo do
  020 para validar no PVA (`tests/fixtures/pva/efd-icms-ipi-020.txt`).

### Fixed (no 020, em relação ao que vinha no 017)
- Campos faltando: `CAP_TANQUE` (1310), campos 18 a 23 do 1391,
  `VL_UNIT_CONV` (C185, C330, C380, C430, C480, C815, C880).
- Casas decimais que mudavam o arquivo gerado: alíquotas e valores com
  `format` vazio (sairiam com ponto), unitários do C176/C180/C185/H030 e
  quantidades do bloco K com 6 decimais, `QUANT_BC_PIS` com 3, `DED` e
  `VL_TERC`/`VL_DA` do D700/D750.
- Regex que só aceitava vazio (`UNID` de C180/C380/C430/C480/C880, `COD_DA`,
  `NUM_DA`, `IND_EMIT` do D180), tamanhos (`NUM_PROC` 60, `COD_ANT_ITEM` 60) e
  valores válidos (C105 `OPER` 2, C120 `COD_DOC_IMP` 2 — DUIMP, D700 `COD_MOD` 62,
  H005 `MOT_INV` 06, entre outros).
- Campo C gravado como número (`COD_INF`, `CHV_COD_DIG`, `IND_MOV` dos blocos) e
  `C112.NUM_DA` com a descrição no lugar do tipo.
- Ordem dos campos do E313; obrigatoriedade de SER, CHV_DOCe, FIN_DOCe e
  TIP_FAT no D700 (históricos 018 e 019).

### Pendente
- Validação do arquivo de teste no PVA da EFD ICMS/IPI.
- `B035` sem auditoria (tabela ilegível nos dois PDFs; estrutura do 017).

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
