<?php

namespace KarelBartunek\BankEmailAvisoParser\MessageParser;

use Brick\Money\Money;
use DateTimeImmutable;
use KarelBartunek\BankEmailAvisoParser\Definition\Map;
use KarelBartunek\BankEmailAvisoParser\Entity\Aviso;
use KarelBartunek\BankEmailAvisoParser\Exception\ParserException;

class TextMessageParser
{
    private array $config;

    private string $emailBody;
    private string $from;
    private string $to;
    private string $subject;

    public function __construct(
        string $emailBody,
        string $from,
        string $to,
        string $subject
    ) {
        $this->config = Map::findByFrom($from);

        $emailBody = preg_replace('/\xc2\xa0/', ' ', $emailBody);

        $this->emailBody = quoted_printable_decode($emailBody);
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
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
            ->setVariableSymbol($this->parseVariableSymbol($this->emailBody))
            ->setConstantSymbol($this->parseConstantSymbol($this->emailBody))
            ->setSubject($this->subject)
            ->setFrom($this->from)
            ->setTo($this->to);
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

        if (empty($match)) {
            return '';
        }

        return $match[1];
    }

    public function parseTextMessage(string $emailBody): ?string
    {
        preg_match($this->config['regex']['cs']['textMessage'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }

    public function parseCustomerName(string $emailBody): ?string
    {
        preg_match($this->config['regex']['cs']['customerName'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }

    public function parseVariableSymbol(string $emailBody): ?string
    {
        preg_match($this->config['regex']['cs']['variableSymbol'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }

    private function parseConstantSymbol(string $emailBody): ?string
    {
        preg_match($this->config['regex']['cs']['constantSymbol'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }
}