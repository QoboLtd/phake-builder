<?php
require_once 'vendor/autoload.php';

/**
 * Execute a shell command
 * 
 * @param string $command Command to execute
 * @return void
 */
function doShellCommand($command) {
	$command .= ' 2>&1';
	
	writeln(purple("Executing shell command: $command"));
	
	unset($output);
	$result = exec($command, $output, $return);
	if ($return > 0) {
		throw new RuntimeException("Failed! " . implode("\n", $output));
	}
	writeln(green("Success. Output: \n" . implode("\n", $output)));
}

// Generic phake-builder tasks
group('builder', function() {
	
	desc('Initialize builder configuration');
	task('init', function() {
		Dotenv::load(getcwd());
	});
	
});

// MySQL utiility tasks
group('mysql', function() {

	desc('Test MySQL database connection');
	task('connect', ':builder:init', function() {
		writeln('Testing MySQL database connection with given credentials');

		Dotenv::required(['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME']);
		
		writeln(yellow('TODO: switch to mysqli or PDO'));
		$db = mysql_connect(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'));
		if (!is_resource($db)) {
			throw new RuntimeException("Failed to connect to the database: " . mysql_error());
		}
		if (!mysql_select_db(getenv('DB_NAME'), $db)) {
			throw new RuntimeException("Failed to select the database: " . mysql_error());
		}
		mysql_close($db);
		
		writeln(green('DB connection successfully established'));
	});

});

// Git related tasks
group('git', function() {
	
	desc('Git checkout');
	task('checkout', ':builder:init', function() {
		Dotenv::required(['GIT_BRANCH']);
		doShellCommand(implode(' ', ['git', 'checkout', getenv('GIT_BRANCH')]));
	});

	desc('Git pull');
	task('pull', ':builder:init', function() {
		Dotenv::required(['GIT_REMOTE', 'GIT_BRANCH']);
		doShellCommand(implode(' ', ['git', 'pull', getenv('GIT_REMOTE'), getenv('GIT_BRANCH')]));
	});
	
	desc('Git push');
	task('push', ':builder:init', function() {
		Dotenv::required(['GIT_REMOTE', 'GIT_BRANCH']);
		doShellCommand(implode(' ', ['git', 'push', getenv('GIT_REMOTE'), getenv('GIT_BRANCH')]));
	});

});

# vi:ft=php
?>
