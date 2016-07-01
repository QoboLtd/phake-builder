<?php
namespace PhakeBuilder\Tests;

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        \PhakeBuilder\Logger::resetAll();
    }

    public function testResetAll()
    {
        $colors = array('debug' => 'black-and-white');
        \PhakeBuilder\Logger::setColors($colors);
        $result = \PhakeBuilder\Logger::getColors();
        $this->assertEquals($colors, $result, "Setting colors is broken");

        \PhakeBuilder\Logger::resetAll();

        $result = \PhakeBuilder\Logger::getColors();
        $this->assertFalse($colors == $result, "resetAll() is broken");
    }

    public function testDefaultProcessor()
    {
        $record = array(
            'color' => 'black-and-white',
            'level' => 'debug',
        );
        $result = \PhakeBuilder\Logger::defaultProcessor($record);
        $this->assertTrue(is_array($result), 'Default processor is broken');
        $this->assertEquals($record['level'], $result['level'], 'Default processor breaks record');
        $this->assertFalse($record['color'] == $result['color'], 'Default processor does not handle color');
    }

    public function testSetProcessorDefault()
    {
        \PhakeBuilder\Logger::setProcessor();
        $result = \PhakeBuilder\Logger::getProcessor();
        $this->assertTrue(is_callable($result), "setProcessor() sets a non-callable processor");
    }

    public function testSetProcessor()
    {
        $processor = '\\PhakeBuilder\\Logger::defaultProcessor';
        \PhakeBuilder\Logger::setProcessor($processor);
        $result = \PhakeBuilder\Logger::getProcessor();
        $this->assertEquals($processor, $result, "Setting default processor is broken");
    }

    public function testGetProcessor()
    {
        $result = \PhakeBuilder\Logger::getProcessor();
        $this->assertTrue(is_callable($result), "getProcessor() returns a non-callable");
    }

    public function testGetLogger()
    {
        $result = \PhakeBuilder\Logger::getLogger();
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $result, "Result does not implement Psr\Log\LoggerInterface");
    }

    public function testSetLoggerDefault()
    {
        \PhakeBuilder\Logger::setLogger();
        $result = \PhakeBuilder\Logger::getLogger();
        $this->assertTrue(is_object($result), 'Setting logger is broken');
    }

    public function testSetLoggerObject()
    {
        $expected = new \StdClass();
        \PhakeBuilder\Logger::setLogger(null, $expected);
        $result = \PhakeBuilder\Logger::getLogger();
        $this->assertTrue(is_object($result), 'Setting logger is broken');
        $this->assertEquals($expected, $result, 'Set logger is modified');
    }

    public function testSetFormatter()
    {
        $expected = new \StdClass();
        \PhakeBuilder\Logger::setFormatter($expected);
        $result = \PhakeBuilder\Logger::getFormatter();
        $this->assertEquals($expected, $result, "Set formatter is broken");
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

    public function testSetHandlerDefault()
    {
        \PhakeBuilder\Logger::setHandler();
        $result = \PhakeBuilder\Logger::getHandler();
        $this->assertTrue(is_object($result), 'Setting handler is broken');
    }

    public function testSetHandlerObject()
    {
        $expected = new \StdClass();
        \PhakeBuilder\Logger::setHandler(null, $expected);
        $result = \PhakeBuilder\Logger::getHandler();
        $this->assertEquals($expected, $result, 'Setting handler object is broken');
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
