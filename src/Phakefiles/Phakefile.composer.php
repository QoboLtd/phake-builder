<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Composer related tasks
group(
    'composer', function () {

        desc('Install composer dependencies');
        task(
            'install', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Composer install");

                $composer = new \PhakeBuilder\Composer(getValue('SYSTEM_COMMAND_COMPOSER', $app));
                doShellCommand($composer->install());
            }
        );

        desc('Update composer dependencies');
        task(
            'update', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Composer update");

                $composer = new \PhakeBuilder\Composer(getValue('SYSTEM_COMMAND_COMPOSER', $app));
                doShellCommand($composer->install());
            }
        );
    }
);

// vi:ft=php
