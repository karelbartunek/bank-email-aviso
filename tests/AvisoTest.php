<?php

use KarelBartunek\BankEmailAvisoParser\AvisoParser;
use PHPUnit\Framework\TestCase;

final class AvisoTest extends TestCase
{
    public function testBankCsob(): void
    {
        $rawContent = file_get_contents(__DIR__ . '/resources/CZ/csob.txt');

        $avisoParser = new AvisoParser();
        $aviso = $avisoParser($rawContent);

        $this->assertEquals(
            DateTimeImmutable::createFromFormat('d.m.Y h:i:s', '14.10.2022 00:00:00'),
            $aviso->getDate()
        );

        $this->assertEquals(
            1500.5,
            $aviso->getAmount()
        );

        $this->assertEquals(
            'CZK',
            $aviso->getCurrency()
        );

        $this->assertEquals(
            '123456789/0100',
            $aviso->getCustomerAccountNumber()
        );

        $this->assertEquals(
            'Irrelevant Text Message',
            $aviso->getTextMessage()
        );

        $this->assertEquals(
            'TEST CUSTOMER',
            $aviso->getCustomerName()
        );

        $this->assertEquals(
            '987654321',
            $aviso->getVariableSymbol()
        );
    }

    public function testBankCsob1(): void
    {
        $rawContent = file_get_contents(__DIR__ . '/resources/CZ/csob_1.txt');

        $avisoParser = new AvisoParser();
        $aviso = $avisoParser($rawContent);

        $this->assertEquals(
            DateTimeImmutable::createFromFormat('d.m.Y h:i:s', '14.10.2022 00:00:00'),
            $aviso->getDate()
        );

        $this->assertEquals(
            999,
            $aviso->getAmount()
        );

        $this->assertEquals(
            'CZK',
            $aviso->getCurrency()
        );

        $this->assertEquals(
            '62100-123456789/0100',
            $aviso->getCustomerAccountNumber()
        );

        $this->assertEquals(
            'Irrelevant Text Message',
            $aviso->getTextMessage()
        );

        $this->assertEquals(
            'TEST CUSTOMER',
            $aviso->getCustomerName()
        );

        $this->assertEquals(
            '004321',
            $aviso->getVariableSymbol()
        );
    }

    public function testBankCsob2(): void
    {
        $rawContent = file_get_contents(__DIR__ . '/resources/CZ/csob_2.txt');

        $avisoParser = new AvisoParser();
        $aviso = $avisoParser($rawContent);

        $this->assertEquals(
            DateTimeImmutable::createFromFormat('d.m.Y h:i:s', '28.01.2023 00:00:00'),
            $aviso->getDate()
        );

        $this->assertEquals(
            85,
            $aviso->getAmount()
        );

        $this->assertEquals(
            'CZK',
            $aviso->getCurrency()
        );

        $this->assertEquals(
            '',
            $aviso->getCustomerAccountNumber()
        );

        $this->assertEquals(
            'Za vedení účtu a výpisu: 80,00',
            $aviso->getTextMessage()
        );

        $this->assertEquals(
            'Service Fees',
            $aviso->getCustomerName()
        );

        $this->assertEquals(
            '',
            $aviso->getVariableSymbol()
        );

        $this->assertEquals(
            '',
            $aviso->getConstantSymbol()
        );
    }
}
