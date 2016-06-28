<?php
namespace PhakeBuilder\Tests;

class UtilsTest extends \PHPUnit_Framework_TestCase
{

    public function testSetDefaultTimezone()
    {
        \PhakeBuilder\Utils::setDefaultTimezone('UTC', true);
        $this->assertEquals('UTC', date_default_timezone_get(), 'Failed to force setting of default timezone');
    }

    public function testFindPhakefileParts()
    {
        $phakefilesPath = __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'src' . DIRECTORY_SEPARATOR
            . 'Phakefiles';
        $result = \PhakeBuilder\Utils::findPhakefileParts($phakefilesPath);
        $this->assertTrue(is_array($result), 'List of found Phakefiles is not an array');
        $this->assertFalse(empty($result), 'No Phakefiles found');
    }
}
