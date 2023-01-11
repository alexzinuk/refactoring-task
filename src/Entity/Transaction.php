<?php

declare(strict_types=1);

namespace RefactoringTask\Entity;

use RefactoringTask\Common\Money;

class Transaction
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @param string $bin
     */
    public function __construct(string $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = new Money($amount, $currency);
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }
}