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

use DirectoryIterator;
use RegexIterator;

/**
 * Utilities Class
 *
 * This class contains utility methods
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Utils
{

    const DEFAULT_TIMEZONE = 'UTC';

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
     * @param  string $timezone Timezone to set as default (default: UTC)
     * @param  boolean $force Whether or not to force the setting of timezone
     * @return void
     */
    public static function setDefaultTimezone($timezone = self::DEFAULT_TIMEZONE, $force = false)
    {
        $dateTimezone = ini_get('date.timezone');
        if (empty($dateTimezone) || $force) {
            date_default_timezone_set($timezone);
        }
    }

    /**
     * Find Phakefile parts
     *
     * Find all Phakefiles in a given folder
     *
     * @param  string $folder Folder path
     * @return array List of found files
     */
    public static function findPhakefileParts($folder)
    {
        $result = array();

        $dir = new DirectoryIterator($folder);
        $regex = new RegexIterator($dir, '/\.php$/');
        foreach ($regex as $item) {
            $result[] = $item->getRealpath();
        }

        return $result;
    }
}
