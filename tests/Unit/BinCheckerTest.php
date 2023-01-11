<?php

declare(strict_types=1);

namespace RefactoringTask\Tests\Unit;

use RefactoringTask\Service\BinChecker;
use PHPUnit\Framework\TestCase;
use RefactoringTask\Service\External\BinInfoApi;

class BinCheckerTest extends TestCase
{
    /**
     * @dataProvider binCountryCheckDataProvider
     *
     * @param string $binCountryAlpha2
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testSuccessfullyCheckCase(string $binCountryAlpha2, bool $expectedResult): void
    {
        $binInfoApi = $this->createMock(BinInfoApi::class);
        $binInfoApi->method('request')->willReturn(['country' => ['alpha2' => $binCountryAlpha2]]);

        $binChecker = new BinChecker($binInfoApi);

        $result = $binChecker->isEuIssuedCard('12341234');

        $this->assertSame($expectedResult, $result);
    }

    public function binCountryCheckDataProvider(): array
    {
        return [
            ['AT', true],
            ['BE', true],
            ['BG', true],
            ['CY', true],
            ['CZ', true],
            ['DE', true],
            ['DK', true],
            ['EE', true],
            ['ES', true],
            ['FI', true],
            ['FR', true],
            ['GR', true],
            ['HR', true],
            ['HU', true],
            ['IE', true],
            ['IT', true],
            ['LT', true],
            ['LU', true],
            ['LV', true],
            ['MT', true],
            ['NL', true],
            ['PO', true],
            ['PT', true],
            ['RO', true],
            ['SE', true],
            ['SI', true],
            ['SK', true],
            ['AF', false],
            ['AL', false],
            ['AS', false],
            ['AD', false],
        ];
    }
}
