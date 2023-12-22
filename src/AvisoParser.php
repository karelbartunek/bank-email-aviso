<?php

namespace KarelBartunek\BankEmailAvisoParser;

use KarelBartunek\BankEmailAvisoParser\Entity\Aviso;
use KarelBartunek\BankEmailAvisoParser\Factory\MessageParserFactory;

class AvisoParser
{
    public function __invoke(string $rawContent): Aviso
    {
        $messageParser = MessageParserFactory::create($rawContent);

        if (is_null($messageParser)) {
            throw new \Exception('Raw e-mail content is not valid!');
        }

        return $messageParser();
    }
}