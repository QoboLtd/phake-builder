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
class Composer extends BaseCommand
{

    /**
     * Composer command string
     */
    protected $command = 'composer --no-interaction --no-dev';

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
