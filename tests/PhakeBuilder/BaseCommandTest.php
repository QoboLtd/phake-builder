<?php
namespace PhakeBuilder\Tests;

require_once 'EmptyCommand.php';
require_once 'GetCommand.php';

class BaseCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructorException()
    {
        $testCommand = new EmptyCommand("");
    }

    public function testConstructor()
    {
        $testCommand = new GetCommand('foobar');
        $result = $testCommand->getCommand();
        $this->assertEquals('foobar', $result, "BaseCommand constructor does not set the command property");
    }
}
