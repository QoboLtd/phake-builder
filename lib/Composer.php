<?php
namespace Phakebuilder;
/**
 * Composer class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Composer {

	const DEFAULT_COMMAND = '/usr/bin/composer';

	protected $command;

	/**
	 * Constructor
	 * 
	 * @param string $command Path to executable
	 * @return object
	 */
	public function __construct($command = self::DEFAULT_COMMAND) {
		$this->command = $command ?: self::DEFAULT_COMMAND;
	}

	/**
	 * Install composer dependecies
	 * 
	 * @return string
	 */
	public function install() {
		$result = $this->command . ' install --no-dev';
		return $result;
	}
	
	/**
	 * Update composer dependecies
	 * 
	 * @return string
	 */
	public function update() {
		$result = $this->command . ' update --no-dev';
		return $result;
	}

}
