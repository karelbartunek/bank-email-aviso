<?php

namespace KarelBartunek\BankEmailAvisoParser\MessageParser;

use Brick\Money\Money;
use DateTimeImmutable;
use KarelBartunek\BankEmailAvisoParser\Entity\Aviso;
use KarelBartunek\BankEmailAvisoParser\Exception\ParserException;

abstract class TextMessageParser
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
        $this->config = $this->getRegexConfig();

        $emailBody = preg_replace('/\xc2\xa0/', ' ', $emailBody);

        $this->emailBody = quoted_printable_decode($emailBody);
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
    }

    /**
     * E-mail addresses the bank sends notifications from.
     *
     * @return string[]
     */
    abstract public static function getFromAddresses(): array;

    abstract public static function getBankId(): string;

    abstract public static function getCountry(): string;

    /**
     * Regex for every field: date, amount, customerAccountNumber,
     * customerName, textMessage, variableSymbol, constantSymbol.
     * The amount regex must capture value in group 2 and currency in group 3.
     */
    abstract protected function getRegexConfig(): array;

    public static function supports(string $from): bool
    {
        foreach (static::getFromAddresses() as $address) {
            if (strpos($from, $address) !== false) {
                return true;
            }
        }

        return false;
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

    protected function parseDate(string $emailBody): DateTimeImmutable
    {
        preg_match($this->config['date'], $emailBody, $match);

        if (isset($match[1])) {
            try {
                return new DateTimeImmutable($match[1]);
            } catch (ParserException $e) {}
        }

        throw new ParserException('Parsing error');
    }

    protected function parseMoney(string $emailBody): Money
    {
        preg_match($this->config['amount'], $emailBody, $match);

        $sanitizedValue = str_replace(',', '.', str_replace(['.', ' '], '', $match[2]));

        return Money::of($sanitizedValue, $match[3]);
    }

    protected function parseCustomerAccountNumber(string $emailBody): string
    {
        preg_match($this->config['customerAccountNumber'], $emailBody, $match);

        if (empty($match)) {
            return '';
        }

        return $match[1];
    }

    protected function parseTextMessage(string $emailBody): ?string
    {
        preg_match($this->config['textMessage'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }

    protected function parseCustomerName(string $emailBody): ?string
    {
        preg_match($this->config['customerName'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }

    protected function parseVariableSymbol(string $emailBody): ?string
    {
        preg_match($this->config['variableSymbol'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }

    protected function parseConstantSymbol(string $emailBody): ?string
    {
        preg_match($this->config['constantSymbol'], $emailBody, $match);

        if (!isset($match[1])) {
            return null;
        }

        return trim($match[1]);
    }
}
