<?php

declare(strict_types=1);

namespace App\Command\Validate;

use App\Command\AbstractCommand;

final class ValidateAllCommand extends AbstractCommand
{
    /** @var string */
    protected static $defaultName = 'validate:all';

    protected function configure(): void
    {
        parent::configure();

        $this->setDescription('Call all validate commands');
    }

    protected function doExecute(): parent
    {
        return $this
            ->runCommand(ValidateBranchNameCommand::getDefaultName())
            ->runCommand(ValidateComposerJsonCommand::getDefaultName())
            ->runCommand(ValidateConfigurationComposerLockCommand::getDefaultName())
            ->runCommand(ValidateConfigurationConfigurationClassCommand::getDefaultName())
            ->runCommand(ValidateConfigurationComponentSourceCodeUrlsCommand::getDefaultName())
            ->runCommand(ValidateConfigurationInitBenchmarkCommand::getDefaultName())
            ->runCommand(ValidateConfigurationResponseBodyCommand::getDefaultName())
            ->runCommand(ValidateConfigurationVhostCommand::getDefaultName());
    }
}
