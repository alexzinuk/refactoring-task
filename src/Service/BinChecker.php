<?php

declare(strict_types=1);

namespace RefactoringTask\Service;

use RefactoringTask\Entity\BinInfo;
use RefactoringTask\Exception\BinInfoNotFoundException;
use RefactoringTask\Service\External\BinInfoApi;

class BinChecker
{
    private const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    /**
     * @var BinInfoApi
     */
    private $binInfoApi;

    public function __construct(BinInfoApi $binInfoApi)
    {
        $this->binInfoApi = $binInfoApi;
    }

    public function isEuIssuedCard(string $binNumber): bool
    {
        $binInfo = $this->getBinInfo($binNumber);

        if (!$binInfo) {
            throw new BinInfoNotFoundException(sprintf('Not found info for bin number "%s"', $binNumber));
        }

        return in_array($binInfo->getCountryAlpha2(), self::EU_COUNTRIES, true);
    }

    private function getBinInfo(string $binNumber): ?BinInfo
    {
        $response = $this->binInfoApi->request('GET', sprintf('/%s', $binNumber));
        $countryAlpha2 = $response['country']['alpha2'] ?? null;

        return $countryAlpha2 ? new BinInfo($countryAlpha2) : null;
    }
}