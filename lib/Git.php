<?php
namespace PhakeBuilder;
/**
 * Git class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Git {

	protected $git;

	/**
	 * Constructor
	 * 
	 * @param string $git Path to git executable
	 * @return object
	 */
	public function __construct($git = '/usr/bin/git') {
		$this->git = $git;
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
		$command = $this->git . ' log -1 --pretty=format:"%h"';
		$result = System::doShellCommand($command);
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
		$command = $this->git . ' rev-parse --abbrev-ref HEAD';
		$result = System::doShellCommand($command);
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
	public function getChangelog($from, $to = 'HEAD', $format = '--no-merges --format=%B') {
		$command = $this->git . ' log ' . $from . '..' . $to . ' ' . $format;
		$result = System::doShellCommand($command);
		return $result;
	}

	/**
	 * Checkout given target
	 * 
	 * @param string $target Target to checkout
	 * @return string
	 */
	public function checkout($target) {
		$command = $this->get . ' checkout ' . $target;
		$result = System::doShellCommand($command);
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
		$command = $this->get . ' pull ' . $remote . ' ' . $branch;
		$result = System::doShellCommand($command);
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
		$command = $this->get . ' push ' . $remote . ' ' . $branch;
		$result = System::doShellCommand($command);
		return $result;
	}

}
?>
