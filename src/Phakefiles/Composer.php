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
