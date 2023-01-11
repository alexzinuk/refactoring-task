<?php

declare(strict_types=1);

namespace RefactoringTask\Service;

use RefactoringTask\Common\Money;
use RefactoringTask\Entity\Transaction;

class CommissionCalculator
{
    public const EU_AMOUNT_COMMISSION_FACTOR = 0.01;

    public const NON_EU_AMOUNT_COMMISSION_FACTOR = 0.02;

    /**
     * @var BinChecker
     */
    private $binChecker;

    /**
     * @var CurrencyConverter
     */
    private $currencyConverter;

    public function __construct(BinChecker $binChecker, CurrencyConverter $currencyConverter)
    {
        $this->binChecker = $binChecker;
        $this->currencyConverter = $currencyConverter;
    }

    public function calculate(Transaction $transaction): Money
    {
        $isEuIssuedCard = $this->binChecker->isEuIssuedCard($transaction->getBin());
        $commissionFactor = $isEuIssuedCard
            ? self::EU_AMOUNT_COMMISSION_FACTOR
            : self::NON_EU_AMOUNT_COMMISSION_FACTOR;

        $transactionAmountInEur = $this->currencyConverter->convert($transaction->getAmount(), Money::CURRENCY_EUR);

        return new Money($transactionAmountInEur->getAmount() * $commissionFactor, Money::CURRENCY_EUR);
    }
}