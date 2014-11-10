<?php
namespace PhakeBuilder;
use \Qobo\Pattern\Pattern;
/**
 * Tempalte class
 * 
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Template {

	protected $src;

	/**
	 * Constructor
	 * 
	 * @param string $src Either the source string or path to file
	 * @param boolean $isFile Whether source string is a filename or not
	 * @return object
	 */
	public function __construct($src, $isFile = true) {
		$this->src = ($isFile) ? file_get_contents($src) : $src;
	}

	/**
	 * Get placeholders from the source
	 * 
	 * @return array
	 */
	public function getPlaceholders() {
		$pattern = new Pattern($this->src);
		return $pattern->getPlaceholders();
	}

	/**
	 * Parse template and populate it with data
	 * 
	 * @param array $data Associative array of keys and values
	 * @return string
	 */
	public function parse($data) {
		$pattern = new Pattern($this->src);
		return $pattern->parse($data);
	}

	/**
	 * Parse template and save it to file
	 * 
	 * @param string $dst Destination filename
	 * @param array $data Associative array of keys and values
	 * @return integer Bytes written to file
	 */
	public function parseToFile($dst, $data) {
		$pattern = new Pattern($this->src);
		return file_put_contents($dst, $this->parse($data));
	}
}
