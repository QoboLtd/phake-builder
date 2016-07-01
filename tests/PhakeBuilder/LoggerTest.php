<?php
namespace PhakeBuilder\Tests;

class LoggerTest extends \PHPUnit_Framework_TestCase
{

    public function testGetLogger()
    {
        $result = \PhakeBuilder\Logger::getLogger();
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $result, "Result does not implement Psr\Log\LoggerInterface");
    }

    public function testSetLoggerDefault()
    {
        \PhakeBuilder\Logger::setLogger();
        $result = \PhakeBuilder\Logger::getLogger();
        $this->assertTrue(is_object($result), 'Setting logger is broker');
    }

    public function testSetLoggerObject()
    {
        $expected = new \StdClass();
        \PhakeBuilder\Logger::setLogger(null, $expected);
        $result = \PhakeBuilder\Logger::getLogger();
        $this->assertTrue(is_object($result), 'Setting logger is broker');
        $this->assertEquals($expected, $result, 'Set logger is modified');
    }

    public function testGetFormatter()
    {
        $result = \PhakeBuilder\Logger::getFormatter();
        $this->assertInstanceOf('Monolog\Formatter\FormatterInterface', $result, "Result does not implement Monolog\Formatter\FormatterInterface");
    }

    public function testGetHandler()
    {
        $result = \PhakeBuilder\Logger::getHandler();
        $this->assertInstanceOf('Monolog\Handler\HandlerInterface', $result, "Result does not implement Monolog\Handler\HandlerInterface");
    }

    public function testGetColors()
    {
        $result = \PhakeBuilder\Logger::getColors();
        $this->assertTrue(is_array($result), "Colors is not an array");
    }

    public function testSetColors()
    {
        $expected = array('foo' => 'bar');
        \PhakeBuilder\Logger::setColors($expected);
        $result = \PhakeBuilder\Logger::getColors();
        $this->assertEquals($expected, $result, "setColor() failed to set colors");
    }
}
