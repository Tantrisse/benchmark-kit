<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Console\Output\OutputInterface;

class ValidationException extends \Exception
{
    public function __construct(OutputInterface $output, string $message)
    {
        parent::__construct($message);

        $output->writeln("  \e[41m > \e[00m \e[41m ERROR \e[00m \e[31m" . $message . "\e[00m");
    }
}
