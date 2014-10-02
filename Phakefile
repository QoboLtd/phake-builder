<?php
require_once 'vendor/autoload.php';
Dotenv::load(getcwd());

group('db', function() {

	desc('Test database connection');
	task('connect', function() {
		writeln('Testing database connection with given credentials');

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

# vi:ft=php
?>
