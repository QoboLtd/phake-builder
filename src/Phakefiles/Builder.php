<?php
///////////////////////
// Utility functions //
///////////////////////
/**
 * Print separator
 *
 * This is mighty useful in long outputs
 *
 * @param  string  $string String to use for separator
 * @param  integer $length Length of the separator line
 * @return void
 */
function printSeparator($character = '-', $length = 70)
{
    printInfo(str_repeat($character, $length), false);
}

/**
 * Format output message
 *
 * @param  string $message    Message text
 * @param  string $prefix     Prefix text, like DEBUG or INF
 * @param  string $dateFormat Date format to use. If null, no date is used
 * @return string
 */
function formatMessage($message, $prefix, $dateFormat = '[Y-m-d H:i:s]')
{
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
 * @param  string  $message Message to show as error
 * @param  boolean $format  Format the message or print as is
 * @return void
 */
function printError($message, $format = true, $returnNoPrint = false)
{
    if ($format) {
        $message = formatMessage($message, ':ERROR:');
    }

    if ($returnNoPrint) {
        return $message;
    }
    \PhakeBuilder\Logger::getLogger(getenv('PHAKE_BUILDER_LOG_LEVEL'))->error($message);
}

/**
 * Print warning message
 *
 * @param  string  $message Message to show as warning
 * @param  boolean $format  Format the message or print as is
 * @return void
 */
function printWarning($message, $format = true)
{
    if ($format) {
        $message = formatMessage($message, ':WARN :');
    }
    \PhakeBuilder\Logger::getLogger(getenv('PHAKE_BUILDER_LOG_LEVEL'))->warning($message);
}

/**
 * Print success message
 *
 * @param  string  $message Message to show as success
 * @param  boolean $format  Format the message or print as is
 * @return void
 */
function printSuccess($message, $format = true)
{
    if ($format) {
        $message = formatMessage($message, ':OK   :');
    }
    \PhakeBuilder\Logger::getLogger(getenv('PHAKE_BUILDER_LOG_LEVEL'))->notice($message);
}

/**
 * Print info message
 *
 * @param  string  $message Message to show as info
 * @param  boolean $format  Format the message or print as is
 * @return void
 */
function printInfo($message, $format = true)
{
    if ($format) {
        $message = formatMessage($message, ':INFO :');
    }
    \PhakeBuilder\Logger::getLogger(getenv('PHAKE_BUILDER_LOG_LEVEL'))->info($message);
}

/**
 * Print debug message
 *
 * @param  string  $message Message to show as debug
 * @param  boolean $format  Format the message or print as is
 * @return void
 */
function printDebug($message, $format = true)
{
    if ($format) {
        $message = formatMessage($message, ':DEBUG:');
    }
    \PhakeBuilder\Logger::getLogger(getenv('PHAKE_BUILDER_LOG_LEVEL'))->debug($message);
}

/**
 * Find a value for configuration parameter
 *
 * @param  $string $param Name of the configuration parameter
 * @param  array  $app   Application command line parameters
 * @return string
 */
function getValue($param, $app = null)
{
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
    $result = null;

    // Default is third
    $default = \PhakeBuilder\System::getDefaultValue($param);
    if ($default !== null) {
        printDebug("No value for $param has been given.  Using default.");
        $result = $default;
    }

    // Null is last
    return $result;
}

/**
 * Find a required value for configuration parameter
 *
 * @param  $string $param Name of the configuration parameter
 * @param  array  $app   Application command line parameters
 * @return string
 */
function requireValue($param, $app = null)
{
    $result = getValue($param, $app);
    if (empty($result)) {
        throw new RuntimeException(printError("Missing required configuration parameter for $param", true, true));
    }

    return $result;
}

/**
 * Execute a shell command
 *
 * @param  string|array $command     Command to execute (as full string or parts)
 * @param  string|array $privateInfo One or more strings to remove from screen output
 * @param  boolean      $silent      Whether or not to supress output
 * @return string
 */
function doShellCommand($command, $privateInfo = null, $silent = false)
{
    if (is_array($command)) {
        $command = implode(' ', array_map('trim', $command));
    }
    $command = trim($command) . ' 2>&1';
    printDebug("Executing shell command: " . \PhakeBuilder\System::secureString($command, $privateInfo));

    try {
        $result = \PhakeBuilder\System::doShellCommand($command);
        $result = \PhakeBuilder\system::secureString($result, $privateInfo);
    } catch (Exception $e) {
        $result = \PhakeBuilder\System::secureString($e->getMessage(), $privateInfo);
        throw new RuntimeException(printError($result, true, true));
    }
    if (!$silent) {
        if (!empty(trim($result))) {
            printSuccess("SUCCESS! Output:\n" . $result);
        } else {
            printSuccess("SUCCESS!");
        }
    }
    return $result;
}

/**
 * Execute a MySQL command
 *
 * @param  string $query MySQL query to execute
 * @return void
 * @deprecated
 */
function doMySQLCommand($app, $query, $requireDB = true, $asAdmin = false, $command = 'SYSTEM_COMMAND_MYSQL')
{

    printWarning("doMySQLCommand() function is deprecated. Use \PhakeBuilder\MySQL instead.");

    // Host is never required, but always nice to have
    $host = getValue('DB_HOST', $app);

    if ($asAdmin) {
        // Use admin credentials for admin operations
        $user = requireValue('DB_ADMIN_USER', $app);
        $pass = getValue('DB_ADMIN_PASS', $app);
    } else {
        // use regular user credentials for everything else
        $user = getValue('DB_USER', $app);
        $pass = getValue('DB_PASS', $app);
    }

    // Not everything requires a known database name parameter
    $name = null;
    if ($requireDB) {
        $name = requireValue('DB_NAME', $app);
    }

    $dsn = array(
        'host' => $host,
        'user' => $user,
        'pass' => $pass,
        'name' => $name,
    );
    $mysql = new \PhakeBuilder\MySQL(requireValue($command, $app));
    $mysql->setDSN($dsn);

    // Build command line strings for different commands
    switch ($command) {
        // /usr/bin/mysql -u root -p foo -h localhost -e 'select now();'
        case 'SYSTEM_COMMAND_MYSQL':
            if (empty($query)) {
                throw new RuntimeException(printError("No SQL query given", true, true));
            }
            $command = $mysql->query($query);
            break;
        // ./vendor/bin/srdb.cli.php -h localhost -h localhost -u root -p 'foo' -n dbname -s 'find' -r 'replace'
        case 'SYSTEM_COMMAND_MYSQL_REPLACE':
            $find = getValue('DB_FIND', $app);
            $replace = getValue('DB_REPLACE', $app);

            if (empty($find)) {
                printDebug("Nothing to find.  Skipping replace.");
                return;
            }

            $command = $mysql->findReplace($find, $replace);
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
group('builder', function () {

    desc('Initialize builder configuration');
    task('init', function ($app) {
        $hasDotEnv = false;
        try {
            Dotenv::load(getcwd());
            $hasDotEnv = true;
        } catch (Exception $e) {
            $hasDotEnv = false;
        }

        // Special treatment of log level to avoid
        // sending $app parameter to every log message
        $logLevel = getValue('PHAKE_BUILDER_LOG_LEVEL', $app);
        putenv("PHAKE_BUILDER_LOG_LEVEL=$logLevel");

        printInfo('Welcome to phake-builder!', false);
        printInfo('Use "phake -T" to list all commands.', false);
        printInfo('More info at https://github.com/QoboLtd/phake-builder', false);
        printSeparator();
        printInfo("Task: builder:init (Initialize builder configuration)");
        if (!$hasDotEnv) {
            printWarning("Failed to load .env configuration file");
        }
        printDebug('Phake-builder initialized');
    });

});
