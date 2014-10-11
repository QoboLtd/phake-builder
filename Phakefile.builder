<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'System.php';

///////////////////////
// Utility functions //
///////////////////////

/**
 * Print error message
 * 
 * @param string $message Message to show as error
 * @return void
 */
function printError($message) {
	writeln(red($message));
}

/**
 * Print success message
 * 
 * @param string $message Message to show as success
 * @return void
 */
function printSuccess($message) {
	writeln(green($message));
}

/**
 * Print warning message
 * 
 * @param string $message Message to show as warning
 * @return void
 */
function printWarning($message) {
	writeln(yellow($message));
}

/**
 * Print info message
 * 
 * @param string $message Message to show as info
 * @return void
 */
function printInfo($message) {
	writeln(cyan($message));
}

/**
 * Print debug message
 * 
 * @param string $message Message to show as debug
 * @return void
 */
function prinDebug($message) {
	writeln(purple($message));
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
	$default = \PhakeBuilder\System::getDefaultValue($param);
	if ($default !== null) {
		printWarning("No value for $param has been given.  Using default.");
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
 * @param string|array $command Command to execute (as full string or parts)
 * @param string|array $privateInfo One or more strings to remove from screen output
 * @return void
 */
function doShellCommand($command, $privateInfo = null) {
	if (is_array($command)) {
		$command = implode(' ', array_map('trim', $command));
	}
	$command = trim($command) . ' 2>&1';
	prinDebug("Executing shell command: " . \PhakeBuilder\System::secureString($command, $privateInfo));
	
	try {
		$result = \PhakeBuilder\System::doShellCommand($command);
		$result = \PhakeBuilder\system::secureString($result, $privateInfo);
	}
	catch (Exception $e) {
		$result = \PhakeBuilder\System::secureString($e->getMessage(), $privateInfo);
		throw new RuntimeException("FAILED! Output: " . $result);
	}
	printSuccess("SUCCESS! Output: " . $result);
}

/**
 * Execute a MySQL command
 * 
 * @param string $query MySQL query to execute
 * @return voi
 */
function doMySQLCommand($app, $query, $requireDB = true, $asAdmin = false, $command = 'SYSTEM_COMMAND_MYSQL') {

	// Host is never required, but always nice to have
	$host = getValue('DB_HOST', $app);

	// Use admin credentials for admin operations
	if ($asAdmin) {
		$user = requireValue('DB_ADMIN_USER', $app);
		$pass = getValue('DB_ADMIN_PASS', $app);
	}
	// use regular user credentials for everything else
	else {
		$user = getValue('DB_USER', $app);
		$pass = getValue('DB_PASS', $app);
	}
	
	// Not everything requires a known database name parameter
	$name = null;
	if ($requireDB) {
		$name = requireValue('DB_NAME', $app);
	}

	// Build command line strings for different commands
	switch ($command) {
		// /usr/bin/mysql -u root -p foo -h localhost -e 'select now();'
		case 'SYSTEM_COMMAND_MYSQL':
			if (empty($query)) {
				throw new RuntimeException("No SQL query given");
			}
			$command = escapeshellcmd(requireValue($command, $app));
			$command .= ($host) ? ' -h' . escapeshellarg($host) : '';
			$command .= ($user) ? ' -u' . escapeshellarg($user) : '';
			$command .= ($pass) ? ' -p' . escapeshellarg($pass) : '';
			$command .= ($name) ? ' ' . escapeshellarg($name) : '';
			$command .= ' -e ' . escapeshellarg($query);
			break;
		// ./vendor/bin/mysql-replace.php database=foo find=blah relace=bleh
		case 'SYSTEM_COMMAND_MYSQL_REPLACE':
			$find = requireValue('DB_FIND', $app);
			$replace = requireValue('DB_REPLACE', $app);
			
			$command = requireValue($command, $app);
			$command .= ($host) ? ' hostname=' . $host : '';
			$command .= ($user) ? ' username=' . $user : '';
			$command .= ($pass) ? " password='" . $pass . "'" : '';
			$command .= ($name) ? ' database=' . $host : '';
			$command .= " find='" . $find . "'";
			$command .= " replace='" . $replace . "'";
			break;
		default:
			throw new RuntimeException("$command is not supported");
			break;
	}
	
	// Just in case, always pad user and admin passwords
	$secureStrings = array(
		getValue('DB_PASS', $app), 
		getValue('DB_ADMIN_PASS', $app),
	);

	doShellCommand($command, $secureStrings);
}

// Generic phake-builder tasks
group('builder', function() {
	
	desc('Initialize builder configuration');
	task('init', ':builder:hello', function($app) {
		Dotenv::load(getcwd());
	});

	desc('Print welcome message');
	task('hello', function($app) {
		printSuccess('Welcome to phake-builder! Use "phake -T" to list all commands. More info at https://github.com/QoboLtd/phake-builder');
	});

});
# vi:ft=php
?>
