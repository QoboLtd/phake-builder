<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'System.php';

///////////////////////
// Utility functions //
///////////////////////

/**
 * Print separator
 * 
 * This is mighty useful in long outputs
 * 
 * @param string $string String to use for separator
 * @param integer $length Length of the separator line
 * @return void
 */
function printSeparator($character = '-', $length = 70) {
	writeln(white(str_repeat($character, $length), true));
}

/**
 * Format output message
 * 
 * @param string $message Message text
 * @param string $prefix Prefix text, like DEBUG or INF
 * @param string $dateFormat Date format to use. If null, no date is used
 * @return string
 */
function formatMessage($message, $prefix, $dateFormat = '[Y-m-d H:i:s]') {
	$result = $prefix . ' ' . $message;
	if ($dateFormat) {
		$result = date($dateFormat) . ' ' . $result;
	}
	return $result;
}

/**
 * Print error message
 * 
 * Error messages are different from all the other ones
 * because they might be thrown by exceptions.
 * 
 * @param string $message Message to show as error
 * @param boolean $format Format the message or print as is
 * @return void
 */
function printError($message, $format = true, $returnNoPrint = false) {
	if ($format) {
		$message = formatMessage($message, ':ERROR:');
	}
	
	if ($returnNoPrint) {
		return $message;
	}
	writeln(red($message, true));
}

/**
 * Print success message
 * 
 * @param string $message Message to show as success
 * @param boolean $format Format the message or print as is
 * @return void
 */
function printSuccess($message, $format = true) {
	if ($format) {
		$message = formatMessage($message, ':OK   :');
	}
	writeln(green($message, true));
}

/**
 * Print warning message
 * 
 * @param string $message Message to show as warning
 * @param boolean $format Format the message or print as is
 * @return void
 */
function printWarning($message, $format = true) {
	if ($format) {
		$message = formatMessage($message, ':WARN :');
	}
	writeln(yellow($message, true));
}

/**
 * Print info message
 * 
 * @param string $message Message to show as info
 * @param boolean $format Format the message or print as is
 * @return void
 */
function printInfo($message, $format = true) {
	if ($format) {
		$message = formatMessage($message, ':INFO :');
	}
	writeln(cyan($message, true));
}

/**
 * Print debug message
 * 
 * @param string $message Message to show as debug
 * @param boolean $format Format the message or print as is
 * @return void
 */
function printDebug($message, $format = true) {
	if ($format) {
		$message = formatMessage($message, ':DEBUG:');
	}
	writeln(purple($message, true));
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
		throw new RuntimeException(printError("Missing required configuration parameter for $param", true, true));
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
	printDebug("Executing shell command: " . \PhakeBuilder\System::secureString($command, $privateInfo));
	
	try {
		$result = \PhakeBuilder\System::doShellCommand($command);
		$result = \PhakeBuilder\system::secureString($result, $privateInfo);
	}
	catch (Exception $e) {
		$result = \PhakeBuilder\System::secureString($e->getMessage(), $privateInfo);
		throw new RuntimeException(printError($result, true, true));
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
				throw new RuntimeException(printError("No SQL query given", true, true));
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
			$command .= ' -h ' . $host ;
			$command .= ' -u ' . $user;
			$command .= " -p '" . $pass . "'";
			$command .= ' -n ' . $name;
			$command .= " -s '" . $find . "'";
			$command .= " -r '" . $replace . "'";
			break;
		default:
			throw new RuntimeException(printError("$command is not supported", true, true));
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
		try {
			Dotenv::load(getcwd());
		}
		catch (Exception $e) {
			printWarning("Failed to load .env configuration file");
		}
	});

	desc('Print welcome message');
	task('hello', function($app) {
		printSuccess('Welcome to phake-builder!', false);
		printSuccess('Use "phake -T" to list all commands.', false); 
		printSuccess('More info at https://github.com/QoboLtd/phake-builder', false);
		printSeparator();
	});

});
# vi:ft=php
?>
