<?php
// MySQL utiility tasks
group('mysql', function () {

    desc('Test MySQL database connection');
    task('connect', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:connect (Test MySQL database connection)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_USER', $app),
            'pass' => getValue('DB_PASS', $app),
            'name' => getValue('DB_NAME', $app),
        );

        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->query('SELECT NOW() AS ServerTime');
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Create database');
    task('database-create', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:database-create (Create database)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_ADMIN_USER', $app),
            'pass' => getValue('DB_ADMIN_PASS', $app),
        );

        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->query('CREATE DATABASE IF NOT EXISTS ' . requireValue('DB_NAME', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Drop database');
    task('database-drop', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:database-drop (Drop database)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_ADMIN_USER', $app),
            'pass' => getValue('DB_ADMIN_PASS', $app),
        );

        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->query('DROP DATABASE IF EXISTS ' . requireValue('DB_NAME', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Import database');
    task('database-import', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:database-import (Import database)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_USER', $app),
            'pass' => getValue('DB_PASS', $app),
            'name' => getValue('DB_NAME', $app),
        );

        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->import(requireValue('DB_DUMP_PATH', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Find and replace across the database');
    task('find-replace', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:find-replace (Find and replace across the database)");

        $find = getValue('DB_FIND', $app);
        $replace = getValue('DB_REPLACE', $app);

        if (empty($find)) {
            printDebug("Nothing to find.  Skipping replace.");
            return;
        }

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_USER', $app),
            'pass' => getValue('DB_PASS', $app),
            'name' => getValue('DB_NAME', $app),
        );

        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL_REPLACE', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->findReplace($find, $replace);
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Grant access');
    task('access-grant', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:access-grant (Grant access)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_ADMIN_USER', $app),
            'pass' => getValue('DB_ADMIN_PASS', $app),
        );
        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->grant(requireValue('DB_NAME', $app), requireValue('DB_USER', $app), getValue('DB_PASS', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Revoke access');
    task('access-revoke', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:access-revoke (Revoke access)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_ADMIN_USER', $app),
            'pass' => getValue('DB_ADMIN_PASS', $app),
        );
        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->revoke(requireValue('DB_NAME', $app), requireValue('DB_USER', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Allow file operation');
    task('access-file-allow', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:access-file-allow (Allow file operation)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_ADMIN_USER', $app),
            'pass' => getValue('DB_ADMIN_PASS', $app),
        );
        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->fileAllow(requireValue('DB_USER', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });

    desc('Deny file operation');
    task('access-file-deny', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: mysql:access-file-deny (Deny file operation)");

        $dsn = array(
            'host' => getValue('DB_HOST', $app),
            'user' => getValue('DB_ADMIN_USER', $app),
            'pass' => getValue('DB_ADMIN_PASS', $app),
        );
        $mysql = new \PhakeBuilder\MySQL(requireValue('SYSTEM_COMMAND_MYSQL', $app));
        $mysql->setDSN($dsn);
        $command = $mysql->fileDeny(requireValue('DB_USER', $app));
        $secureStrings = array('DB_PASS', 'DB_ADMIN_PASS');
        doShellCommand($command, $secureStrings);
    });
});
