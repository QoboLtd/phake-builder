<?php
namespace PhakeBuilder;
/**
 * Git class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Git {

	const DEFAULT_COMMAND = '/usr/bin/git';

	protected $command;

	/**
	 * Constructor
	 * 
	 * @param string $command Path to git executable
	 * @return object
	 */
	public function __construct($command = self::DEFAULT_COMMAND) {
		$this->command = $command ?: self::DEFAULT_COMMAND;
	}

	/**
	 * Get abbreviated hash of current commit
	 * 
	 * Thanks to comments on:
	 * https://gist.github.com/mamchenkov/930900
	 * 
	 * @return string
	 */
	public function getCurrentHash() {
		$result = $this->command . ' log -1 --pretty=format:"%h"';
		return $result;
	}

	/**
	 * Get the name of current branch
	 * 
	 * Thanks to:
	 * http://stackoverflow.com/questions/6245570/how-to-get-current-branch-name-in-git
	 * 
	 * @return string
	 */
	public function getCurrentBranch() {
		$result = $this->command . ' rev-parse --abbrev-ref HEAD';
		return $result;
	}

	/**
	 * Generate a changelog between two versions
	 * 
	 * Some possible values for the format parameter:
	 * * --no-merges --format=%B
	 * * --pretty=%s
	 * 
	 * @param string $from From reference
	 * @param string $to (Optional) To reference, HEAD assumed
	 * @param string $format Format
	 * @return string
	 */
	public function changelog($from, $to = 'HEAD', $format = '--reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, %an)" --date=short') {
		$result = $this->command . ' log ' . $from . '..' . $to . ' ' . $format;
		return $result;
	}

	/**
	 * Checkout given target
	 * 
	 * @param string $target Target to checkout
	 * @return string
	 */
	public function checkout($target) {
		$result = $this->command . ' checkout ' . $target;
		return $result;
	}

	/**
	 * Pull remote 
	 * 
	 * @param string $remote Remote reference
	 * @param string $branch Branch reference
	 * @return string
	 */
	public function pull($remote = null, $branch = null) {
		$result = $this->command . ' pull ' . $remote . ' ' . $branch;
		return $result;
	}
	
	/**
	 * Push remote 
	 * 
	 * @param string $remote Remote reference
	 * @param string $branch Branch reference
	 * @return string
	 */
	public function push($remote = null, $branch = null) {
		$result = $this->command . ' push ' . $remote . ' ' . $branch;
		return $result;
	}

}
?>
