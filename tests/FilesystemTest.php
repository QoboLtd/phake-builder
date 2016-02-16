<?php
namespace Phakebuilder\Tests;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'FileSystem.php';

class FilesystemTest extends \PHPUnit_Framework_TestCase
{

    public function testChmodDirectory()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
        unlink($tmpDir);
        mkdir($tmpDir);
        // Readable only
        chmod($tmpDir, 0400);

        $this->assertFalse(is_writeable($tmpDir), "Directory [$tmpDir] is writeable");
        $result = \Phakebuilder\FileSystem::chmodPath($tmpDir, 0600, 0600);
        $this->assertTrue(is_writeable($tmpDir), "Directory [$tmpDir] was not made writeable");

        rmdir($tmpDir);
    }
 
    public function testChmodDirectoryRecursive()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
        unlink($tmpDir);
        mkdir($tmpDir);
        // Readable only
        chmod($tmpDir, 0400);
 
        $this->assertFalse(is_writeable($tmpDir), "Directory [$tmpDir] is writeable");
        $this->assertFalse(is_executable($tmpDir), "Directory [$tmpDir] is executable");
        $result = \Phakebuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
        $this->assertTrue(is_writeable($tmpDir), "Directory [$tmpDir] was not made writeable");
        $this->assertTrue(is_executable($tmpDir), "Directory [$tmpDir] was not made executable");

        $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . 'foobar';
        touch($tmpFile);
        chmod($tmpFile, 0400);

        $this->assertFalse(is_writeable($tmpFile), "File [$tmpFile] is writeable");
        $result = \Phakebuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
        $this->assertTrue(is_writeable($tmpFile), "File [$tmpFile] was not made writeable");

        unlink($tmpFile);
        rmdir($tmpDir);
    }
}
