<?php
namespace PhakeBuilder;
/**
 * Archive class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Archive {
	
	/**
	 * Extract a ZIP or TAR archive
	 * 
	 * @param string $src Path to the archive file
	 * @param string $dst Path to the destination
	 * @return boolean
	 */
	public static function extract($src, $dst) {
		$result = false;

		$archive = \ezcArchive::open($src);
		$archive->extract($dst);
		$result = true;

		return $result;
	}

	/**
	 * Compress a ZIP or TAR archive
	 * 
	 * @todo Populate this placeholder
	 * @param string $src Path to file or folder to be compressed
	 * @param string $dst Path tot he resulting archive file
	 * @return boolean
	 */
	public static function compress($src, $dst) {
		throw new \RuntimeException("This functionality is not implemented yet");
	}

}
?>
