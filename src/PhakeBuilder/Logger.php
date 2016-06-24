<?php
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
    protected static $colors;

    /**
     * Get logger object
     *
     * @param string $level Log level
     * @return object
     */
    public static function getLogger($level = self::DEFAULT_LEVEL)
    {
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
    public static function setLogger($level = self::DEFAULT_LEVEL, $logger = null)
    {
        if (!empty($logger)) {
            static::$logger = $logger;
            return;
        }

        static::$logger = new \Monolog\Logger("log");

        $handler = static::getHandler($level);
        static::$logger->pushHandler($handler);

        static::$logger->pushProcessor(
            function ($record) {

                $colors = static::getColors();

                $record['color'] = $colors[ $record['level'] ] ?: self::DEFAULT_COLOR;
                return $record;
            }
        );
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
            \Monolog\Logger::INFO      => 'white',
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
    public static function getHandler($level = self::DEFAULT_LEVEL)
    {
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
    public static function setHandler($level = self::DEFAULT_LEVEL, $handler = null)
    {

        if (!empty($handler)) {
            static::$handler = $handler;
            return;
        }

        static::$handler = new \Monolog\Handler\StdoutHandler(constant("\Monolog\Logger::$level"));
        static::$handler->setFormatter(static::getFormatter());
    }
}
