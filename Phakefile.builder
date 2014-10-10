<?php
require_once 'vendor/autoload.php';

///////////////////////
// Utility functions //
///////////////////////

/**
 * Get default configuration value for given parameter
 * 
 * @param string $param Parameter to get default value for
 * @return string|null String if found, null otherwise
 */
function getDefaultValue($param) {
	$result = null;
	
	$defaults = array(
		'GIT_REMOTE' => 'origin',
		'GIT_BRANCH' => 'master',

		'DB_HOST' => 'localhost',
		'DB_USER' => 'root',
		'DB_PASS' => '',

		'SYSTEM_COMMAND_GIT' => '/usr/bin/git',	
		'SYSTEM_COMMAND_SUDO' => '/usr/bin/sudo',
		'SYSTEM_COMMAND_SERVICE' => '/usr/sbin/service',

		'SYSTEM_COMMAND_TOUCH' => '/usr/bin/touch',
		'SYSTEM_COMMAND_LINK' => '/usr/bin/ln -s',
		'SYSTEM_COMMAND_RM' => '/usr/bin/rm -r',
		'SYSTEM_COMMAND_MKDIR' => '/usr/bin/mkdir -p',
	);

	if (isset($defaults[$param])) {
		$result = $defaults[$param];
	}

	return $result;
}

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
	$default = getDefaultValue($param);
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
 * Check if the current user needs sudo
 * 
 * root user doesn't need sudo.  Everybody else does.
 * 
 * This functionality is outside of targets for future
 * proof.  One day we might need a more complex way to
 * figure the answer to this question.  For example,
 * based on a while of some parameter.
 * 
 * @return boolean True if needs, false otherwise
 */
function needsSudo() {
	$result = (posix_getuid() == 0) ? false : true; 
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
	$command .= trim($command) . ' 2>&1';
	
	writeln(purple("Executing shell command: " . secureString($command, $privateInfo)));
	
	unset($output);
	$result = exec($command, $output, $return);
	$output = secureString(implode("\n", $output), $privateInfo);
	if ($return > 0) {
		throw new RuntimeException("Failed! " . $output);
	}
	writeln(green("Success. Output: \n" . $output));
}

/**
 * Secure string for screen output
 * 
 * @param string $string String to secure
 * @param string|array $privateInfo One or more strings to replace
 * @return string
 */
function secureString($string, $privateInfo) {
	$result = $string;

	if (empty($privateInfo)) {
		return $result;
	}

	if (!is_array($privateInfo)) {
		$privateInfo = [ $privateInfo ];
	}

	foreach ($privateInfo as $privateString) {
		$replacement = str_repeat('x', strlen($privateString));
		$result = str_replace($privateString, $replacement, $result);
	}

	return $result;
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
