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
        $phakefilesPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR
            . 'src' . DIRECTORY_SEPARATOR
            . 'Phakefiles';
        $result = \PhakeBuilder\Utils::findPhakefileParts($phakefilesPath);
        $this->assertTrue(is_array($result), 'List of found Phakefiles is not an array');
        $this->assertFalse(empty($result), 'No Phakefiles found');
    }

    public function testGetCurrentDir()
    {
        $cwdNoTrail = \PhakeBuilder\Utils::getCurrentDir(false);
        $this->assertTrue(is_string($cwdNoTrail), "getCurrentDir() returned a non-string for no trail");
        $this->assertFalse(empty($cwdNoTrail), "getCurrentDir() returned empty result for no trail");

        $cwdWithTrail = \PhakeBuilder\Utils::getCurrentDir();
        $this->assertTrue(is_string($cwdWithTrail), "getCurrentDir() returned a non-string for trail");
        $this->assertFalse(empty($cwdWithTrail), "getCurrentDir() returned empty result for trail");

        $this->assertEquals($cwdWithTrail, $cwdNoTrail . DIRECTORY_SEPARATOR, "getCurrentDir() trail/no-trail is broken");
    }
}
