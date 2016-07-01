<?php
namespace PhakeBuilder\Tests;

class HipChatTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        \PhakeBuilder\HipChat::resetAll();
    }

    public function testResetAll()
    {
        \PhakeBuilder\HipChat::setFrom('foobar');
        \PhakeBuilder\HipChat::resetAll();
        $result = \PhakeBuilder\HipChat::getFrom();
        $this->assertEquals(\PhakeBuilder\HipChat::DEFAULT_FROM, $result, "Default From is broken");

        \PhakeBuilder\HipChat::setColor('black-and-white');
        \PhakeBuilder\HipChat::resetAll();
        $result = \PhakeBuilder\HipChat::getColor();
        $this->assertEquals(\PhakeBuilder\HipChat::DEFAULT_COLOR, $result, "Default color is broken");
    }

    public function testSetFrom()
    {
        $expected = 'Test HipChat From';
        \PhakeBuilder\HipChat::setFrom($expected);
        $result = \PhakeBuilder\HipChat::getFrom();
        $this->assertEquals($expected, $result, "Setting From is broken");
    }

    public function testGetFromDefault()
    {
        $result = \PhakeBuilder\HipChat::getFrom();
        $this->assertEquals(\PhakeBuilder\HipChat::DEFAULT_FROM, $result, "Default From is broken");
    }

    public function testSetColor()
    {
        $expected = 'black-and-white';
        \PhakeBuilder\HipChat::setColor($expected);
        $result = \PhakeBuilder\HipChat::getColor();
        $this->assertEquals($expected, $result, "Setting color is broken");
    }

    public function testGetColorDefault()
    {
        $result = \PhakeBuilder\HipChat::getColor();
        $this->assertEquals(\PhakeBuilder\HipChat::DEFAULT_COLOR, $result, "Default color is broken");
    }

    public function testSetToken()
    {
        $expected = 'token-123';
        \PhakeBuilder\HipChat::setToken($expected);
        $result = \PhakeBuilder\HipChat::getToken();
        $this->assertEquals($expected, $result, "Setting token is broken");
    }

    public function testSetAuth()
    {
        $expected = 'some auth';
        \PhakeBuilder\HipChat::setAuth($expected);
        $result = \PhakeBuilder\HipChat::getAuth();
        $this->assertEquals($expected, $result, "Setting auth is broken");
    }

    public function testGetAuthDefault()
    {
        $result = \PhakeBuilder\HipChat::getAuth();
        $this->assertTrue(is_object($result), $result, "Default auth is broken");
    }

    public function testSetClient()
    {
        $expected = 'some client';
        \PhakeBuilder\HipChat::setClient($expected);
        $result = \PhakeBuilder\HipChat::getClient();
        $this->assertEquals($expected, $result, "Setting client is broken");
    }

    public function testGetClientDefault()
    {
        $result = \PhakeBuilder\HipChat::getClient();
        $this->assertTrue(is_object($result), $result, "Default client is broken");
    }

    public function testSetRoomAPI()
    {
        $expected = 'some room api';
        \PhakeBuilder\HipChat::setRoomAPI($expected);
        $result = \PhakeBuilder\HipChat::getRoomAPI();
        $this->assertEquals($expected, $result, "Setting room API is broken");
    }

    public function testGetRoomAPIDefault()
    {
        $result = \PhakeBuilder\HipChat::getRoomAPI();
        $this->assertTrue(is_object($result), $result, "Default room API is broken");
    }

    public function testSetMessage()
    {
        $message = 'some message';
        \PhakeBuilder\HipChat::setMessage($message);
        $result = \PhakeBuilder\HipChat::getMessage();
        $this->assertTrue(is_object($result), "Setting message is broken");
    }

    public function testSetMessageWithParams()
    {
        $message = 'some message';
        $from = 'some from';
        $color = 'some color';
        \PhakeBuilder\HipChat::setMessage($message, $from, $color);
        $result = \PhakeBuilder\HipChat::getMessage();
        $this->assertTrue(is_object($result), "Setting message with params is broken");
    }

    public function testSetMessageObject()
    {
        $message = new \StdClass();
        \PhakeBuilder\HipChat::setMessage($message);
        $result = \PhakeBuilder\HipChat::getMessage();
        $this->assertTrue(is_object($result), "Setting message object is broken");
        $this->assertEquals($message, $result, "Set message object is modified");
    }

    public function testGetMessageDefault()
    {
        $result = \PhakeBuilder\HipChat::getMessage();
        $this->assertTrue(is_object($result), $result, "Default message is broken");
    }


    /**
     * @expectedException \GorkaLaucirica\HipchatAPIv2Client\Exception\RequestException
     */
    public function testMessage()
    {
        $result = \PhakeBuilder\HipChat::message('token', 'room', 'msg');
        $this->assertNull($result, "Non-null result from message");
    }
}
