<?php

namespace App\Command\Example;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExampleHelloWorld extends Command
{
    public static function getDefaultName(): ?string
    {
        return "example:hello-world";
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Hello World!");

        return self::SUCCESS;
    }
}
