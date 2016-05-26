<?php
require_once 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (!function_exists('phakeGetBuildDirs')) {
	/**
	 * Get build directories
	 *
	 * Build directories will be removed and recreated during
	 * the build:clean task.
	 *
	 * @return array
	 */
	function phakeGetBuildDirs() {
		$result = array(
			'build/coverage',
			'build/logs',
			'build/pdepend',
		);

		return $result;
	}
}

if (!function_exists('phakeDirHasPHPFiles')) {
	/**
	 * Check if the directory has any .php files (recursively)
	 *
	 * @param string $dir Directory to check
	 * @return boolean True if at least one file is found, false otherwise
	 */
	function phakeDirHasPHPFiles($dir) {
		$result = false;

		try {
			$dirIt = new RecursiveDirectoryIterator($dir);
			$recIt = new RecursiveIteratorIterator($dirIt);
			$regexIt = new RegexIterator($recIt, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
			if (iterator_count($regexIt) > 0) {
				$result = true;
			}
		} catch (\Exception $e) {
			$result = false;
		}

		return $result;
	}
}
if (!function_exists('phakeGetBuildCommands')) {
	/**
	 * Get all test and build commands
	 *
	 * @return array
	 */
	function phakeGetBuildCommands() {
		$result = array(
			'phpunit' => './vendor/bin/phpunit',
			'phpcs' => './vendor/bin/phpcs -n -p --extensions=php --standard=PSR2 src/ tests/',
			'phpcs-ci' => './vendor/bin/phpcs -n -p --extensions=php --standard=PSR2 src/ tests/ --report=checkstyle --report-file=build/logs/checkstyle.xml',
			'pdepend' => './vendor/bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/pdepend/dependecies.svg --overview-pyramid=build/pdepend/overview-pyramid.svg src/',
			'phploc' => './vendor/bin/phploc --count-tests --log-csv build/logs/phploc.csv --log-xml build/logs/phploc.xml src/ tests/',
			'phpmd' => './vendor/bin/phpmd src/ text codesize,controversial,naming,unusedcode',
			'phpmd-ci' => './vendor/bin/phpmd src/ xml codesize,controversial,naming,unusedcode --reportfile build/logs/phpmd.xml',
			'phpcpd' => './vendor/bin/phpcpd --log-pmd=build/logs/phpcpd.xml src/',
		);

		$hasSrc = phakeDirHasPHPFiles('./src');
		$hasTests = phakeDirHasPHPFiles('./tests');

		if (!$hasTests) {
			printWarning("No unit test files found. Skipping phpnit");
			$result['phpunit'] = null;
		}
		if (!$hasSrc) {
			printWarning("No src files found. Skipping pdepend, phpmd, phpmd-ci, phpcpd");
			$result['pdepend'] = null;
			$result['phpmd'] = null;
			$result['phpmd-ci'] = null;
			$result['phpcpd'] = null;
		}

		if (!$hasSrc && !$hasTests) {
			printWarning("No unit test or source files found. Skipping phpcs, phpcs-ci, phploc");
			$result['phpcs'] = null;
			$result['phpcs-ci'] = null;
			$result['phploc'] = null;
		}

		return $result;
	}
}

// Build related tasks
group(
	'build', function() {
		desc('All build tasks');
		task(
			'all', ':builder:init', function ($app) {
				printSeparator();
				printInfo("Build tasks");

				$commands = phakeGetBuildCommands();

				$failedCommands = array();
				foreach ($commands as $name => $command) {
					try {
						doShellCommand($command);
					}
					catch (\Exception $e) {
						$failedCommands[$name] = $e->getMessage();
					}
				}

				if (!empty($failedCommands)) {
					throw new \RuntimeException("The following failures occured during execution: \n" . print_r($failedCommands, true));
				}
			}
		);
		task('all', 'build:clean');
		task('all', 'sami:update');

		desc('Clean');
		task(
			'clean', ':builder:init', function ($app) {
				printSeparator();
				printInfo("Clean");

				$dirs = phakeGetBuildDirs();
				foreach ($dirs as $dir) {
					\PhakeBuilder\FileSystem::removePath($dir);
					\PhakeBuilder\FileSystem::makeDir($dir);
				}
			}
		);

		desc('PHPUnit');
		task(
			'phpunit', ':builder:init', function ($app) {
				printSeparator();
				printInfo("PHPUnit");

				$commands = phakeGetBuildCommands();
				if (!empty($commands['phpunit'])) {
					doShellCommand($commands['phpunit']);
				}
			}
		);

		desc('CodeSniffer');
		task(
			'phpcs', ':builder:init', function ($app) {
				printSeparator();
				printInfo("CodeSniffer");

				$commands = phakeGetBuildCommands();
				if (!empty($commands['phpcs-ci'])) {
					try {
						// This time for the build/logs/checkstyle.xml
						doShellCommand($commands['phpcs-ci']);
					}
					catch (\Exception $e) {
					}

				}
				// This time for the developer and human-friendly output
				if (!empty($commands['phpcs'])) {
					doShellCommand($commands['phpcs']);
				}
			}
		);

		desc('PDepend');
		task(
			'pdepend', ':builder:init', function ($app) {
				printSeparator();
				printInfo('PDepend');

				$commands = phakeGetBuildCommands();
				if (!empty($commands['pdepend'])) {
					doShellCommand($commands['pdepend']);
				}
			}
		);

		desc('PHPLoc');
		task(
			'phploc', ':builder:init', function ($app) {
				printSeparator();
				printInfo('PHPLoc');

				$commands = phakeGetBuildCommands();
				if (!empty($commands['phploc'])) {
					doShellCommand($commands['phploc']);
				}
			}
		);

		desc('PHPMD');
		task(
			'phpmd', ':builder:init', function($app) {
				printSeparator();
				printInfo('PHPMD');

				$commands = phakeGetBuildCommands();
				if (!empty($commands['phpmd-ci'])) {
					try {
						// This time for the build/logs/phpmd.xml
						doShellCommand($commands['phpmd-ci']);
					}
					catch (\Exception $e) {
					}

				}
				// This time for the developer and human-friendly output
				if (!empty($commands['phpmd'])) {
					doShellCommand($commands['phpmd']);
				}

				$commands = phakeGetBuildCommands();
				if (!empty($commands['phpmd'])) {
					doShellCommand($commands['phpmd']);
				}
			}
		);

		desc('PHPCPD');
		task(
			'phpcpd', ':builder:init', function($app) {
				printSeparator();
				printInfo('PHPCPD');

				$commands = phakeGetBuildCommands();
				if (!empty($commands['phpcpd'])) {
					doShellCommand($commands['phpcpd']);
				}
			}
		);

	}
);
