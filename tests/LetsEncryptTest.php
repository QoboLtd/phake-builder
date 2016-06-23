<?php
namespace PhakeBuilder\Tests;

class LetsEncryptTest extends \PHPUnit_Framework_TestCase
{

    public function testCertonly()
    {
        $command = new \PhakeBuilder\LetsEncrypt();
        $result = $command->certonly('me@here.com', '/var/www/vhosts', array('abc.com', 'xyz.com'));
        $expected = '/opt/letsencrypt/certbot-auto certonly --webroot --debug --agree-tos --email me@here.com -w /var/www/vhosts -d abc.com -d xyz.com';
        $this->assertEquals($expected, $result, "Invalid certbot command [$result]");
    }
}
