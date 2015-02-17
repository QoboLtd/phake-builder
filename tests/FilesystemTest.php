<?php
namespace Phakebuilder\Tests;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'FileSystem.php';

class FilesystemTest extends \PHPUnit_Framework_TestCase {
	
	public function test__chmod_dir() {
		$tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpDir);
		mkdir($tmpDir);
		// Readable only
		chmod($tmpDir, 0400);
		$result = \Phakebuilder\FileSystem::chmodPath($tmpDir, 0600, 0600);
		
		$this->assertTrue(is_writeable($tmpDir), "Directory [$tmpDir] was not made writeable");

		rmdir($tmpDir);
	}
	
	public function test__chmod_dir_recursive() {
		$tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpDir);
		mkdir($tmpDir);
		// Readable only
		chmod($tmpDir, 0400);
		
		$result = \Phakebuilder\FileSystem::chmodPath($tmpDir, 0700, 0600);
		$this->assertTrue(is_writeable($tmpDir), "Directory [$tmpDir] was not made writeable");
		
		$tmpFile = $tmpDir . DIRECTORY_SEPARATOR . 'foobar';
		touch($tmpFile);
		chmod($tmpFile, 0400);
		
		$result = \Phakebuilder\FileSystem::chmodPath($tmpDir, 0600, 0600);
		$this->assertTrue(is_writeable($tmpFile), "File [$tmpFile] was not made writeable");

		unlink($tmpFile);
		rmdir($tmpDir);
	}

}
?>