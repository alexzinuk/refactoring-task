<?php

declare(strict_types = 1);

namespace RefactoringTask\Service;

use RefactoringTask\Common\Money;
use RefactoringTask\Exception\CurrencyConvertException;
use RefactoringTask\Service\External\ExchangeRateApi;

class CurrencyConverter
{
    /**
     * @var ExchangeRateApi
     */
    private $exchangeRateApi;

    public function __construct(ExchangeRateApi $exchangeRateApi)
    {
        $this->exchangeRateApi = $exchangeRateApi;
    }

    public function convert(Money $money, string $targetCurrency): Money
    {
        if ($money->getCurrency() === $targetCurrency) {
            return $money;
        }

        $exchangeRate = $this->getExchangeRateForCurrency($targetCurrency);

        if ($exchangeRate === null) {
            throw new CurrencyConvertException(
                sprintf(
                    'Could not find exchange rate for currencies "%s" -> "%s"',
                    $money->getCurrency(),
                    $targetCurrency
                )
            );
        }

        if ($exchangeRate === 0.0) {
            throw new CurrencyConvertException(
                sprintf(
                    'Exchange rate "%s" -> "%s" is zero',
                    $money->getCurrency(),
                    $targetCurrency
                )
            );
        }

        return new Money($money->getAmount() / $exchangeRate, $targetCurrency);
    }

    private function getExchangeRateForCurrency(string $targetCurrency): ?float
    {
        $rates = $this->getLatestExchangeRates();

        foreach ($rates as $currency => $rate) {
            if ($currency === $targetCurrency) {
                return (float)$rate;
            }
        }

        return null;
    }

    private function getLatestExchangeRates(): array
    {
        $response = $this->exchangeRateApi->request('GET', '/latest');

        return $response['rates'] ?? [];
    }
}