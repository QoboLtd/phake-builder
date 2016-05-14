<?php
namespace PhakeBuilder\Tests;

class HipChatTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \GorkaLaucirica\HipchatAPIv2Client\Exception\RequestException
     */
    public function testMessage()
    {
        $result = \PhakeBuilder\HipChat::message('token', 'room', 'msg');
        $this->assertNul($result, "Non-null result from message");
    }
}
