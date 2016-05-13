<?php
namespace PhakeBuilder\Tests;

class MySQLTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDSN()
    {
        $mysql = new \PhakeBuilder\MySQL('foobar');
        $result = $mysql->getDSN();
        $this->assertTrue(is_array($result), "Invalid result from getDSN()");
    }

    public function testSetDSN()
    {
        $mysql = new \PhakeBuilder\MySQL('foobar');
        $dsn = array(
            'host' => 'dbserver1',
            'port' => 9999,
            'user' => 'dbuser1',
            'pass' => 'dbpass1',
            'name' => 'dbname1',
        );
        $mysql->setDSN($dsn);
        $result = $mysql->getDSN($dsn);
        $this->assertEquals($dsn, $result, "Invalid result from setDSN()");
    }

    public function testFindReplace()
    {
        $mysql = new \PhakeBuilder\MySQL('foobar');
        $dsn = array(
            'host' => 'dbserver1',
            'port' => 9999,
            'user' => 'dbuser1',
            'pass' => 'dbpass1',
            'name' => 'dbname1',
        );
        $mysql->setDSN($dsn);
        $result = $mysql->findReplace('dbfind', 'dbreplace');
        $this->assertEquals("foobar -h 'dbserver1' --port '9999' -u 'dbuser1' -p 'dbpass1' -n 'dbname1' -s 'dbfind' -r 'dbreplace'", $result, "Invalid result from findReplace()");
    }

    public function testQuery()
    {
        $mysql = new \PhakeBuilder\MySQL('foobar');
        $dsn = array(
            'host' => 'dbserver1',
            'port' => 9999,
            'user' => 'dbuser1',
            'pass' => 'dbpass1',
            'name' => 'dbname1',
        );
        $mysql->setDSN($dsn);
        $result = $mysql->query('blah');
        $this->assertEquals("foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p 'dbpass1' 'dbname1' -e 'blah'", $result, "Invalid result from query()");
    }
}
