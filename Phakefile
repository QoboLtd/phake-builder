<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

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

// Load everything from the current folder
loadPhakefileParts(__DIR__);

# vi:ft=php
?>
