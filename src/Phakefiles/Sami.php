<?php
// Sami.php related tasks
group('sami', function () {

    desc('Update API documentation (source only)');
    task('update', ':builder:init', ':dotenv:create', function ($app) {
        printSeparator();
        printInfo("Task: sami:update (Update source API documentation)");

        $sami_command = getValue('SYSTEM_COMMAND_SAMI', $app);
        $sami_config = getValue('SAMI_CONFIG', $app);
        $sami_fullpath = true;
        if (empty($sami_config)) {
            $sami_config = 'source';
            $sami_fullpath = false;
        }
        $sami = new \PhakeBuilder\Sami($sami_command);
        doShellCommand($sami->update($sami_config, $sami_fullpath));
    });

    desc('Update API documentation (everything)');
    task('update-all', ':builder:init', ':dotenv:create', function ($app) {
        printSeparator();
        printInfo("Task: sami:update-all (Update all API documentation)");

        $sami_command = getValue('SYSTEM_COMMAND_SAMI', $app);
        $sami_config = getValue('SAMI_CONFIG', $app);
        $sami_fullpath = true;
        if (empty($sami_config)) {
            $sami_config = 'full';
            $sami_fullpath = false;
        }
        $sami = new \PhakeBuilder\Sami($sami_command);
        doShellCommand($sami->update($sami_config, $sami_fullpath));
    });
});
