<?php

declare(strict_types = 1);

namespace RefactoringTask\Common;

class Money
{
    public const CURRENCY_EUR = 'EUR';

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    public function __construct(float $amount, string $currency)
    {
        $this->amount = round($amount, 2);
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}