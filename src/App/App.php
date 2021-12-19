<?php

namespace App;

use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class App
{
    private Finder $finder;
    private Application $application;

    public function __construct(Application $application, Finder $finder)
    {
        $this->finder       = $finder;
        $this->application  = $application;
    }

    public function run(): void
    {
        $filter = function (SplFileInfo $fileInfo): bool
        {
            $ref = $this->getReflection($fileInfo);
            return $ref->isSubclassOf(Command::class);
        };

        $this->finder
            ->files()
            ->in(__DIR__ . "/Command")
            ->name('*.php')
            ->filter($filter)
        ;

        foreach ($this->finder as $file) {
            $ref = $this->getReflection($file);
            $ins = $ref->newInstance();

            if (!$ins instanceof Command) {
                continue; // TODO warnings should be here
            }

            $this->application->add($ins);
        }

        try {
            $this->application->run();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function parseFqcn(SplFileInfo $fileInfo): ?string
    {
        return once(function () use ($fileInfo) {
            try {
                $filePath  = explode(__DIR__, $fileInfo->getPath())[1];
                $fileName  = str_replace(".php", "", $fileInfo->getBasename());
                return str_replace('/', '\\', "/App{$filePath}/{$fileName}");
            } catch (Exception $exception) {
                return null;
            }
        });
    }

    /**
     * @throws ReflectionException
     */
    private function getReflection(SplFileInfo $fileInfo): ?ReflectionClass
    {
        return once(function () use ($fileInfo) {
            return new ReflectionClass($this->parseFqcn($fileInfo));
        });
    }
}
