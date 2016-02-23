<?php
namespace PhakeBuilder\Tests;

class ComposerTest extends \PHPUnit_Framework_TestCase
{

    public function testInstall()
    {
        $composer = new \PhakeBuilder\Composer('foobar');
        $result = $composer->install();
        $this->assertEquals('foobar install', $result, "Invalid composer command [$result]");
    }

    public function testUpdate()
    {
        $composer = new \PhakeBuilder\Composer('foobar');
        $result = $composer->update();
        $this->assertEquals('foobar update', $result, "Invalid composer command [$result]");
    }
}
