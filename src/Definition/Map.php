<?php

namespace KarelBartunek\BankEmailAvisoParser\Definition;

class Map
{
    private const MAP = [
        [
            'id' => Bank::CSOB,
            'country' => Country::CZ,
            'receivedFrom' => 'mm2b2.csob.cz',
            'from' => 'notification@csob.cz',
            'regex' => [
                'cs' => [
                    'date' => "'dne ((\d{1,2}).(\d{1,2}).(\d{4})) byla na'is",
                    'amount' => "'Částka: ([+-]{1})([0-9 .,]+) ([A-Z]{1,3})[\r\n]'si",
                    'customerAccountNumber' => "'Účet protistrany: (.*?)[\r\n]'si",
                    'customerName' => "'Název protistrany: (.*?)[\r\n]'si",
                    'textMessage' => "'Zpráva příjemci: (.*?)[\r\n]'si",
                    'variableSymbol' => "'Variabilní symbol: (.*?)[\r\n]'si",
                    'constantSymbol' => "'Konstantni symbol: (.*?)[\r\n]'si",
                ]
            ]
        ]
    ];

    public static function findByFrom(string $from): ?array
    {
        $key = array_search($from, array_column(self::MAP, 'from'));
        return self::MAP[$key];
    }
}