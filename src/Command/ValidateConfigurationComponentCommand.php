<?php

declare(strict_types=1);

namespace App\Command;

use App\{
    Benchmark\BenchmarkType,
    Component\ComponentType,
    ComponentConfiguration\ComponentConfiguration,
    PhpVersion\PhpVersion
};

class ValidateConfigurationComponentCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('validate:configuration:component')
            ->setDescription('Validate .phpbenchmarks/AbstractComponentConfiguration.php');
    }

    protected function doExecute(): parent
    {
        $this
            ->title('Validation of .phpbenchmarks/AbstractComponentConfiguration.php')
            ->assertInArray('getComponentType', ComponentType::getAll())
            ->assertCallMethod('getComponentName')
            ->assertCallMethod('getComponentSlug')
            ->assertPhpVersionsEnabled()
            ->assertCallMethod('getBenchmarkUrl')
            ->assertCallMethod('getCoreDependencyName')
            ->assertCallMethod('getCoreDependencyMajorVersion')
            ->assertCallMethod('getCoreDependencyMinorVersion')
            ->assertCallMethod('getCoreDependencyPatchVersion')
            ->assertInArray(
                'getBenchmarkType',
                BenchmarkType::getByComponentType(ComponentConfiguration::getComponentType())
            );

        return $this;
    }

    protected function onError(): parent
    {
        $this->warning('You can use phpbench initialize:branch to create AbstractComponentConfiguration class.');

        return $this;
    }

    protected function assertInArray(string $method, array $allowedValues): self
    {
        $value = ComponentConfiguration::{$method}();
        if (array_key_exists($value, $allowedValues) === false) {
            $allowedValuesError = [];
            foreach ($allowedValues as $allowedValue => $allowedValueDescription) {
                $allowedValuesError[] = $allowedValue . ' (' . $allowedValueDescription . ')';
            }
            $this->error($method . '() should return a data among ' . implode(', ', $allowedValuesError) . '.');
        }
        $this->success($method . '() return ' . $value . ' (' . $allowedValues[$value] . ').');

        return $this;
    }

    protected function assertCallMethod(string $method): self
    {
        $value = ComponentConfiguration::{$method}();
        if (is_bool($value)) {
            $valueStr = $value ? 'true' : 'false';
        } else {
            $valueStr = $value;
        }
        $this->success($method . '() return ' . $valueStr . '.');

        return $this;
    }

    protected function assertPhpVersionsEnabled(): self
    {
        foreach (PhpVersion::getAllWithoutDot() as $phpVersion) {
            $this->assertCallMethod('isPhp' . $phpVersion . 'Enabled');
        }

        return $this;
    }
}
