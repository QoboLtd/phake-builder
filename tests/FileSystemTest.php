<?php
namespace PhakeBuilder\Tests;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{

    public function testMakeDir()
    {
        $dstDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foobar_' . uniqid();
        $this->assertFileNotExists($dstDir, "Destination directory [$dstDir] already exists");
        \PhakeBuilder\FileSystem::makeDir($dstDir);
        $this->assertFileExists($dstDir, "Failed to created destination directory [$dstDir]");
        $this->assertTrue(is_dir($dstDir), "Destination [$dstDir] is not a directory");
        rmdir($dstDir);
    }

    public function testRemovePath()
    {
        $dstDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foobar_' . uniqid();
        $dstFile = $dstDir . DIRECTORY_SEPARATOR . 'foobar.txt';
        mkdir($dstDir);
        touch($dstFile);
        $this->assertFileExists($dstDir, "Failed to create destination directory [$dstDir]");
        $this->assertFileExists($dstFile, "Failed to create destination file [$dstFile]");
        \PhakeBuilder\FileSystem::removePath($dstDir);
        $this->assertFileNotExists($dstDir, "Failed to remove destination directory [$dstDir]");
        $this->assertFileNotExists($dstFile, "Failed to remove destination file [$dstFile]");
    }

    public function testChmodDirectory()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
        unlink($tmpDir);
        mkdir($tmpDir);
        // Readable only
        chmod($tmpDir, 0400);

        $this->assertFalse(is_writeable($tmpDir), "Directory [$tmpDir] is writeable");
        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir, 0600, 0600);
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
        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
        $this->assertTrue(is_writeable($tmpDir), "Directory [$tmpDir] was not made writeable");
        $this->assertTrue(is_executable($tmpDir), "Directory [$tmpDir] was not made executable");

        $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . 'foobar';
        touch($tmpFile);
        chmod($tmpFile, 0400);

        $this->assertFalse(is_writeable($tmpFile), "File [$tmpFile] is writeable");
        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
        $this->assertTrue(is_writeable($tmpFile), "File [$tmpFile] was not made writeable");

        unlink($tmpFile);
        rmdir($tmpDir);
    }

    /**
     * Test chown() operation
     *
     * Since this operation requires super-user privileges or
     * in-depth knowledge about current system's users, this
     * method is here mostly for the code coverage reports.
     */
    public function testChownPath()
    {
        $dst = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phakeTest_owner';
        if (!file_exists($dst)) {
            mkdir($dst);
        }
        $result = \PhakeBuilder\FileSystem::chownPath($dst);
        $this->assertTrue($result, "Result is not true");
        if (file_exists($dst)) {
            rmdir($dst);
        }
    }

    /**
     * Test chgrp() operation
     *
     * Since this operation requires super-user privileges or
     * in-depth knowledge about current system's users, this
     * method is here mostly for the code coverage reports.
     */
    public function testChgrpPath()
    {
        $dst = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phakeTest_owner';
        if (!file_exists($dst)) {
            mkdir($dst);
        }
        $result = \PhakeBuilder\FileSystem::chgrpPath($dst);
        $this->assertTrue($result, "Result is not true");
        if (file_exists($dst)) {
            rmdir($dst);
        }
    }

    public function testDownloadFile()
    {
        $src = 'https://wikipedia.org';
        $dst = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phakeTest_wikipedia.html';
        $result = \PhakeBuilder\FileSystem::downloadFile($src, $dst);
        $this->assertTrue($result, "Failed to download [$src] to [$dst]");
        $this->assertTrue(file_exists($dst), "File [$dst] does not exist");
        $this->assertTrue(filesize($dst) > 0, "File [$dst] is empty");
        unlink($dst);
    }
}
