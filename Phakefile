<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

/**
 * Set default timezone
 * 
 * phake-builder is using date() functionality a lot, especially
 * for things like logs and output.  If the machine doesn't have
 * a date.timezone configured in its php.ini file, PHP generates
 * warnings and notices, messing up the output and logs.
 * 
 * So, here we check if the setting is in, and if it's not, we
 * set the default timezone to UTC.
 * 
 * @param string $timezone Timezone to set as default (default: UTC)
 * @return void
 */
function setDefaultTimezone($timezone = 'UTC') {
	$dateTimezone = ini_get('date.timezone');
	if (empty($dateTimezone)) {
		date_default_timezone_set($timezone);
	}
}

/**
 * Load Phakefile parts
 * 
 * Automatically include all Phakefile.* files
 * from a given folder
 * 
 * @param string $folder Folder path
 * @return void
 */
function loadPhakefileParts($folder) {
	$dir = new DirectoryIterator($folder);
	$regex = new RegexIterator($dir, '/^Phakefile\./');
	foreach ($regex as $item) {
		require_once $item->getRealpath();
	}
}


// Set default timezone
setDefaultTimezone();

// Load everything from the current folder
loadPhakefileParts(__DIR__);

# vi:ft=php
?>
