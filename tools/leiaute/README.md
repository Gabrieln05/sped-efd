# Ferramentas de leiaute (EFD ICMS/IPI)

Geram e conferem as pastas `storage/layouts/ICMSIPI/vNNN` a partir dos PDFs
oficiais. A estrutura dos registros não é digitada à mão: sai de uma
referência extraída da Nota Técnica e do Guia Prático, mais um arquivo de
decisões manuais para o que o PDF não resolve sozinho.

## Fluxo

```bash
# 1. texto dos PDFs (poppler-utils)
pdftotext -enc UTF-8 -layout docs/leiautes/NT_EFD_ICMS_IPI_2025.001_v1.0_leiaute_020.pdf /tmp/nt.txt
pdftotext -enc UTF-8 -layout docs/leiautes/Guia_Pratico_EFD_ICMS_IPI_3.2.2.pdf /tmp/guia.txt

# 2. referência do leiaute (campos, tipo, tamanho, decimais, valores válidos)
php tools/leiaute/extrair_referencia.php 020 /tmp/nt.txt /tmp/guia.txt docs/leiautes/referencia/020.json
#    tabela que não fecha: ver o texto anotado de um registro
php tools/leiaute/extrair_referencia.php 020 /tmp/nt.txt /tmp/guia.txt --depurar=C185

# 3. pasta do leiaute, partindo do leiaute anterior
php tools/leiaute/gerar_leiaute.php docs/leiautes/referencia/020.json \
    storage/layouts/ICMSIPI/v017 storage/layouts/ICMSIPI/v020 tools/leiaute/ajustes/020.json /tmp/geracao.md

# 4. conferência (tem de dar zero divergências)
php tools/leiaute/comparar.php docs/leiautes/referencia/020.json storage/layouts/ICMSIPI/v020 \
    /tmp/comparacao.md --ajustes=tools/leiaute/ajustes/020.json
```

O passo 4 também roda na suíte (`tests/Leiautes/ReferenciaOficialTest.php`)
para todo leiaute com referência em `docs/leiautes/referencia/`.

## Como a extração funciona

Em parte das tabelas o PDF sai embaralhado: a descrição fica fora do lugar,
mas os nomes dos campos e as trincas Tipo/Tam/Dec continuam na ordem. O
extrator pareia por posição e só aceita o registro quando a numeração é
contínua e a quantidade de trincas bate. NT e Guia são lidos em separado; se
os dois fecham, as trincas são comparadas e as diferenças vão para
`divergencias` (vale a NT).

Armadilhas já tratadas: nome cortado na coluna (vale o mais longo), nome
quebrado na linha de baixo, nome com acento ou asterisco de nota, coluna Dec
vazia, "Nível hierárquico" antes das últimas trincas, colunas de
obrigatoriedade depois da trinca.

## O que o gerador corrige no JSON-base

Mantém a definição existente do campo (regex, `info`, `required`) e corrige só
o que diverge do leiaute: `type` inválido, campo C gravado como número, casas
decimais do `format`, regex quebrada ou de tamanho errado, regex que recusa
valor válido. Campo novo nasce da referência com `required: false`.

## ajustes/NNN.json

- `referencia`: lista de campos de registro que o extrator não leu (sem o `01 REG`).
- `sem_auditoria`: registro copiado do leiaute-base sem conferência, com o motivo.
- `campos.REG.NN.ref`: corrige a referência antes de gerar (nome, tipo, tam, fixo, dec, valores).
- `campos.REG.NN.json`: sobrescreve a definição gerada (ex.: `required`).
- Toda decisão leva `_fonte`: de onde veio (NT, Guia, histórico de versões).

Obrigatoriedade não é extraída (a coluna sai ilegível no Guia): fica a do
leiaute-base, e as mudanças documentadas no histórico do Guia entram como
ajuste.
