<?php

namespace KarelBartunek\BankEmailAvisoParser\Factory;

use KarelBartunek\BankEmailAvisoParser\Definition\Map;
use KarelBartunek\BankEmailAvisoParser\MessageParser\MessageParser;
use PhpMimeMailParser\Parser;

class MessageParserFactory
{
    public static function create(string $rawContent): MessageParser
    {
        $parser = new Parser();
        $parser->setText($rawContent);

        $bodyMessage = $parser->getMessageBody();

        $from = $parser->getHeader('from');
        $subject = $parser->getHeader('subject');

        $config = Map::findByFrom($from);

        return new MessageParser($config, $bodyMessage, $from, $subject);
    }
}