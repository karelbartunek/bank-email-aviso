<?php

use KarelBartunek\BankEmailAvisoParser\AvisoParser;
use PHPUnit\Framework\TestCase;

final class AvisoTest extends TestCase
{
    public function testBankCsob(): void
    {
        $rawContent = file_get_contents(__DIR__ . '/resources/CZ/csob_0.txt');

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
            null,
            $aviso->getVariableSymbol()
        );

        $this->assertEquals(
            '0898',
            $aviso->getConstantSymbol()
        );
    }

    public function testBankCsob3(): void
    {
        $rawContent = file_get_contents(__DIR__ . '/resources/CZ/csob_3.txt');

        $avisoParser = new AvisoParser();
        $aviso = $avisoParser($rawContent);

        $this->assertEquals(
            DateTimeImmutable::createFromFormat('d.m.Y h:i:s', '21.12.2023 00:00:00'),
            $aviso->getDate()
        );

        $this->assertEquals(
            3499,
            $aviso->getAmount()
        );

        $this->assertEquals(
            'CZK',
            $aviso->getCurrency()
        );

        $this->assertEquals(
            '1234567890/0800',
            $aviso->getCustomerAccountNumber()
        );

        $this->assertEquals(
            'Darkove poukazy',
            $aviso->getTextMessage()
        );

        $this->assertEquals(
            'Nováková Petúnie',
            $aviso->getCustomerName()
        );

        $this->assertEquals(
            '999230001',
            $aviso->getVariableSymbol()
        );

        $this->assertEquals(
            null,
            $aviso->getConstantSymbol()
        );

        $this->assertEquals(
            'CEB Info: Zaúčtování platby',
            $aviso->getSubject()
        );
    }
}
