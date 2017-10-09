<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

// Dotenv related tasks
group('dotenv', function () {

    desc('Create .env file');
    task('create', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: dotenv:create (Create .env file)");

        // .env
        $envFile = getcwd() . DIRECTORY_SEPARATOR . '.env';
        // .env.example
        $templateFile = getcwd() . DIRECTORY_SEPARATOR . '.env.example';
        // Command line arguments
        $appParams = [];
        foreach ($app as $key => $value) {
            $appParams[$key] = $value;
        }

        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->generate($envFile, $templateFile, $appParams);

        if (!$result) {
            throw new \RuntimeException("Failed to save $envFile");
        }
        printSuccess("SUCCESS! Generated $envFile");
    });

    desc('Reload settings from .env file');
    task('reload', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: dotenv:reload (Reload settings from .env file)");

        Dotenv::makeMutable();
        Dotenv::load(getcwd());
        Dotenv::makeImmutable();

        printSuccess("SUCCESS!");
    });

    desc('Delete .env file');
    task('delete', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: dotenv:delete (Delete .env file)");

        $envFile = getcwd() . DIRECTORY_SEPARATOR . '.env';
        if (file_exists($envFile)) {
            $result = unlink($envFile);
            if ($result) {
                printSuccess("SUCCESS! Removed $envFile");
            } else {
                throw new \RuntimeException("Failed to remove $envFile");
            }
        } else {
            printWarning("File $envFile does not exist");
        }
    });
});
