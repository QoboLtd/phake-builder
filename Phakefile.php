<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// Set default timezone
\PhakeBuilder\Utils::setDefaultTimezone();

// Load Phakefiles
$phakefilesPath = __DIR__ . DIRECTORY_SEPARATOR
    . 'src' . DIRECTORY_SEPARATOR
    . 'Phakefiles';
$phakefiles = \PhakeBuilder\Utils::findPhakefileParts($phakefilesPath);
foreach ($phakefiles as $phakefile) {
    require_once $phakefile;
}
