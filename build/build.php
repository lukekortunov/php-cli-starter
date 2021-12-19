<?php

try {
    $file = "dist/app.phar";

    if (file_exists($file)) {
        unlink($file);
    }

    if (file_exists($file . '.gz')) {
        unlink($file . '.gz');
    }

    $phar = new Phar($file);
    $phar->startBuffering();

    $defaultStub = $phar->createDefaultStub('app.php');
    $phar->buildFromDirectory(__DIR__ . '/../src');

    $stub = "#!/usr/bin/env php \n" . $defaultStub;
    $phar->setStub($stub);

    $phar->stopBuffering();
    $phar->compressFiles(Phar::GZ);

    chmod(__DIR__ . '/app.phar', 0770);
    echo "$file successfully created" . PHP_EOL;
} catch (Exception $e) {
    print_r($e->getMessage());
}
