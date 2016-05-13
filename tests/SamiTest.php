<?php
namespace PhakeBuilder\Tests;

class SamiTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUpdateException()
    {
        $sami = new \PhakeBuilder\Sami('foobar');
        $sami->update('blah');
    }

    public function testUpdate()
    {
        $sami = new \PhakeBuilder\Sami('foobar');
        $result = $sami->update(__FILE__);
        $this->assertEquals('foobar update ' . __FILE__, $result, "Invalid sami command [$result]");
    }

    public function testUpdateDefaultConfig()
    {
        $sami = new \PhakeBuilder\Sami('foobar');
        $result = $sami->update();
        $this->assertEquals('foobar update etc/sami.config.php', $result, "Invalid sami command [$result]");
    }

}
