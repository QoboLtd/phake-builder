<?php
namespace PhakeBuilder\Tests;

class ComposerTest extends \PHPUnit_Framework_TestCase
{

    public function testInstall()
    {
        $composer = new \PhakeBuilder\Composer('foobar');

        $result = $composer->install();
        $this->assertStringStartsWith('foobar install', $result, "Invalid composer command with default options [$result]");

        $options = ['one', 'two'];
        $result = $composer->install($options);
        $this->assertEquals('foobar install one two', $result, "Invalid composer command with given options [$result]");
    }

    public function testUpdate()
    {
        $composer = new \PhakeBuilder\Composer('foobar');

        $result = $composer->update();
        $this->assertStringStartsWith('foobar update', $result, "Invalid composer command with default options [$result]");

        $options = ['one', 'two'];
        $result = $composer->update($options);
        $this->assertEquals('foobar update one two', $result, "Invalid composer command with given options [$result]");
    }
}
