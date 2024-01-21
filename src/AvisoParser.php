<?php

namespace KarelBartunek\BankEmailAvisoParser;

use Exception;
use KarelBartunek\BankEmailAvisoParser\Entity\Aviso;
use KarelBartunek\BankEmailAvisoParser\Factory\MessageParserFactory;

class AvisoParser
{
    public function __invoke(string $rawContent): Aviso
    {
        try {
            $messageParser = MessageParserFactory::create($rawContent);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 1001);
        }

        if (is_null($messageParser)) {
            throw new Exception('Raw e-mail content is not valid!');
        }

        return $messageParser();
    }
}