<?php

namespace KarelBartunek\BankEmailAvisoParser\MessageParser;

use Brick\Money\Money;
use DateTimeImmutable;
use KarelBartunek\BankEmailAvisoParser\Entity\Aviso;
use KarelBartunek\BankEmailAvisoParser\Exception\ParserException;

class MessageParser
{
    private array $config;
    private string $emailBody;

    public function __construct(array $config, string $emailBody)
    {
        $this->config = $config;

        $emailBody = preg_replace('/\xc2\xa0/', ' ', $emailBody);

        $this->emailBody = mb_convert_encoding($emailBody, 'UTF-8');
    }

    public function __invoke(): Aviso
    {
        $money = $this->parseMoney($this->emailBody);

        return (new Aviso())
            ->setDate($this->parseDate($this->emailBody))
            ->setAmount($money->getAmount()->toFloat())
            ->setCurrency($money->getCurrency()->getCurrencyCode())
            ->setCustomerAccountNumber($this->parseCustomerAccountNumber($this->emailBody))
            ->setTextMessage($this->parseTextMessage($this->emailBody))
            ->setCustomerName($this->parseCustomerName($this->emailBody))
            ->setVariableSymbol($this->parseVariableSymbol($this->emailBody));
    }

    public function parseDate(string $emailBody): DateTimeImmutable
    {
        preg_match($this->config['regex']['cs']['date'], $emailBody, $match);

        if (isset($match[1])) {
            try {
                return new DateTimeImmutable($match[1]);
            } catch (ParserException $e) {}
        }

        throw new ParserException('Parsing error');
    }

    public function parseMoney(string $emailBody): Money
    {
        preg_match($this->config['regex']['cs']['amount'], $emailBody, $match);

        $sanitizedValue = str_replace(',', '.', str_replace(['.', ' '], '', $match[2]));

        return Money::of($sanitizedValue, $match[3]);
    }

    public function parseCustomerAccountNumber(string $emailBody): string
    {
        preg_match($this->config['regex']['cs']['customerAccountNumber'], $emailBody, $match);

        return $match[1];
    }

    public function parseTextMessage(string $emailBody): string
    {
        preg_match($this->config['regex']['cs']['textMessage'], $emailBody, $match);

        return trim($match[1]);
    }

    public function parseCustomerName(string $emailBody): string
    {
        preg_match($this->config['regex']['cs']['customerName'], $emailBody, $match);

        return trim($match[1]);
    }

    public function parseVariableSymbol(string $emailBody): string
    {
        preg_match($this->config['regex']['cs']['variableSymbol'], $emailBody, $match);

        return trim($match[1]);
    }
}