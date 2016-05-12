<?php
namespace PhakeBuilder\Tests;

class ServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testStart()
    {
        $service = new \PhakeBuilder\Service('foobar');
        $result = $service->start('blah');
        $this->assertEquals('foobar start blah', $result, "Invalid service command [$result]");
    }

    public function testStop()
    {
        $service = new \PhakeBuilder\Service('foobar');
        $result = $service->stop('blah');
        $this->assertEquals('foobar stop blah', $result, "Invalid service command [$result]");
    }
}
