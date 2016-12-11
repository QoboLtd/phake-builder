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
        $this->assertEquals(
            "foobar -h 'dbserver1' --port '9999' -u 'dbuser1' -p 'dbpass1' -n 'dbname1' -s 'dbfind' -r 'dbreplace'",
            $result,
            "Invalid result from findReplace()"
        );
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
        $this->assertEquals(
            "foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p'dbpass1' 'dbname1' -e 'blah'",
            $result,
            "Invalid result from query()"
        );
    }

    public function testImport()
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
        $result = $mysql->import('blah.sql');
        $this->assertEquals(
            "foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p'dbpass1' 'dbname1' -e 'SOURCE blah.sql'",
            $result,
            "Invalid result from query()"
        );
    }

    public function testGrant()
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
        $result = $mysql->grant('dbname2', 'dbuser2', 'dbpass2');
        $this->assertEquals(
            "foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p'dbpass1' 'dbname1' -e 'GRANT ALL ON dbname2.* TO \"dbuser2\"@\"%\" IDENTIFIED BY \"dbpass2\"'",
            $result,
            "Invalid result from grant()"
        );
    }

    public function testRevoke()
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
        $result = $mysql->revoke('dbname2', 'dbuser2');
        $this->assertEquals(
            "foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p'dbpass1' 'dbname1' -e 'REVOKE ALL ON dbname2.* FROM \"dbuser2\"@\"%\"'",
            $result,
            "Invalid result from revoke()"
        );
    }

    public function testFileAllow()
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
        $result = $mysql->fileAllow('dbuser2');
        $this->assertEquals(
            "foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p'dbpass1' 'dbname1' -e 'GRANT FILE ON *.* TO \"dbuser2\"@\"%\"'",
            $result,
            "Invalid result from fileAllow()"
        );
    }

    public function testFileDeny()
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
        $result = $mysql->fileDeny('dbuser2');
        $this->assertEquals(
            "foobar -h 'dbserver1' -P '9999' -u 'dbuser1' -p'dbpass1' 'dbname1' -e 'REVOKE FILE ON *.* FROM \"dbuser2\"@\"%\"'",
            $result,
            "Invalid result from fileDeny()"
        );
    }
}
