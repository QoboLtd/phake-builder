<?php
namespace Phakebuilder;
/**
 * File class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class FileSystem {

	const DEFAULT_MKDIR_MODE = 0755;

	/**
	 * Make directory (recursively)
	 * 
	 * @param string $path Path to make
	 * @param numeric $mode Permission mask
	 * @return boolean True on success, false otherwise
	 */
	public static function makeDir($path, $mode = self::DEFAULT_MKDIR_MODE) {
		$mode = $mode ?: self::DEFAULT_MKDIR_MODE;
		$result = mkdir($path, $mode, true);
		return $result;
	}

	/**
	 * Remove file or folder (recursively)
	 * 
	 * Thanks to: http://stackoverflow.com/a/15111679/151647
	 * 
	 * @param string $path Path to remove
	 * @return boolean True on success, false otherwise
	 */
	public static function removePath($path) {
		$result = false;
		
		if (is_dir($path)) {
			$it = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator(
					$path, 
					\FilesystemIterator::SKIP_DOTS
				), 
				\RecursiveIteratorIterator::CHILD_FIRST
			);
			foreach($it as $item) {
				$result = $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
			}
			$result = rmdir($path);	
		}
		else {
			$result = unlink($path);
		}
		
		return $result;
	}
}
?>
