<?php
namespace PhakeBuilder\Tests;

/**
 * This class is used for testing abstract BaseCommand class
 */
class GetCommand extends \PhakeBuilder\BaseCommand
{
    public function getCommand()
    {
        return $this->command;
    }
}
