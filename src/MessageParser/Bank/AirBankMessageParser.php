<?php

namespace KarelBartunek\BankEmailAvisoParser\MessageParser\Bank;

use KarelBartunek\BankEmailAvisoParser\Definition\Bank;
use KarelBartunek\BankEmailAvisoParser\Definition\Country;
use KarelBartunek\BankEmailAvisoParser\MessageParser\TextMessageParser;

class AirBankMessageParser extends TextMessageParser
{
    public static function getFromAddresses(): array
    {
        return [
            'info@airbank.cz',
        ];
    }

    public static function getBankId(): string
    {
        return Bank::AIR_BANK;
    }

    public static function getCountry(): string
    {
        return Country::CZ;
    }

    protected function getRegexConfig(): array
    {
        return [
            'date' => "'Datum zaúčtování: ((\d{1,2}).(\d{1,2}).(\d{4}))'is",
            'amount' => "'Částka: ([+-]?)([0-9 .,]+) ([A-Z]{1,3})[\r\n]'si",
            'customerAccountNumber' => "'úhrada z účtu .*? číslo ([\d-]+/\d+)[\r\n]'si",
            'customerName' => "'úhrada z účtu (.*?) číslo [\d-]+/\d+[\r\n]'si",
            'textMessage' => "'Zpráva pro příjemce: (.*?)[\r\n]'si",
            'variableSymbol' => "'Variabilní symbol: (.*?)[\r\n]'si",
            'constantSymbol' => "'Konstantní symbol: (\d+)'si",
        ];
    }
}
