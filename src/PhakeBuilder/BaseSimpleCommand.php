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
 * BaseSimpleCommand Helper Class
 *
 * Base class for command-line helper classes, which
 * mostly deal with simple commands, for which the
 * command string can be constructed by just appending
 * arguments.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 * @abstract
 */
abstract class BaseSimpleCommand extends BaseCommand
{
    /**
     * Default options for commands
     */
    protected $defaultOptions = [];

    /**
     * Dynamic support for commands
     *
     * Example usage:
     *
     * ```
     * $composer = new Composer('/bin/composer');
     * $command = $composer->update(); // 'composer update' + default options
     * $command = $composer->update('foo bar'); // 'composer update foo bar'
     * $command = $composer->update(['foo', 'bar']); // same as above
     * ```
     *
     * @param string $name Function to call
     * @param array $arguments Arguments to pass to function
     * @return string
     */
    public function __call($name, array $arguments)
    {
        $result = $this->command . ' ' . $name;

        $options = null;
        if (!empty($arguments)) {
            $options = $arguments[0];
        }

        if (empty($options) && !empty($this->defaultOptions[$name])) {
            $options = $this->defaultOptions[$name];
        }

        if (is_array($options)) {
            $options = implode(' ', $options);
        }

        /**
         * This should be fine for:
         *
         * * Empty options
         * * Scalar options
         * * Array options, that we've converted to string above
         * * Objects that can be cast to string via implemented __toString() method
         *
         * So pretty much the only time it will break is if options are
         * an object that cannot be cast to string.  You've been warned!
         */
        $options = (string)$options;

        $result = trim($this->command . ' ' . $name . ' ' . $options);

        return $result;
    }
}
