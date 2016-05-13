<?php
namespace PhakeBuilder\Tests;

class BaseCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructorException()
    {
        $testCommand = new EmptyCommand(null);
    }

    public function testConstructor()
    {
        $testCommand = new GetCommand('foobar');
        $result = $testCommand->getCommand();
        $this->assertEquals('foobar', $result, "BaseCommand constructor does not set the command property");
    }
}
