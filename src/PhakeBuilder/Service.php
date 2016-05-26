<?php
namespace PhakeBuilder;

/**
 * Service Helper Class
 *
 * This class helps with starting and stopping system services.
 * The commands are not actually executed, but returned as strings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Service extends BaseCommand
{

    /**
     * Service command string
     */
    protected $command = 'service';

    /**
     * Start system service
     *
     * @param  string $service Service to start
     * @return string
     */
    public function start($service)
    {
        $result = $this->command . ' start ' . $service;
        return $result;
    }

    /**
     * Stop system service
     *
     * @param  string $service Service to stop
     * @return string
     */
    public function stop($service)
    {
        $result = $this->command . ' stop ' . $service;
        return $result;
    }
}
