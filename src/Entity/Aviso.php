<?php

namespace KarelBartunek\BankEmailAvisoParser\Entity;

use DateTimeImmutable;

class Aviso
{
    private float $amount;
    private string $currency;
    private string $customerAccountNumber;
    private ?string $customerName;
    private DateTimeImmutable $date;
    private ?string $messageId = null;
    private ?string $textMessage;
    private string $returnPath;
    private string $to;
    private ?string $variableSymbol;
    private ?string $constantSymbol;
    private string $subject;
    private string $from;


    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Aviso
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCustomerAccountNumber(): string
    {
        return $this->customerAccountNumber;
    }

    public function setCustomerAccountNumber(string $customerAccountNumber): Aviso
    {
        $this->customerAccountNumber = $customerAccountNumber;
        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): Aviso
    {
        $this->customerName = $customerName;
        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): Aviso
    {
        $this->date = $date;
        return $this;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setFrom(string $from): Aviso
    {
        $this->from = $from;
        return $this;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function setMessageId(?string $messageId): Aviso
    {
        $this->messageId = $messageId;
        return $this;
    }

    public function getReturnPath(): string
    {
        return $this->returnPath;
    }

    public function setReturnPath(string $returnPath): Aviso
    {
        $this->returnPath = $returnPath;
        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): Aviso
    {
        $this->to = $to;
        return $this;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function setVariableSymbol(?string $variableSymbol): Aviso
    {
        $this->variableSymbol = $variableSymbol;
        return $this;
    }

    public function getConstantSymbol(): ?string
    {
        return $this->constantSymbol;
    }

    public function setConstantSymbol(?string $constantSymbol): Aviso
    {
        $this->constantSymbol = $constantSymbol;
        return $this;
    }

    public function setCurrency(string $currency): Aviso
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTextMessage(): ?string
    {
        return $this->textMessage;
    }

    public function setTextMessage(?string $textMessage): Aviso
    {
        $this->textMessage = $textMessage;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Aviso
    {
        $this->subject = $subject;
        return $this;
    }
}