<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace PhakeBuilder;

/**
 * System class
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class System
{

    /**
     * @var array $defaults Parameter defaults
     */
    public static $defaults = [
        'PHAKE_BUILDER_LOG_LEVEL' => 'INFO',

        'GIT_REMOTE' => 'origin',
        'GIT_BRANCH' => '',

        'DB_HOST' => 'localhost',
        'DB_USER' => 'root',
        'DB_PASS' => '',

        'DB_ADMIN_USER' => 'root',
        'DB_ADMIN_PASS' => '',

        'SYSTEM_COMMAND_MYSQL' => 'mysql',
        'SYSTEM_COMMAND_MYSQL_REPLACE' => './vendor/bin/srdb.cli.php',
        'SYSTEM_COMMAND_SERVICE' => 'service',
        'SYSTEM_COMMAND_SUDO' => 'sudo',
        'SYSTEM_COMMAND_LETSENCRYPT' => '/opt/letsencrypt/certbot-auto',
    ];

    /**
     * Get default configuration value for given parameter
     *
     * If parameter is omitted, then all defaults are returned
     *
     * @param  string $param Parameter to get default value for
     * @return array|string|null All values, string if found, null otherwise
     */
    public static function getDefaultValue($param = null)
    {
        $result = null;

        if (empty($param)) {
            return static::$defaults;
        }

        if (isset(static::$defaults[$param])) {
            $result = static::$defaults[$param];
        }

        return $result;
    }

    /**
     * Check if the current user needs sudo
     *
     * root user doesn't need sudo.  Everybody else does.
     *
     * This functionality is outside of targets for future
     * proof.  One day we might need a more complex way to
     * figure the answer to this question.  For example,
     * based on a while of some parameter.
     *
     * @return boolean True if needs, false otherwise
     */
    public static function needsSudo()
    {
        $result = (posix_getuid() == 0) ? false : true;
        return $result;
    }

    /**
     * Execute a shell command
     *
     * Execute the shell command and return all output
     * as a string.  If the execution failed, then
     * throw a RuntimeException with the full output
     * as the exception message.
     *
     * @throws \RuntimeException
     * @param  string $command Command to execute
     * @return string Output
     */
    public static function doShellCommand($command)
    {
        $result = '';

        unset($output);
        $result = exec($command, $output, $return);
        $result = implode("\n", $output);
        if ($return > 0) {
            throw new \RuntimeException($result);
        }

        return $result;
    }

    /**
     * Secure string for screen output
     *
     * @param  string       $string      String to secure
     * @param  string|array $privateInfo One or more strings to secure
     * @param  string       $padWith     String to use for replacement
     * @return string
     */
    public static function secureString($string, $privateInfo, $padWith = 'x')
    {
        $result = $string;

        if (empty($privateInfo)) {
            return $result;
        }

        if (!is_array($privateInfo)) {
            $privateInfo = array($privateInfo);
        }

        foreach ($privateInfo as $privateString) {
            $replacement = str_repeat($padWith, strlen($privateString));
            $result = str_replace($privateString, $replacement, $result);
        }

        return $result;
    }
}
