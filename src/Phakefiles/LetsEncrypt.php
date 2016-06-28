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

    desc('Link certificate files');
    task('symlink', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: letsencrypt:symlink (Link certificate files)");

        // The reason for joining and then splitting these values is that
        // some of them (LETSENCRYPT_DOMAINS, NGINX_SITE_OTHER) can contain
        // multiple domains, space-separated.
        $sslDomains = join(' ', array(
            getValue('LETSENCRYPT_DOMAINS', $app),
            getValue('NGINX_SITE_MAIN', $app),
            getValue('NGINX_SITE_OTHER', $app)
        ));
        $sslDomains = array_unique(array_map('trim', explode(' ', $sslDomains)));
        if (empty($sslDomains)) {
            throw new \RuntimeException("No domains found");
        }

        // List of certificate files to link
        $sslCerts = array(
            'cert.pem',
            'chain.pem',
            'fullchain.pem',
            'privkey.pem',
        );

        foreach ($sslDomains as $sslDomain) {
            // Leftovers of space-separation
            if (empty($sslDomain)) {
                printDebug("Skipping empty domain");
                continue;
            }
            // Domain has no certificates
            if (!file_exists("/etc/letsencrypt/live/$sslDomain")) {
                printDebug("No certificates found for domain $sslDomain");
                continue;
            }
            // Check each certificate for the domain
            foreach ($sslCerts as $sslCert) {
                if (file_exists("etc/ssl/$sslDomain.$sslCert")) {
                    printDebug("Certificate $sslCert is already linked for domain $sslDomain");
                    continue;
                }
                $result = symlink("/etc/letsencrypt/live/$sslDomain/$sslCert", "etc/ssl/$sslDomain.$sslCert");
                if (!$result) {
                    throw new \RuntimeException("Failed to link certificate $sslCert for domain $sslDomain");
                } else {
                    printInfo("Linked etc/ssl/$sslDomain.$sslCert to /etc/letsencrypt/live/$sslDomain/$sslCert");
                }
            }
        }
        printSuccess("SUCCESS!");
    });
});
