<?php
namespace PhakeBuilder\Tests;

class PhakefileFileTest extends \PHPUnit_Framework_TestCase {

	protected $phake;

	public function setUp() {
		$this->phake = './vendor/bin/phake';
	}
	
	public function test__file_process() {
		$patternRaw = 'this is %%FOO%% and %%BAR%%';
		$patternResult = 'this is BLAH and ';
		
		$tmpFile = tempnam(sys_get_temp_dir(), 'phakeTest_');
		$tmpFileResult = $tmpFile . '_pattern';
		unlink($tmpFile); // PHP creates a file, which we don't need
		file_put_contents($tmpFile, $patternRaw);
		
		$command = $this->phake . " file:process TEMPLATE_SRC=$tmpFile TEMPLATE_DST=$tmpFileResult FOO=BLAH BAR=";
		$result = exec($command);
		$result = file_get_contents($tmpFileResult);
		$this->assertEquals($result, $patternResult, "Pattern processing is broken");
		
		unlink($tmpFile);
		unlink($tmpFileResult);
	}

	public function test__file_touch() {
		$tmpFile = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpFile); // PHP creates a file, which we don't need
		$command = $this->phake . " file:touch TOUCH_PATH=$tmpFile";
		
		$result = exec($command);
		$this->assertTrue(file_exists($tmpFile), "File [$tmpFile] was not created");

		unlink($tmpFile);
	}

	public function test__file_link() {
		$tmpFile = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpFile); // PHP creates a file, which we don't need
		$tmpLink = $tmpFile . '_link';

		$command = $this->phake . " file:touch TOUCH_PATH=$tmpFile";
		$result = exec($command);
		$this->assertTrue(file_exists($tmpFile), "File [$tmpFile] was not created");
		
		$command = $this->phake . " file:link LINK_SRC=$tmpFile LINK_DST=$tmpLink";
		$result = exec($command);
		$this->assertTrue(file_exists($tmpLink), "Link [$tmpLink] was not created");
		$this->assertTrue(is_link($tmpLink), "Link [$tmpLink] is not a link");

		unlink($tmpLink);
		unlink($tmpFile);
	}

	public function test__file_rm_file() {
		$tmpFile = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpFile); // PHP creates a file, which we don't need
		$command = $this->phake . " file:touch TOUCH_PATH=$tmpFile";
		
		$result = exec($command);
		$this->assertTrue(file_exists($tmpFile), "File [$tmpFile] was not created");
	
		$command = $this->phake . " file:rm RM_PATH=$tmpFile";
		$result = exec($command);
		$this->assertFalse(file_exists($tmpFile), "File [$tmpFile] was not removed");
	}
	
	public function test__file_rm_mkdir() {
		$tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpDir); // PHP creates a file, which we don't need
		$command = $this->phake . " file:mkdir MKDIR_PATH=$tmpDir";
		
		$result = exec($command);
		$this->assertTrue(file_exists($tmpDir), "Directory [$tmpDir] was not created");
		$this->assertTrue(is_dir($tmpDir), "Directory [$tmpDir] is not a folder");
	
		$command = $this->phake . " file:rm RM_PATH=$tmpDir";
		$result = exec($command);
		$this->assertFalse(file_exists($tmpDir), "Directory [$tmpDir] was not removed");
	}
	
	public function test__file_rm_mkdir_recursive() {
		$tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpDir); // PHP creates a file, which we don't need
		$command = $this->phake . " file:mkdir MKDIR_PATH=$tmpDir";
		
		$result = exec($command);
		$this->assertTrue(file_exists($tmpDir), "Directory [$tmpDir] was not created");
		$this->assertTrue(is_dir($tmpDir), "Directory [$tmpDir] is not a folder");
	
		$tmpFile = tempnam($tmpDir, 'phakeTest_');
		$command = $this->phake . " file:touch TOUCH_PATH=$tmpFile";
		
		$result = exec($command);
		$this->assertTrue(file_exists($tmpFile), "File [$tmpFile] was not created");
		
		$command = $this->phake . " file:rm RM_PATH=$tmpDir";
		$result = exec($command);
		$this->assertFalse(file_exists($tmpDir), "Directory [$tmpDir] was not removed");
	}

	public function test__chmod_file() {
		$tmpFile = tempnam(sys_get_temp_dir(), 'phakeTest_');
		// Readable only
		chmod($tmpFile, 0400);
		$command = $this->phake . " file:chmod CHMOD_PATH=$tmpFile CHMOD_DIR_MODE='0600' CHMOD_FILE_MODE='0600'";
		$result = exec($command);

		$this->assertTrue(is_writeable($tmpFile), "File [$tmpFile] was not made writeable");
		unlink($tmpFile);
	}
	
	public function test__chmod_dir() {
		$tmpDir = tempnam(sys_get_temp_dir(), 'phakeTest_');
		unlink($tmpDir);
		mkdir($tmpDir);
		// Readable only
		chmod($tmpDir, 0400);
		$command = $this->phake . " file:chmod CHMOD_PATH=$tmpDir CHMOD_DIR_MODE='0600' CHMOD_FILE_MODE='0600'";
		$result = exec($command);

		$this->assertTrue(is_writeable($tmpDir), "Directory [$tmpDir] was not made writeable");
		rmdir($tmpDir);
	}

}
