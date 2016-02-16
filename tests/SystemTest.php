<?php
namespace PhakeBuilder\Tests;

use PhakeBuilder\System;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'System.php';

class SystemTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDefaultValue()
    {
        // Known value
        $result = System::getDefaultValue('GIT_REMOTE');
        $this->assertEquals('origin', $result);

        // Unknown value
        $result = System::getDefaultValue('THIS_VALUE_WILL_NEVER_EXIST');
        $this->assertNull($result);
    }

    public function testNeedsSudo()
    {
        $uid = posix_getuid();
        $result = System::needsSudo();
        $expected = ($uid == 0) ? false : true;
        $this->assertEquals($expected, $result);
    }
 
    /**
     * @expectedException \RuntimeException
     */
    public function testDoShellCommandException()
    {
        $result = System::doShellCommand('THIS_COMMAND_WILL_NEVER_EXIST 2>/dev/null');
    }

    public function testDoShellCommand()
    {
        $result = System::doShellCommand('ls');
        $this->assertFalse(empty($result));
    }

    public function testSecureString()
    {
        $result = System::secureString('foobar', 'bar');
        $this->assertEquals('fooxxx', $result);

        $result = System::secureString('foobar', '');
        $this->assertEquals('foobar', $result);

        $result = System::secureString('foobar', array('foo','bar'));
        $this->assertEquals('xxxxxx', $result);

        $result = System::secureString('foobar', 'bar', '*');
        $this->assertEquals('foo***', $result);
    }
}
