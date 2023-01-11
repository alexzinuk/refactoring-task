<?php

declare(strict_types=1);

namespace RefactoringTask\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RefactoringTask\Common\Money;
use RefactoringTask\Exception\CurrencyConvertException;
use RefactoringTask\Service\CurrencyConverter;
use RefactoringTask\Service\External\ExchangeRateApi;

class CurrencyConverterTest extends TestCase
{
    public function testNoNeedToConvert(): void
    {
        $converter = new CurrencyConverter(
            $this->createMock(ExchangeRateApi::class)
        );
        $money = new Money(1, Money::CURRENCY_EUR);

        $result = $converter->convert($money, Money::CURRENCY_EUR);

        $this->assertSame($money, $result);
    }

    public function testSuccessfulRateConversion(): void
    {
        $targetCurrency = 'USD';
        $targetCurrencyExchangeRate = 1.073434;
        $convertingAmount = 1;
        $money = new Money($convertingAmount, Money::CURRENCY_EUR);

        $api = $this->createMock(ExchangeRateApi::class);
        $api
            ->method('request')
            ->willReturn(['rates' => [$targetCurrency => $targetCurrencyExchangeRate]]);

        $converter = new CurrencyConverter($api);

        $result = $converter->convert($money, $targetCurrency);
        $neededResult = new Money($convertingAmount / $targetCurrencyExchangeRate, $targetCurrency);

        $this->assertEquals($neededResult, $result);
    }

    public function testConversionRateNotFoundException(): void
    {
        $targetCurrency = 'USD';
        $money = new Money(1, Money::CURRENCY_EUR);

        $api = $this->createMock(ExchangeRateApi::class);
        $api
            ->method('request')
            ->willReturn(['rates' => [Money::CURRENCY_EUR => 1]]);

        $converter = new CurrencyConverter($api);

        $this->expectException(CurrencyConvertException::class);
        $converter->convert($money, $targetCurrency);
    }

    public function testConversionRateIsZero(): void
    {
        $targetCurrency = 'USD';
        $money = new Money(1, Money::CURRENCY_EUR);

        $api = $this->createMock(ExchangeRateApi::class);
        $api
            ->method('request')
            ->willReturn(['rates' => [$targetCurrency => 0]]);

        $converter = new CurrencyConverter($api);

        $this->expectException(CurrencyConvertException::class);
        $converter->convert($money, $targetCurrency);
    }
}