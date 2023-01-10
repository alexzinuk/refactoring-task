<?php

declare(strict_types = 1);

namespace RefactoringTask\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RefactoringTask\Common\Money;
use RefactoringTask\Entity\Transaction;
use RefactoringTask\Service\BinChecker;
use RefactoringTask\Service\CommissionCalculator;
use RefactoringTask\Service\CurrencyConverter;

class CommissionCalculatorTest extends TestCase
{
    /**
     * @dataProvider isEuIssuedCardDataProvider
     *
     * @param bool $isEuIssuedCard
     * @param float $transactionAmount
     * @param float $expectedCommission
     *
     * @return void
     */
    public function testSuccessfulCalculationCommission(
        bool  $isEuIssuedCard,
        float $transactionAmount,
        float $expectedCommission
    ): void {
        $transactionCurrency = 'EUR';
        $binChecker = $this->createMock(BinChecker::class);
        $binChecker->method('isEuIssuedCard')->willReturn($isEuIssuedCard);

        $currencyConverter = $this->createMock(CurrencyConverter::class);
        $currencyConverter->method('convert')->willReturn(new Money($transactionAmount, $transactionCurrency));

        $calculator = new CommissionCalculator($binChecker, $currencyConverter);
        $transaction = new Transaction('12341234', $transactionAmount, $transactionCurrency);

        $commission = $calculator->calculate($transaction);

        $this->assertEquals(new Money($expectedCommission, Money::CURRENCY_EUR), $commission);
    }

    public function isEuIssuedCardDataProvider(): array
    {
        return [
            [true, 100.00, 1],
            [true, 200.00, 2],
            [false, 100.00, 2],
            [false, 200.00, 4],
        ];
    }
}