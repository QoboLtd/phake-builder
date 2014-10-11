<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'System.php';

///////////////////////
// Utility functions //
///////////////////////

/**
 * Find a value for configuration parameter
 * 
 * @param $string $param Name of the configuration parameter
 * @param $array $app Application command line parameters
 * @return string
 */
function getValue($param, $app = null) {
	$result = null;

	// Command-line arguments are first
	if (!empty($app) && isset($app[$param])) {
		$result = $app[$param];
		return $result;
	}

	// .env file is second
	$result = getenv($param);
	if ($result !== false) {
		return $result;
	}
	
	// Default is third
	$default = \PhakeBuilder\System::getDefaultValue($param);
	if ($default !== null) {
		writeln(yellow("No value for $param has been given.  Using default."));
		$result = $default;
	}

	// Null is last
	return $result;
}

/**
 * Find a required value for configuration parameter
 * 
 * @param $string $param Name of the configuration parameter
 * @param $array $app Application command line parameters
 * @return string
 */
function requireValue($param, $app = null) {
	$result = getValue($param, $app);
	if (empty($result)) {
		throw new RuntimeException("Missing required configuration parameter for $param");
	}

	return $result;
}

/**
 * Execute a shell command
 * 
 * @param string $command Command to execute
 * @param string|array $privateInfo One or more strings to remove from screen output
 * @return void
 */
function doShellCommand($command, $privateInfo = null) {
	$command = trim($command) . ' 2>&1';
	
	writeln(purple("Executing shell command: " . \PhakeBuilder\System::secureString($command, $privateInfo)));
	
	try {
		$result = \PhakeBuilder\System::doShellCommand($command);
		$result = green(\PhakeBuilder\system::secureString($result, $privateInfo));
	}
	catch (Exception $e) {
		$result = red(\PhakeBuilder\System::secureString($e->getMessage(), $privateInfo));
		throw new RuntimeException("FAILED! " . $result);
	}
	writeln($result);
}



// Generic phake-builder tasks
group('builder', function() {
	
	desc('Initialize builder configuration');
	task('init', ':builder:hello', function($app) {
		Dotenv::load(getcwd());
	});

	desc('Print welcome message');
	task('hello', function($app) {
		writeln(green('Welcome to phake-builder! Use "phake -T" to list all commands. More info at https://github.com/QoboLtd/phake-builder'));
	});

});
# vi:ft=php
?>
