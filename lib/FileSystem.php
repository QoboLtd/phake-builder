<?php
namespace Phakebuilder;
/**
 * File class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class FileSystem {

	const DEFAULT_DIR_MODE  = 0775;
	const DEFAULT_FILE_MODE = 0664;

	const DEFAULT_USER  = 'nginx';
	const DEFAULT_GROUP = 'nginx';

	/**
	 * Make directory (recursively)
	 * 
	 * Thanks to: http://stackoverflow.com/questions/6229353/permissions-with-mkdir-wont-work
	 * 
	 * @param string $path Path to make
	 * @param numeric $mode Permission mask
	 * @return boolean True on success, false otherwise
	 */
	public static function makeDir($path, $mode = self::DEFAULT_DIR_MODE) {
		$mode = $mode ? self::valueToOct($mode) : self::DEFAULT_DIR_MODE;
		$oldUmask = umask(0);
		$result = mkdir($path, $mode, true);
		umask($oldUmask);
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

	/**
	 * Change permissions on path
	 * 
	 * @param string $path Path to chmod
	 * @param numeric $dirMode Mode to set for folders (octal)
	 * @param numeric $fileMode Mode to set for files (octal)
	 * @param boolean $recursive Recurse into path or no (default: yes)
	 * @return boolean True on success, false otherwise
	 */
	public static function chmodPath($path, $dirMode = self::DEFAULT_DIR_MODE, $fileMode = self::DEFAULT_FILE_MODE, $recursive = true) {
		$result = false;
		
		$dirMode = $dirMode ?: self::DEFAULT_DIR_MODE;
		$fileMode = $fileMode ?: self::DEFAULT_FILE_MODE; 
		
		if ($recursive && is_dir($path)) {
			$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
		}
		else {
			$iterator = array(new \SplFileInfo($path));
		}
		
		foreach($iterator as $item) {
			$mode = $item->isDir() ? $dirMode : $fileMode;
			$mode = self::valueToOct($mode);
			$singleResult = self::processFile($item, 'chmod', array(octdec($mode)));
			if ($singleResult) {
				$result = true;
			}
		}

		return $result;
	}
	
	/**
	 * Change user ownership on path
	 * 
	 * @param string $path Path to chown
	 * @param string $user User to change ownership to
	 * @param boolean $recursive Recurse into path or no (default: yes)
	 * @return boolean True on success, false otherwise
	 */
	public static function chownPath($path, $user, $recursive = true) {
		$result = false;
		
		$user = $user ?: self::DEFAULT_USER;
		
		if ($recursive && is_dir($path)) {
			$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
		}
		else {
			$iterator = array(new \SplFileInfo($path));
		}
		
		foreach($iterator as $item) {
			$singleResult = self::processFile($item, 'chown', array($user));
			if ($singleResult) {
				$result = true;
			}
		}

		return $result;
	}
	
	/**
	 * Change group ownership on path
	 * 
	 * @param string $path Path to chgrp
	 * @param string $group Group to change ownership to
	 * @param boolean $recursive Recurse into path or no (default: yes)
	 * @return boolean True on success, false otherwise
	 */
	public static function chgrpPath($path, $group, $recursive = true) {
		$result = false;
		
		$group = $group ?: self::DEFAULT_GROUP;
		
		if ($recursive && is_dir($path)) {
			$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
		}
		else {
			$iterator = array(new \SplFileInfo($path));
		}
		
		foreach($iterator as $item) {
			$singleResult = self::processFile($item, 'chgrp', array($group));
			if ($singleResult) {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * Process file or folder with given callback
	 * 
	 * This is a convenience method, where we'll prepend full
	 * path to the array of given parameters.
	 * 
	 * @param SplFileInfo $file File or folder to process
	 * @param callback $callback Callback
	 * @param array $params Parameters
	 * @result mixed
	 */
	public static function processFile($file, $callback, $params) {
		array_unshift($params, $file->getRealPath());
		return call_user_func_array($callback, $params);
	}

	/**
	 * Download remote file using CURL
	 * 
	 * @param string $src URL to file
	 * @param string $dst Filepath to the local result
	 * @return boolean True on success, false otherwise
	 */
	public static function downloadFile($src, $dst) {
		$result = false;

		$fh = fopen($dst, 'w');
		if (!is_resource($fh)) {
			return $result;
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $src);
		curl_setopt($curl, CURLOPT_FILE, $fh);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		$result = curl_exec($curl);
		curl_close($curl);
		fclose($fh);

		return $result;
	}
	
	/**
	 * Convert a given value to octal
	 * 
	 * Thanks to: http://stackoverflow.com/questions/13112934/ishex-and-isocta-functions
	 * 
	 * @param string|numeric $value String, decimal integer, or octal value
	 * @return numeric Octal value
	 */
	protected static function valueToOct($value) {
		$result = $value;

		// If the value is not octal, convert
		if (decoct(octdec($value)) <> $value) {
			$result = base_convert((string) $value, 10, 8);
		}

		return $value;
	}

}
?>
