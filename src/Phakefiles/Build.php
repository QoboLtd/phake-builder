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

if (!function_exists('phakeGetBuildDirs')) {
    /**
     * Get build directories
     *
     * Build directories will be removed and recreated during
     * the build:clean task.
     *
     * @return array
     */
    function phakeGetBuildDirs()
    {
        $result = array(
            'build/coverage',
            'build/logs',
            'build/pdepend',
        );

        return $result;
    }
}

if (!function_exists('phakeDirHasPHPFiles')) {
    /**
     * Check if the directory has any .php files (recursively)
     *
     * @param string $dir Directory to check
     * @return boolean True if at least one file is found, false otherwise
     */
    function phakeDirHasPHPFiles($dir)
    {
        $result = false;

        try {
            $dirit = new RecursiveDirectoryIterator($dir);
            $recit = new RecursiveIteratorIterator($dirit);
            $regexit = new RegexIterator($recit, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
            if (iterator_count($regexit) > 0) {
                $result = true;
            }
        } catch (\exception $e) {
            $result = false;
        }

        return $result;
    }
}

if (!function_exists('phakeGetBuildCommands')) {
    /**
     * Get all test and build commands
     *
     * @return array
     */
    function phakeGetBuildCommands()
    {
        $result = array(
            'phpunit' => './vendor/bin/phpunit',
            'phpcs' => './vendor/bin/phpcs',
            'pdepend' => './vendor/bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/pdepend/dependecies.svg --overview-pyramid=build/pdepend/overview-pyramid.svg src/',
            'phploc' => './vendor/bin/phploc --count-tests --log-csv build/logs/phploc.csv --log-xml build/logs/phploc.xml src/ tests/',
            'phpmd' => './vendor/bin/phpmd src/ text codesize,controversial,naming,unusedcode',
            'phpmd-ci' => './vendor/bin/phpmd src/ xml codesize,controversial,naming,unusedcode --reportfile build/logs/phpmd.xml',
            'phpcpd' => './vendor/bin/phpcpd --log-pmd=build/logs/phpcpd.xml src/',
        );

        $hasSrc = phakeDirHasPHPFiles('./src');
        $hasTests = phakeDirHasPHPFiles('./tests');

        if (!$hasTests) {
            printWarning("No unit test files found. Skipping phpnit");
            $result['phpunit'] = null;
        }
        if (!$hasSrc) {
            printWarning("No src files found. Skipping pdepend, phpmd, phpmd-ci, phpcpd");
            $result['pdepend'] = null;
            $result['phpmd'] = null;
            $result['phpmd-ci'] = null;
            $result['phpcpd'] = null;
        }

        if (!$hasSrc && !$hasTests) {
            printWarning("No unit test or source files found. Skipping phpcs, phploc");
            $result['phpcs'] = null;
            $result['phploc'] = null;
        }

        return $result;
    }
}

// Build related tasks
group('build', function () {

    desc('All build tasks');
    task('all', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:all (All build tasks)");

        $commands = phakeGetBuildCommands();

        $failedCommands = array();
        foreach ($commands as $name => $command) {
            try {
                printInfo("Task: build:$name");
                doShellCommand($command);
            } catch (\Exception $e) {
                $failedCommands[$name] = $e->getMessage();
            }
        }

        if (!empty($failedCommands)) {
            throw new \RuntimeException("The following failures occured during execution: \n" . print_r($failedCommands, true));
        }
        printInfo("SUCCESS!");
    });
    task('all', 'build:clean');
    task('all', 'sami:update');

    desc('Clean build files');
    task('clean', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:clean (Clean build files)");

        $dirs = phakeGetBuildDirs();
        foreach ($dirs as $dir) {
            \PhakeBuilder\FileSystem::removePath($dir);
            \PhakeBuilder\FileSystem::makeDir($dir);
        }
        printInfo("SUCCESS!");
    });

    desc('PHP unit tests (PHPUnit)');
    task('phpunit', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:phpunit (PHP unit tests (PHPUnit))");

        $commands = phakeGetBuildCommands();
        if (!empty($commands['phpunit'])) {
            doShellCommand($commands['phpunit']);
        }
    });

    desc('PHP coding style check (CodeSniffer)');
    task('phpcs', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:phpcs (PHP coding style check (CodeSniffer))");

        $commands = phakeGetBuildCommands();
        // This time for the developer and human-friendly output
        if (!empty($commands['phpcs'])) {
            doShellCommand($commands['phpcs']);
        }
    });

    desc('PHP coding metrics (PDepend)');
    task('pdepend', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:pdepend (PHP coding metrics (PDepend))");

        $commands = phakeGetBuildCommands();
        if (!empty($commands['pdepend'])) {
            doShellCommand($commands['pdepend']);
        }
    });

    desc('PHP lines of code metrics (PHPLOC)');
    task('phploc', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:phploc (PHP lines of code metrics (PHPLOC))");

        $commands = phakeGetBuildCommands();
        if (!empty($commands['phploc'])) {
            doShellCommand($commands['phploc']);
        }
    });

    desc('PHP mess detection (PHPMD)');
    task('phpmd', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:phpmd (PHP mess detection (PHPMD))");

        $commands = phakeGetBuildCommands();
        if (!empty($commands['phpmd-ci'])) {
            try {
                // This time for the build/logs/phpmd.xml
                doShellCommand($commands['phpmd-ci']);
            } catch (\Exception $e) {
            }
        }

        // This time for the developer and human-friendly output
        if (!empty($commands['phpmd'])) {
            doShellCommand($commands['phpmd']);
        }

        $commands = phakeGetBuildCommands();
        if (!empty($commands['phpmd'])) {
            doShellCommand($commands['phpmd']);
        }
    });

    desc('PHP copy-paste detection (PHPCPD)');
    task('phpcpd', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: build:phpcpd (PHP copy-paste detection (PHPCPD))");

        $commands = phakeGetBuildCommands();
        if (!empty($commands['phpcpd'])) {
            doShellCommand($commands['phpcpd']);
        }
    });
});
