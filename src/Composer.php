<?php
namespace PhakeBuilder;

/**
 * Composer class
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Composer
{

    // Shorter form of --no-interaction
    const DEFAULT_COMMAND = 'composer -n --no-dev';

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
