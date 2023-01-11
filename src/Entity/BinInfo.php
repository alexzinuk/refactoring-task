<?php

declare(strict_types=1);

namespace RefactoringTask\Entity;

class BinInfo
{
    /**
     * @var string
     */
    private $countryAlpha2;

    public function __construct(string $countryAlpha2)
    {
        $this->countryAlpha2 = $countryAlpha2;
    }

    /**
     * @return string
     */
    public function getCountryAlpha2(): string
    {
        return $this->countryAlpha2;
    }
}