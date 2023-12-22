<?php

namespace KarelBartunek\BankEmailAvisoParser\Factory;

use KarelBartunek\BankEmailAvisoParser\Definition\Map;
use KarelBartunek\BankEmailAvisoParser\MessageParser\MessageParser;
use PhpMimeMailParser\Parser;

class MessageParserFactory
{
    public static function create(string $rawContent): ?MessageParser
    {
        $parser = new Parser();
        $parser->setText($rawContent);

        $parts = $parser->getParts();
        if (!isset($parts[1]['headers']['return-path']) || !isset($parts[1]['headers']['content-transfer-encoding'])) {
            return null;
        }

        $bodyMessage = $parser->getMessageBody();
        $to = $parser->getHeader('to');
        $from = $parser->getHeader('from');
        $subject = $parser->getHeader('subject');

        $config = Map::findByFrom($from);

        return new MessageParser($config, $bodyMessage, $from, $to, $subject);
    }
}