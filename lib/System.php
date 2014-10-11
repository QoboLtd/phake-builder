<?php
namespace PhakeBuilder;
/**
 * System class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class System {
	
	/**
	 * Get default configuration value for given parameter
	 * 
	 * @param string $param Parameter to get default value for
	 * @return string|null String if found, null otherwise
	 */
	public static function getDefaultValue($param) {
		$result = null;
		
		$defaults = array(
			'GIT_REMOTE' => 'origin',
			'GIT_BRANCH' => 'master',

			'DB_HOST' => 'localhost',
			'DB_USER' => 'root',
			'DB_PASS' => '',

			'SYSTEM_COMMAND_GIT' => '/usr/bin/git',	
			'SYSTEM_COMMAND_LINK' => '/usr/bin/ln -s',
			'SYSTEM_COMMAND_MKDIR' => '/usr/bin/mkdir -p',
			'SYSTEM_COMMAND_MYSQL' => '/usr/bin/mysql',
			'SYSTEM_COMMAND_MYSQL_REPLACE' => 'vendor/bin/mysql-replace.php',
			'SYSTEM_COMMAND_RM' => '/usr/bin/rm -r',
			'SYSTEM_COMMAND_SERVICE' => '/usr/sbin/service',
			'SYSTEM_COMMAND_SUDO' => '/usr/bin/sudo',
			'SYSTEM_COMMAND_TOUCH' => '/usr/bin/touch',
		);

		if (isset($defaults[$param])) {
			$result = $defaults[$param];
		}

		return $result;
	}
	
	/**
	 * Check if the current user needs sudo
	 * 
	 * root user doesn't need sudo.  Everybody else does.
	 * 
	 * This functionality is outside of targets for future
	 * proof.  One day we might need a more complex way to
	 * figure the answer to this question.  For example,
	 * based on a while of some parameter.
	 * 
	 * @return boolean True if needs, false otherwise
	 */
	public static function needsSudo() {
		$result = (posix_getuid() == 0) ? false : true; 
		return $result;
	}

	/**
	 * Execute a shell command
	 * 
	 * Execute the shell command and return all output
	 * as a string.  If the execution failed, then
	 * throw a RuntimeException with the full output
	 * as the exception message.
	 * 
	 * @throws RuntimeException
	 * @param string $command Command to execute
	 * @return string Output
	 */
	public static function doShellCommand($command) {
		$result = '';
		
		unset($output);
		$result = exec($command, $output, $return);
		$result = implode("\n", $output);
		if ($return > 0) {
			throw new RuntimeException($output);
		}

		return $result;
	}
	
	/**
	 * Secure string for screen output
	 * 
	 * @param string $string String to secure
	 * @param string|array $privateInfo One or more strings to secure
	 * @param string $padWith String to use for replacement
	 * @return string
	 */
	public static function secureString($string, $privateInfo, $padWith = 'x') {
		$result = $string;

		if (empty($privateInfo)) {
			return $result;
		}

		if (!is_array($privateInfo)) {
			$privateInfo = [ $privateInfo ];
		}

		foreach ($privateInfo as $privateString) {
			$replacement = str_repeat($padWith, strlen($privateString));
			$result = str_replace($privateString, $replacement, $result);
		}

		return $result;
	}

}
?>
