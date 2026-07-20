# Bank Email Aviso Parser

[![CI](https://github.com/karelbartunek/bank-email-aviso/actions/workflows/ci.yml/badge.svg)](https://github.com/karelbartunek/bank-email-aviso/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/packagist/v/karelbartunek/bank-email-aviso)](https://packagist.org/packages/karelbartunek/bank-email-aviso)
[![Total Downloads](https://img.shields.io/packagist/dt/karelbartunek/bank-email-aviso)](https://packagist.org/packages/karelbartunek/bank-email-aviso)
[![License](https://img.shields.io/packagist/l/karelbartunek/bank-email-aviso)](LICENSE)

Parses bank payment-notification emails ("avisos") into a structured PHP object.
Feed it the raw MIME content of a notification email sent by a supported bank and
it extracts the payment date, amount, currency, counterparty account and name,
variable/constant symbols and the message for the recipient.

## Supported banks

| Bank     | Country | Email type |
|----------|---------|------------|
| ČSOB     | CZ      | plain text |
| Air Bank | CZ      | plain text |

Only plain-text notification emails are supported — HTML-only emails are rejected
by design.

## Requirements

- PHP 8.4+
- `ext-mailparse`
- `ext-mbstring`

## Installation

```bash
composer require karelbartunek/bank-email-aviso
```

## Usage

```php
use KarelBartunek\BankEmailAvisoParser\AvisoParser;
use KarelBartunek\BankEmailAvisoParser\Exception\Exception;

$rawEmailContent = file_get_contents('notification.eml'); // full raw MIME email

try {
    $aviso = (new AvisoParser())($rawEmailContent);
} catch (Exception $e) {
    if ($e->getCode() === 1001) {
        // Email was not recognized as a supported bank notification
        // (unknown sender, HTML body, missing headers, ...)
    }
    throw $e;
}

$aviso->getDate();                  // DateTimeImmutable — payment date
$aviso->getAmount();                // float   — e.g. 1500.50
$aviso->getCurrency();              // string  — e.g. "CZK"
$aviso->getCustomerAccountNumber(); // string  — counterparty account, e.g. "123456789/0100"
$aviso->getCustomerName();          // ?string — counterparty name
$aviso->getVariableSymbol();        // ?string
$aviso->getConstantSymbol();        // ?string
$aviso->getTextMessage();           // ?string — message for the recipient
$aviso->getSubject();               // string  — email subject
$aviso->getFrom();                  // string  — sender address
$aviso->getTo();                    // string  — recipient address
$aviso->getMessageId();             // ?string
$aviso->getReturnPath();            // string
```

Any email that cannot be matched to a supported bank throws
`KarelBartunek\BankEmailAvisoParser\Exception\Exception` with code `1001`,
so callers can distinguish "not a supported aviso email" from an actual
parsing bug (`ParserException`).

## Adding a new bank

1. Create a parser in `src/MessageParser/Bank/` extending `TextMessageParser`.
   It supplies the bank's sender addresses (`getFromAddresses()`), `Bank` and
   `Country` identifiers, and seven field regexes via `getRegexConfig()`
   (`date`, `amount`, `customerAccountNumber`, `customerName`, `textMessage`,
   `variableSymbol`, `constantSymbol` — all keys are required even if the
   bank's emails never contain the field). The `amount` regex must capture the
   value in group 2 and the currency in group 3. The `parse*` methods are
   `protected`, so a bank with an atypical format can override them.
2. Register the class in `MessageParserFactory::PARSERS` and add the
   `Bank`/`Country` constants if needed.
3. Add an anonymized sample email to `tests/resources/` and a test to
   `tests/AvisoTest.php`.

## Development

The test suite needs `ext-mailparse`, so it runs inside Docker:

```bash
make up               # build & start the PHP 8.4 container
make composer-install
make phpunit          # run tests
make phpstan          # static analysis (level 10)
make phpcs            # coding standard (PSR-12)
```

## License

Released under the [MIT License](LICENSE).
