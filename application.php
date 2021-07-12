<?php
// Autoload files
require __DIR__ . '/vendor/autoload.php';

use App\Command\ExportFilesToPdfCommand;
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new ExportFilesToPdfCommand());
$app->run();