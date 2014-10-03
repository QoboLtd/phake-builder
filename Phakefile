<?php
require_once 'vendor/autoload.php';

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

	desc('Git pull');
	task('pull', ':builder:init', function() {

		Dotenv::required(['GIT_REMOTE', 'GIT_BRANCH']);
		$result = exec(implode(' ', ['git', 'pull', getenv('GIT_REMOTE'), getenv('GIT_BRANCH')]), $output, $return);
		if ($return > 0) {
			throw new RuntimeException("Failed to pull from git: $result");
		}
		writeln(green(implose("\n", $output)));
	});
});

# vi:ft=php
?>
