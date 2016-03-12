<?php
namespace PhakeBuilder;

use \Qobo\Pattern\Pattern;

/**
 * Tempalte Helper Class
 *
 * This class helps with rendering basic templates.  All the
 * heavy lifting is done by qobo/pattern library.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Template
{

    /**
     * Template source content
     */
    protected $src;

    /**
     * Constructor
     *
     * @param  string  $src    Either the source string or path to file
     * @param  boolean $isFile Whether source string is a filename or not
     * @return object
     */
    public function __construct($src, $isFile = true)
    {
        if ($isFile) {
            $this->checkFileReadable($src);
            $this->src = file_get_contents($src);
        } else {
            $this->src = $src;
        }
    }

    /**
     * Check that a given file is readable
     *
     * Check that the file exists and that we can read from it.
     * Otherwise throw an exception with a reason.
     *
     * @throws RuntimeException
     * @return void
     */
    protected function checkFileReadable($file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException("File [$file] does not exist");
        }
        if (!is_file($file)) {
            throw new \RuntimeException("File [$file] is not a file");
        }
        if (!is_readable($file)) {
            throw new \RuntimeException("File [$file] is not readable");
        }
    }

    /**
     * Get placeholders from the source
     *
     * @return array
     */
    public function getPlaceholders()
    {
        $pattern = new Pattern($this->src);
        return $pattern->getPlaceholders();
    }

    /**
     * Parse template and populate it with data
     *
     * @param  array $data Associative array of keys and values
     * @return string
     */
    public function parse($data)
    {
        $pattern = new Pattern($this->src);
        return $pattern->parse($data);
    }

    /**
     * Parse template and save it to file
     *
     * @param  string $dst  Destination filename
     * @param  array  $data Associative array of keys and values
     * @return integer Bytes written to file
     */
    public function parseToFile($dst, $data)
    {
        $pattern = new Pattern($this->src);
        return file_put_contents($dst, $this->parse($data));
    }
}
