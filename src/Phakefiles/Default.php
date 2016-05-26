<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

desc('Default target');
task('default', 'builder:init');
