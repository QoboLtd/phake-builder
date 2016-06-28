<?php
namespace PhakeBuilder\Tests;

class ArchiveTest extends \PHPUnit_Framework_TestCase
{

    public function archives()
    {
        return [
        ['hello.zip', 'hello.txt'],
        ['hello.tar.bz2', 'hello.txt'],
        ];
    }

    /**
     * @dataProvider archives
     */
    public function testExtract($src, $expected)
    {
        $srcDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'Archive' . DIRECTORY_SEPARATOR;
        $src = $srcDir . $src;

        $dstDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;

        $result = $dstDir . $expected;
        $expected = $srcDir . $expected;

        $this->assertFileNotExists($result, "Result file [$result] already exists");
        \PhakeBuilder\Archive::extract($src, $dstDir);
        $this->assertFileExists($result, "Result file [$result] was not extracted");
        $this->assertFileEquals($expected, $result, "Extracted file [$result] does not match source file [$expected]");
        unlink($result);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCompress()
    {
        $result = \PhakeBuilder\Archive::compress('some', 'other');
    }
}
