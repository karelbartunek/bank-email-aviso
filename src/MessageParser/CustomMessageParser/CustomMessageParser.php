<?php

namespace KarelBartunek\BankEmailAvisoParser\MessageParser\CustomMessageParser;

use DateTimeImmutable;
use KarelBartunek\BankEmailAvisoParser\Entity\Aviso;

interface CustomMessageParser
{
    public function parseDate(string $emailBody): DateTimeImmutable;
    public function parseAmount(string $emailBody): string;
}