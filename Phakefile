<?php
require_once 'vendor/autoload.php';

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

	$defaults = array(
		'GIT_REMOTE' => 'origin',
		'GIT_BRANCH' => 'master',

		'DB_HOST' => 'localhost',
		'DB_USER' => 'root',
		'DB_PASS' => '',
	);

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
	if (isset($defaults[$param])) {
		writeln(yellow("No value for $param has been given.  Assuming default"));
		$result = $defaults[$param];
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
	$command .= ' 2>&1';
	
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

/////////////////////////
// Phake-builder tasks //
/////////////////////////

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

// MySQL utiility tasks
group('mysql', function() {

	desc('Test MySQL database connection');
	task('connect', ':builder:init', function($app) {
		writeln('Testing MySQL database connection with given credentials');

		$dbName = requireValue('DB_NAME', $app);
		
		writeln(yellow('TODO: switch to mysqli or PDO'));
		$db = mysql_connect(getValue('DB_HOST', $app), getValue('DB_USER', $app), getValue('DB_PASS', $app));
		if (!is_resource($db)) {
			throw new RuntimeException("Failed to connect to the database: " . mysql_error());
		}
		if (!mysql_select_db($dbName, $db)) {
			throw new RuntimeException("Failed to select the database: " . mysql_error());
		}
		mysql_close($db);
		
		writeln(green('DB connection successfully established'));
	});

});

// Git related tasks
group('git', function() {
	
	desc('Git checkout');
	task('checkout', ':builder:init', function($app) {
		doShellCommand(implode(' ', ['git', 'checkout', requireValue('GIT_BRANCH', $app)]));
	});

	desc('Git pull');
	task('pull', ':builder:init', function($app) {
		doShellCommand(implode(' ', ['git', 'pull', getValue('GIT_REMOTE', $app), getValue('GIT_BRANCH', $app)]));
	});
	
	desc('Git push');
	task('push', ':builder:init', function($app) {
		doShellCommand(implode(' ', ['git', 'push', getValue('GIT_REMOTE', $app), getValue('GIT_BRANCH', $app)]));
	});

});

// Operating system tasks
group('system', function() {

	desc('Start system service');
	task('service-start', ':builder:init', function($app) {
		$command = needsSudo() ? 'sudo service' : 'service';
		doShellCommand(implode(' ', [$command, requireValue('SYSTEM_SERVICE', $app), 'start']));
	});
	
	desc('Stop system service');
	task('service-stop', ':builder:init', function($app) {
		$command = needsSudo() ? 'sudo service' : 'service';
		doShellCommand(implode(' ', [$command, requireValue('SYSTEM_SERVICE', $app), 'stop']));
	});
	
	desc('Restart system service');
	task('service-restart', ':builder:init', ':system:service-stop', ':system:service-start');

});

desc('Default target');
task('default', 'builder:hello');
# vi:ft=php
?>
