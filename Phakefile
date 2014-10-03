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
	task('init', function($app) {
		Dotenv::load(getcwd());
	});

});

// MySQL utiility tasks
group('mysql', function() {

	desc('Test MySQL database connection');
	task('connect', ':builder:init', function($app) {
		writeln('Testing MySQL database connection with given credentials');

		Dotenv::required(['DB_NAME']);
		
		writeln(yellow('TODO: switch to mysqli or PDO'));
		$db = mysql_connect(getValue('DB_HOST', $app), getValue('DB_USER', $app), getValue('DB_PASS', $app));
		if (!is_resource($db)) {
			throw new RuntimeException("Failed to connect to the database: " . mysql_error());
		}
		if (!mysql_select_db(getValue('DB_NAME', $app), $db)) {
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
		doShellCommand(implode(' ', ['git', 'checkout', getValue('GIT_BRANCH', $app)]));
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

# vi:ft=php
?>
