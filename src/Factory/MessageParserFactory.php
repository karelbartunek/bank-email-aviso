<?php

namespace KarelBartunek\BankEmailAvisoParser\Factory;

use KarelBartunek\BankEmailAvisoParser\MessageParser\Bank\AirBankMessageParser;
use KarelBartunek\BankEmailAvisoParser\MessageParser\Bank\CsobMessageParser;
use KarelBartunek\BankEmailAvisoParser\MessageParser\TextMessageParser;
use PhpMimeMailParser\Parser;

class MessageParserFactory
{
    /** @var class-string<TextMessageParser>[] */
    private const array PARSERS = [
        CsobMessageParser::class,
        AirBankMessageParser::class,
    ];

    public static function create(string $rawContent): ?TextMessageParser
    {
        $parser = new Parser();
        $parser->setText($rawContent);

        $parts = $parser->getParts();
        if (!isset($parts[1]['headers']['return-path']) || !isset($parts[1]['headers']['content-transfer-encoding'])) {
            return null;
        }
        $to = $parser->getHeader('to');
        $from = $parser->getHeader('from');
        $subject = $parser->getHeader('subject');

        $bodyMessage = $parser->getMessageBody('text');
        $htmlBodyMessage = $parser->getMessageBody('html');

        if (!empty($htmlBodyMessage)) {
            throw new \Exception('HTML Parser is not implemented.');
        }

        foreach (self::PARSERS as $parserClass) {
            if ($parserClass::supports($from)) {
                return new $parserClass($bodyMessage, $from, $to, $subject);
            }
        }

        throw new \Exception(sprintf('Unknown bank sender "%s".', $from));
    }
}
