<?php
namespace PhakeBuilder;

/**
 * Composer Helper Class
 *
 * This class helps with running composer commands.  The commands
 * are not actually executed, but returned as strings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Composer
{

    /**
     * Default for composer command location and arguments
     */
    const DEFAULT_COMMAND = 'composer -n --no-dev';

    /**
     * Composer command string
     */
    protected $command;

    /**
     * Constructor
     *
     * @param  string $command Path to executable
     * @return object
     */
    public function __construct($command = self::DEFAULT_COMMAND)
    {
        $this->command = $command ?: self::DEFAULT_COMMAND;
    }

    /**
     * Install composer dependecies
     *
     * @return string
     */
    public function install()
    {
        $result = $this->command . ' install';
        return $result;
    }

    /**
     * Update composer dependecies
     *
     * @return string
     */
    public function update()
    {
        $result = $this->command . ' update';
        return $result;
    }
}
