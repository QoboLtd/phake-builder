<?php
namespace PhakeBuilder;

/**
 * Command Interface
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
interface Command
{
    /**
     * Constructor
     *
     * @param string $command Path to executable
     * @return object
     */
    public function __construct($command = null);
}
