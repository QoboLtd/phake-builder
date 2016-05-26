<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Dotenv related tasks
group(
    'dotenv', function () {

        desc('Create .env file');
        task(
            'create', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Creating .env file");

                $envFile = getcwd() . DIRECTORY_SEPARATOR . '.env';
                $templateFile = getcwd() . DIRECTORY_SEPARATOR . '.env.example';

                if (!file_exists($templateFile)) {
                    throw new \RuntimeException(".env template file ($templateFile) does not exist");
                }
                if (!is_file($templateFile)) {
                    throw new \RuntimeException(".env template file ($templateFile) is not a file");
                }
                if (!is_readable($templateFile)) {
                    throw new \RuntimeException(".env template file ($templateFile) is not readable");
                }
                $linesIn = file($templateFile);
                $linesOut = array();

                // Know all available parameters via App
                $appParams = array();
                foreach ($app as $key => $value) {
                    $appParams[] = $key;
                }

                $count = 0;
                $processedParams = array();
                foreach ($linesIn as $line) {
                    $count++;
                    trim($line);
                    if (!preg_match('#^(.*)?=(.*)?$#', $line, $matches)) {
                        $linesOut[] = $line;
                        continue;
                    }
                    $name = $matches[1];
                    if (!in_array($name, $processedParams)) {
                        $value = getValue($name, $app) ? getValue($name, $app) : $matches[2];
                        $linesOut[] = $name . '=' . $value;
                        $processedParams[] = $name;
                    }

                    // Remove current parameter from the all known parameters list
                    if (in_array($name, $appParams)) {
                        unset($appParams[$name]);
                    }
                }

                // If anything is left in known parameters list, append it to the file
                if (!empty($appParams)) {
                    foreach ($appParams as $param) {
                        $linesOut[] = '';
                        if (!in_array($param, $processedParams)) {
                            $value = getValue($param, $app);
                            $linesOut[] = $param . '=' . $value;
                        }
                    }
                }

                $bytes = file_put_contents($envFile, implode("\n", $linesOut));
                if (!$bytes) {
                    throw new \RuntimeException("Failed to save $count lines to $envFile");
                }
                printSuccess("SUCCESS! Saved $count lines to $envFile");
            }
        );

        desc('Reload settings from .env');
        task(
            'reload', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Reloading .env configuration");

                Dotenv::makeMutable();
                Dotenv::load(getcwd());
                Dotenv::makeImmutable();

                printSuccess("SUCCESS!");
            }
        );

        desc('Delete .env file');
        task(
            'delete', ':builder:init', function ($app) {
                printSeparator();
                printInfo("Deleting .env file");

                $envFile = getcwd() . DIRECTORY_SEPARATOR . '.env';
                if (file_exists($envFile)) {
                    $result = unlink($envFile);
                    if ($result) {
                        printSuccess("SUCCESS! Removed $envFile");
                    } else {
                        throw new \RuntimeException("Failed to remove $envFile");
                    }
                } else {
                    printWarning("File $envFile does not exist");
                }
            }
        );
    }
);
