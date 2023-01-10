<?php

declare(strict_types = 1);

namespace RefactoringTask\Command;

use RefactoringTask\Entity\Transaction;
use RefactoringTask\Service\CommissionCalculator;
use Symfony\Component\Console\Output\OutputInterface;
use SplFileObject;
use InvalidArgumentException;

class CalculateCommissionsFromFileCommand
{
    /**
     * @var CommissionCalculator
     */
    private $commissionCalculator;

    public function __construct(CommissionCalculator $commissionCalculator)
    {
        $this->commissionCalculator = $commissionCalculator;
    }

    public function __invoke(string $filePath, OutputInterface $output): void
    {
        if (!is_file($filePath)) {
            throw new InvalidArgumentException(sprintf('File %s not found', $filePath));
        }

        $file = new SplFileObject($filePath);

        while (!$file->eof()) {
            $row = $file->fgets();

            if (!empty($row)) {
                $rowData = json_decode($row, true);

                $transaction = new Transaction($rowData['bin'], (float)$rowData['amount'], $rowData['currency']);

                try {
                    $commission = $this->commissionCalculator->calculate($transaction);
                } catch (\Exception $exception) {
                    $output->writeln(sprintf('ERROR: %s: %s', get_class($exception), $exception->getMessage()));

                    continue;
                }

                $output->writeln($commission->getAmount());
            }
        }
    }
}