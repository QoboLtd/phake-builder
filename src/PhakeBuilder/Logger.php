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
 * Logger Class
 *
 * This class helps to setup the logger
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Logger
{

    /**
     * Default log level
     */
    const DEFAULT_LEVEL = 'INFO';

    /**
     * Default log message color
     */
    const DEFAULT_COLOR = 'blue';

    protected static $logger;
    protected static $formatter;
    protected static $handler;
    protected static $processor;
    protected static $colors;

    public static function resetAll()
    {
        static::$logger = null;
        static::$formatter = null;
        static::$handler = null;
        static::$processor = null;
        static::$colors = null;
    }

    /**
     * Get logger object
     *
     * @param string $level Log level
     * @return object
     */
    public static function getLogger($level = null)
    {
        if (empty($level)) {
            $level = static::DEFAULT_LEVEL;
        }

        if (empty(static::$logger)) {
            static::setLogger($level);
        }

        return static::$logger;
    }

    /**
     * Set logger object
     *
     * If no logger is given, we assume a sensible default
     *
     * @param string $level Log level
     * @param object $logger Logger object
     * @return void
     */
    public static function setLogger($level = null, $logger = null)
    {
        if (empty($level)) {
            $level = static::DEFAULT_LEVEL;
        }

        if (!empty($logger)) {
            static::$logger = $logger;
            return;
        }

        static::$logger = new \Monolog\Logger("log");

        $handler = static::getHandler($level);
        static::$logger->pushHandler($handler);

        $processor = static::getProcessor();
        static::$logger->pushProcessor($processor);
    }

    /**
     * Get log processor object
     *
     * @rturn callable
     */
    public static function getProcessor()
    {
        if (empty(static::$processor)) {
            static::setProcessor();
        }
        return static::$processor;
    }

    /**
     * Set log processor
     *
     * If processor is not given, we assume a sensible default
     *
     * @param object $formatter Formatter object to use
     * @return void
     */
    public static function setProcessor($processor = null)
    {
        if (!empty($processor)) {
            static::$processor = $processor;
            return;
        }
        static::$processor = __CLASS__ . '::defaultProcessor';
    }

    public static function defaultProcessor($record)
    {
        $colors = static::getColors();

        $record['color'] = empty($colors[ $record['level'] ]) ? static::DEFAULT_COLOR : $colors[ $record['level'] ];
        return $record;
    }

    /**
     * Get log message colors configuration
     *
     * @return array
     */
    public static function getColors()
    {
        if (empty(static::$colors)) {
            static::setColors();
        }
        return static::$colors;
    }

    /**
     * Set log message colors configuration
     *
     * If no colors given, we assume a sensible default
     *
     * @param array $colors Associative array of log levels and colors
     * @return void
     */
    public static function setColors(array $colors = array())
    {
        if (!empty($colors)) {
            static::$colors = $colors;
            return;
        }
        static::$colors = array(
            \Monolog\Logger::DEBUG     => 'purple',
            \Monolog\Logger::INFO      => 'cyan',
            \Monolog\Logger::NOTICE    => 'green',
            \Monolog\Logger::WARNING   => 'yellow',
            \Monolog\Logger::ERROR     => 'red',
            \Monolog\Logger::CRITICAL  => 'red',
            \Monolog\Logger::ALERT     => 'red',
            \Monolog\Logger::EMERGENCY => 'red',
        );
    }

    /**
     * Get log formatter object
     *
     * @rturn object
     */
    public static function getFormatter()
    {
        if (empty(static::$formatter)) {
            static::setFormatter();
        }
        return static::$formatter;
    }

    /**
     * Set log formatter
     *
     * If formatter is not given, we assume a sensible default
     *
     * @param object $formatter Formatter object to use
     * @return void
     */
    public static function setFormatter($formatter = null)
    {
        if (!empty($formatter)) {
            static::$formatter = $formatter;
            return;
        }
        static::$formatter = new \Monolog\Formatter\ColorLineFormatter("[c=%color%]%message%[/c]\n", null, true, true);
    }

    /**
     * Get log handler
     *
     * @param string $level Log level
     * @return object
     */
    public static function getHandler($level = null)
    {
        if (empty($level)) {
            $level = static::DEFAULT_LEVEL;
        }

        if (empty(static::$handler)) {
            static::setHandler($level);
        }
        return static::$handler;
    }

    /**
     * Set log handler
     *
     * If no hanlder given, we assume a sensible default
     *
     * @param string $level Log level
     * @param  object $formatter Formatter intance
     * @return object
     */
    public static function setHandler($level = null, $handler = null)
    {
        if (empty($level)) {
            $level = static::DEFAULT_LEVEL;
        }

        if (!empty($handler)) {
            static::$handler = $handler;
            return;
        }

        static::$handler = new \Monolog\Handler\StdoutHandler(constant("\Monolog\Logger::$level"));
        static::$handler->setFormatter(static::getFormatter());
    }
}
