<?php
// Sami.php related tasks
group('sami', function () {

    desc('Update API documentation');
    task('update', ':builder:init', ':dotenv:create', function ($app) {
        printSeparator();
        printInfo("Task: sami:update (Update API documentation)");

        $sami_command = getValue('SYSTEM_COMMAND_SAMI', $app);
        $sami_config = getValue('SAMI_CONFIG', $app);
        $sami = new \PhakeBuilder\Sami($sami_command);
        doShellCommand($sami->update($sami_config));
    });

});
