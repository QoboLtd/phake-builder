<?php
// Let's Encrypt related tasks
group('letsencrypt', function () {

    desc('Get webroot certonly');
    task('certonly', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: letsencrypt:certonly (Get webroot certonly)");

        $command = \PhakeBuilder\System::needsSudo() ? requireValue('SYSTEM_COMMAND_SUDO', $app) : '';
        $command .= ' ' . getValue('SYSTEM_COMMAND_LETSENCRYPT', $app);

        $email = requireValue('LETSENCRYPT_EMAIL', $app);
        $webroot = requireValue('LETSENCRYPT_WEBROOT', $app);
        $domains = explode(' ', requireValue('LETSENCRYPT_DOMAINS', $app));

        $command = new \PhakeBuilder\LetsEncrypt();
        doShellCommand($command->certonly($email, $webroot, $domains));
    });

});
