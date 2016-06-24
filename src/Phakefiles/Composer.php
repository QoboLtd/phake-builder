<?php
// Composer related tasks
group('composer', function () {

    desc('Install composer dependencies');
    task('install', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: composer:install (Install composer dependencies)");

        $composer = new \PhakeBuilder\Composer(getValue('SYSTEM_COMMAND_COMPOSER', $app));
        doShellCommand($composer->install());
    });

    desc('Update composer dependencies');
    task('update', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: composer:update (Update composer dependencies)");

        $composer = new \PhakeBuilder\Composer(getValue('SYSTEM_COMMAND_COMPOSER', $app));
        doShellCommand($composer->install());
    });

});
