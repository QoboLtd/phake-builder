<?php
namespace PhakeBuilder;

/**
 * BaseCommand Helper Class
 *
 * Base class for command-line helper classes
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 * @abstract
 */
abstract class BaseCommand implements Command
{

    /**
     * Command string
     */
    protected $command;

    /**
     * Constructor
     *
     * @throws \RuntimeException
     * @param  string $command Path to executable
     * @return object
     */
    public function __construct($command = null)
    {
        if (!empty($command)) {
            $this->command = $command;
        }

        if (empty($this->command)) {
            throw new \RuntimeException("Command not defined");
        }
    }
}
