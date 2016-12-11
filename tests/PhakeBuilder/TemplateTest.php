<?php
namespace PhakeBuilder\Tests;

class TemplateTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $template = new \PhakeBuilder\Template('hello %%name%%', false);
        $result = $template->parse(['name' => 'Leonid']);

        $this->assertEquals('hello Leonid', $result, "Failed to parse template correctly");
    }

    public function testGetPlaceholders()
    {
        $template = new \PhakeBuilder\Template('hello %%name%%', false);
        $result = $template->getPlaceholders();

        $this->assertEquals(array('name'), $result, "Failed to get placeholders");
    }

    public function testParseFromFile()
    {
        $file = join(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'data', 'Template', 'small.txt']);
        $template = new \PhakeBuilder\Template($file, true);
        $result = trim($template->parse(['name' => 'Leonid']));

        $this->assertEquals('hello Leonid', $result, "Failed to parse template file correctly");
    }

    public function testParseToFile()
    {
        $template = new \PhakeBuilder\Template('hello %%name%%', false);
        $tmpFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('phpunit_template_test_');
        if (file_exists($tmpFile)) {
            $this->fail("Failed to generate a new temporary file. File exists [$tmpFile]");
        }

        $result = $template->parseToFile($tmpFile, ['name' => 'Leonid']);
        $this->assertTrue(is_int($result), "Wrong return type from parseToFile()");
        $this->assertTrue($result > 0, "parseToFile() wrote 0 bytes to file");
        $this->assertFileExists($tmpFile, "parseToFile() failed to create file");

        $expected = trim($template->parse(['name' => 'Leonid']));
        $result = trim(file_get_contents($tmpFile));

        $this->assertEquals($expected, $result, "Unexpected result from parseToFile()");

        if (file_exists($tmpFile)) {
            unlink($tmpFile);
        }
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testParseFromFileExceptionMissingFile()
    {
        $file = 'foobar';
        $template = new \PhakeBuilder\Template($file, true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testParseFromFileExceptionNotFile()
    {
        $file = __DIR__;
        $template = new \PhakeBuilder\Template($file, true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testParseFromFileNotReadable()
    {
        $file = '/etc/shadow';
        if (!file_exists($file)) {
            $this->markTestSkipped("Don't know how to test unreadable file - missing /etc/shadow");
        }
        $template = new \PhakeBuilder\Template($file, true);
    }
}
