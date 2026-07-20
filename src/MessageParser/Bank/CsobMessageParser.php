<?php

namespace KarelBartunek\BankEmailAvisoParser\MessageParser\Bank;

use KarelBartunek\BankEmailAvisoParser\Definition\Bank;
use KarelBartunek\BankEmailAvisoParser\Definition\Country;
use KarelBartunek\BankEmailAvisoParser\MessageParser\TextMessageParser;

class CsobMessageParser extends TextMessageParser
{
    public static function getFromAddresses(): array
    {
        return [
            'notification@csob.cz',
            'noreply@csob.cz',
        ];
    }

    public static function getBankId(): string
    {
        return Bank::CSOB;
    }

    public static function getCountry(): string
    {
        return Country::CZ;
    }

    protected function getRegexConfig(): array
    {
        return [
            'date' => "'dne ((\d{1,2}).(\d{1,2}).(\d{4})) byla na'is",
            'amount' => "'Částka: ([+-]{1})([0-9 .,]+) ([A-Z]{1,3})[\r\n]'si",
            'customerAccountNumber' => "'Účet protistrany: (.*?)[\r\n]'si",
            'customerName' => "'Název protistrany: (.*?)[\r\n]'si",
            'textMessage' => "'Zpráva příjemci: (.*?)[\r\n]'si",
            'variableSymbol' => "'Variabilní symbol: (.*?)[\r\n]'si",
            'constantSymbol' => "'Konstantn.*?(\d+).*$'si",
        ];
    }
}
