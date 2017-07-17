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

    public function testMakeDirMode()
    {
        $dstDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foobar_' . uniqid();
        $this->assertFileNotExists($dstDir, "Destination directory [$dstDir] already exists");
        \PhakeBuilder\FileSystem::makeDir($dstDir, 0400);
        $this->assertFileExists($dstDir, "Failed to created destination directory [$dstDir]");
        $this->assertTrue(is_dir($dstDir), "Destination [$dstDir] is not a directory");

        $access = substr(sprintf('%o', fileperms($dstDir)), -4);
        $this->assertEquals('0400', $access, "Destination directory [$dstDir] permissions [$access] are wrong");

        rmdir($dstDir);
    }

    public function testMakeDirModeString()
    {
        $dstDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foobar_' . uniqid();
        $this->assertFileNotExists($dstDir, "Destination directory [$dstDir] already exists");
        \PhakeBuilder\FileSystem::makeDir($dstDir, '0400');
        $this->assertFileExists($dstDir, "Failed to created destination directory [$dstDir]");
        $this->assertTrue(is_dir($dstDir), "Destination [$dstDir] is not a directory");

        $access = substr(sprintf('%o', fileperms($dstDir)), -4);
        $this->assertEquals('0400', $access, "Destination directory [$dstDir] permissions [$access] are wrong");

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

    public function testRemoveReal()
    {
        $result = \PhakeBuilder\FileSystem::removePath('/this-path-does-not-exist');
        $this->assertFalse($result, "removePath() not bailing early when path is not real");
    }

    public function testChmodDirectory()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
        unlink($tmpDir);
        mkdir($tmpDir);
        // Readable only
        chmod($tmpDir, 0400);

        $access = substr(sprintf('%o', fileperms($tmpDir)), -4);
        $this->assertEquals('0400', $access, "Destination directory [$tmpDir] permissions [$access] are wrong");

        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir, 0600, 0600);
        clearstatcache();

        $access = substr(sprintf('%o', fileperms($tmpDir)), -4);
        $this->assertEquals('0600', $access, "Destination directory [$tmpDir] permissions [$access] are wrong");

        rmdir($tmpDir);
    }

    public function testChmodDefault()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
        unlink($tmpDir);
        mkdir($tmpDir);
        // Readable only
        chmod($tmpDir, 0400);

        $access = substr(sprintf('%o', fileperms($tmpDir)), -4);
        $this->assertEquals('0400', $access, "Destination directory [$tmpDir] permissions [$access] are wrong");

        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir);
        clearstatcache();

        $access = substr(sprintf('%o', fileperms($tmpDir)), -4);
        $this->assertEquals('0775', $access, "Destination directory [$tmpDir] permissions [$access] are wrong");

        rmdir($tmpDir);
    }

    public function testChmodReal()
    {
        $result = \PhakeBuilder\FileSystem::chmodPath('/this-path-does-not-exist');
        $this->assertFalse($result, "chmodPath() not bailing early when path is not real");
    }

    public function testChmodDirectoryRecursive()
    {
        $tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
        $tmpDirNested = $tmpDir . DIRECTORY_SEPARATOR . 'nested';
        if (file_exists($tmpDir)) {
            unlink($tmpDir);
        }
        mkdir($tmpDir);
        if (file_exists($tmpDirNested)) {
            unlink($tmpDirNested);
        }
        mkdir($tmpDirNested);

        // Nested check goes first
        chmod($tmpDirNested, 0400);
        $access = substr(sprintf('%o', fileperms($tmpDirNested)), -4);
        $this->assertEquals('0400', $access, "Destination directory [$tmpDirNested] permissions [$access] are wrong");

        // Parent check goes last
        chmod($tmpDir, 0400);

        $access = substr(sprintf('%o', fileperms($tmpDir)), -4);
        $this->assertEquals('0400', $access, "Destination directory [$tmpDir] permissions [$access] are wrong");

        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
        clearstatcache();

        $access = substr(sprintf('%o', fileperms($tmpDir)), -4);
        $this->assertEquals('0700', $access, "Destination directory [$tmpDir] permissions [$access] are wrong");

        $access = substr(sprintf('%o', fileperms($tmpDirNested)), -4);
        $this->assertEquals('0700', $access, "Destination directory [$tmpDirNested] permissions [$access] are wrong");

        $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . 'foobar';
        $tmpFileNested = $tmpDirNested . DIRECTORY_SEPARATOR . 'foobar';
        touch($tmpFile);
        touch($tmpFileNested);
        chmod($tmpFile, 0400);
        chmod($tmpFileNested, 0400);

        $access = substr(sprintf('%o', fileperms($tmpFile)), -4);
        $this->assertEquals('0400', $access, "Destination file [$tmpFile] permissions [$access] are wrong");

        $access = substr(sprintf('%o', fileperms($tmpFileNested)), -4);
        $this->assertEquals('0400', $access, "Destination file [$tmpFileNested] permissions [$access] are wrong");

        $result = \PhakeBuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
        clearstatcache();

        $access = substr(sprintf('%o', fileperms($tmpFile)), -4);
        $this->assertEquals('0600', $access, "Destination file [$tmpFile] permissions [$access] are wrong");

        $access = substr(sprintf('%o', fileperms($tmpFileNested)), -4);
        $this->assertEquals('0600', $access, "Destination file [$tmpFileNested] permissions [$access] are wrong");

        unlink($tmpFileNested);
        unlink($tmpFile);
        rmdir($tmpDirNested);
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
        if (!extension_loaded('posix')) {
            $this->markTestSkipped('The POSIX extension is not available');
        }

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

    public function testChownReal()
    {
        $result = \PhakeBuilder\FileSystem::chownPath('/this-path-does-not-exist');
        $this->assertFalse($result, "chownPath() not bailing early when path is not real");
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
        if (!extension_loaded('posix')) {
            $this->markTestSkipped('The POSIX extension is not available');
        }

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

    public function testChgrpReal()
    {
        $result = \PhakeBuilder\FileSystem::chgrpPath('/this-path-does-not-exist');
        $this->assertFalse($result, "chgrpPath() not bailing early when path is not real");
    }

    public function testDownloadFile()
    {
        $src = 'https://wikipedia.org';
        $dst = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phakeTest_wikipedia.html';
        $result = \PhakeBuilder\FileSystem::downloadFile($src, $dst);
        $this->assertTrue($result, "Failed to download [$src] to [$dst]");
        $this->assertTrue(file_exists($dst), "File [$dst] does not exist");
        $this->assertTrue(filesize($dst) > 0, "File [$dst] is empty");
        if (file_exists($dst)) {
            unlink($dst);
        }
    }

    public function testDownloadFileFailWrite()
    {
        $src = 'https://wikipedia.org';
        $dst = DIRECTORY_SEPARATOR . 'phakeTest_fail.html';
        $result = \PhakeBuilder\FileSystem::downloadFile($src, $dst);
        $this->assertFalse($result, "Managed to write file from [$src] to [$dst]");
        $this->assertFalse(file_exists($dst), "File [$dst] exists");
        if (file_exists($dst)) {
            unlink($dst);
        }
    }

    public function testIsFileReadable()
    {
        // File does not exist
        $result = \PhakeBuilder\FileSystem::isFileReadable('/this/file/will/never/exist.for.sure');
        $this->assertFalse($result);

        // Directory is not a file
        $result = \PhakeBuilder\FileSystem::isFileReadable(__DIR__);
        $this->assertFalse($result);

        // Not readable file
        $result = \PhakeBuilder\FileSystem::isFileReadable('/etc/shadow');
        $this->assertFalse($result);

        // Everything is fine
        $result = \PhakeBuilder\FileSystem::isFileReadable(__FILE__);
        $this->assertTrue($result);
    }
}
