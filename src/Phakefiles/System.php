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

// Operating system tasks
group('system', function () {

    desc('Start system service');
    task('service-start', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: system:service-start (Start system service)");

        $serviceCommand = \PhakeBuilder\System::needsSudo() ? requireValue('SYSTEM_COMMAND_SUDO', $app) : '';
        $serviceCommand .= ' ' . requireValue('SYSTEM_COMMAND_SERVICE', $app);

        $service = new \PhakeBuilder\Service($serviceCommand);
        doShellCommand($service->start(requireValue('SYSTEM_SERVICE', $app)));
    });

    desc('Stop system service');
    task('service-stop', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: system:service-stop (Stop system service)");

        $serviceCommand = \PhakeBuilder\System::needsSudo() ? requireValue('SYSTEM_COMMAND_SUDO', $app) : '';
        $serviceCommand .= ' ' . requireValue('SYSTEM_COMMAND_SERVICE', $app);

        $service = new \PhakeBuilder\Service($serviceCommand);
        doShellCommand($service->stop(requireValue('SYSTEM_SERVICE', $app)));
    });

    desc('Restart system service');
    task('service-restart', ':builder:init', ':system:service-stop', ':system:service-start');
});
