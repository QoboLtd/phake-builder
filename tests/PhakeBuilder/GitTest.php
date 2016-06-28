<?php
namespace PhakeBuilder\Tests;

class GitTest extends \PHPUnit_Framework_TestCase
{

    public function testGetCurrentHash()
    {
        $git = new \PhakeBuilder\Git('foobar');
        $result = $git->getCurrentHash();
        $this->assertEquals('foobar log -1 --pretty=format:"%h"', $result, "Invalid git command [$result]");
    }

    public function testGetCurrentBranch()
    {
        $git = new \PhakeBuilder\Git('foobar');
        $result = $git->getCurrentBranch();
        $this->assertEquals('foobar rev-parse --abbrev-ref HEAD', $result, "Invalid git command [$result]");
    }

    public function testChangelog()
    {
        $git = new \PhakeBuilder\Git('foobar');
        $result = $git->changelog('one', 'two', 'three');
        $this->assertEquals('foobar log one..two three', $result, "Invalid git command [$result]");
    }

    public function testCheckout()
    {
        $git = new \PhakeBuilder\Git('foobar');
        $result = $git->checkout('blah');
        $this->assertEquals('foobar checkout blah', $result, "Invalid git command [$result]");
    }

    public function testPull()
    {
        $git = new \PhakeBuilder\Git('foobar');
        $result = $git->pull('blah', 'meh');
        $this->assertEquals('foobar pull blah meh', $result, "Invalid git command [$result]");
    }

    public function testPush()
    {
        $git = new \PhakeBuilder\Git('foobar');
        $result = $git->push('blah', 'meh');
        $this->assertEquals('foobar push blah meh', $result, "Invalid git command [$result]");
    }
}
