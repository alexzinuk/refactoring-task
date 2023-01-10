<?php

declare(strict_types=1);

use RefactoringTask\Command\CalculateCommissionsFromFileCommand;
use Silly\Application;

$container = require __DIR__.'/../app/bootstrap.php';

$app = new Application();

$app->useContainer($container, $injectWithTypeHint = true);

$app->command('commission-calculator:calculate-from-file [file-path]', CalculateCommissionsFromFileCommand::class);

$app->run();