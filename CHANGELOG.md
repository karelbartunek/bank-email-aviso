# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Air Bank (CZ) support (`AirBankMessageParser`); parsing refactored into per-bank parser classes
- Package metadata (description, MIT license, keywords), `.gitattributes`, `phpunit.xml.dist`, GitHub Actions CI
- QA tooling: PHPStan level 10, PHP_CodeSniffer (PSR-12), dead-code detector, composer audit

### Changed

- Requires PHP 8.4

## [v0.2.4-alpha] - 2024-02-11

Last alpha release with ČSOB (CZ) support only. See git history for details of earlier releases.

[Unreleased]: https://github.com/karelbartunek/bank-email-aviso/compare/v0.2.4-alpha...HEAD
[v0.2.4-alpha]: https://github.com/karelbartunek/bank-email-aviso/releases/tag/v0.2.4-alpha
