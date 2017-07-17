<?php
namespace PhakeBuilder\Tests;

class DotenvTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testGetValuesFromFileExceptionDefaultFail()
    {
        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->getValuesFromFile('/this/file/will/never/exist.for.sure');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetValuesFromFileException()
    {
        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->getValuesFromFile('/this/file/will/never/exist.for.sure', true);
    }

    public function testGetValuesFromFileEmpty()
    {
        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->getValuesFromFile('/this/file/will/never/exist.for.sure', false);
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));

        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->getValuesFromFile(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Dotenv' . DIRECTORY_SEPARATOR . 'env.empty');
        $this->assertTrue(is_array($result));
        $this->assertTrue(empty($result));
    }

    public function testGetValuesFromFile()
    {
        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->getValuesFromFile(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Dotenv' . DIRECTORY_SEPARATOR . 'env.example');
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        $this->assertEquals('foobar', $result['GIT_REMOTE']);
        $this->assertEquals('blah', $result['GIT_BRANCH']);
    }

    public function testGenerate()
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'dotenv_test_');
        if (file_exists($tmpFile)) {
            unlink($tmpFile);
        }
        if (file_exists($tmpFile)) {
            $this->fail("Temporary file [$tmpFile] exists and cannot be removed");
        }

        $exampleEnv = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Dotenv' . DIRECTORY_SEPARATOR . 'env.example';
        $appParams = ['FOO' => 'bar'];

        // Generate a new .env file
        $dotenv = new \PhakeBuilder\Dotenv();
        $result = $dotenv->generate($tmpFile, $exampleEnv, $appParams);
        $this->assertTrue($result);

        $env = $dotenv->getValuesFromFile($tmpFile);
        $this->assertTrue(is_array($env));
        $this->assertEquals('bar', $env['FOO']);
        $this->assertEquals('blah', $env['GIT_BRANCH']);
        $this->assertEquals('foobar', $env['GIT_REMOTE']);
        unlink($tmpFile);

        // Regenerate existing .env file
        $realEnv = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Dotenv' . DIRECTORY_SEPARATOR . 'env.real';
        copy($realEnv, $tmpFile);

        $result = $dotenv->generate($tmpFile, $exampleEnv, $appParams);
        $this->assertTrue($result);
        $env = $dotenv->getValuesFromFile($tmpFile);
        $this->assertTrue(is_array($env));
        $this->assertEquals('bar', $env['FOO']);
        $this->assertEquals('real', $env['GIT_BRANCH']);
        $this->assertEquals('foobar', $env['GIT_REMOTE']);

        // Overwrite parameters from command line
        $appParams = ['GIT_BRANCH' => 'app'];
        $result = $dotenv->generate($tmpFile, $exampleEnv, $appParams);
        $this->assertTrue($result);
        $env = $dotenv->getValuesFromFile($tmpFile);
        $this->assertTrue(is_array($env));
        $this->assertEquals('bar', $env['FOO']);
        $this->assertEquals('app', $env['GIT_BRANCH']);
        $this->assertEquals('foobar', $env['GIT_REMOTE']);

        unlink($tmpFile);
    }
}
