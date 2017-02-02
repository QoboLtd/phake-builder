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
class Service extends BaseSimpleCommand
{
    /**
     * Service command string
     */
    protected $command = 'service';
}
