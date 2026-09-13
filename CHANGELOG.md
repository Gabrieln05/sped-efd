# Changelog

All Notable changes to `sped-efd` will be documented in this file.

Todas as atualizações a partir de 30/05/206 devem observar os principios [Mantendo o CHANGELOG](http://keepachangelog.com/).

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
