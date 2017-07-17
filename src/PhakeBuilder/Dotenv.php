<?php
namespace PhakeBuilder;

use PhakeBuilder\FileSystem;
use RuntimeException;

/**
 * Dotenv Helper Class
 *
 * This class helps with generating and merging .env
 * files.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Dotenv
{
    /**
     * Generate a .env file
     *
     * Start with system defaults. Overwrite with `.env.example`
     * values. Overwrite with values from `.env` if exists.
     * Overwrite with values from $app (command line), if given.
     * Save the result in `.env`.
     *
     * @param string $env Path to .env file
     * @param string $example Path to .env.example file
     * @param array $app Array of command line arguments
     * @return bool True on success, false or exception otherwise
     */
    public function generate($env, $example, array $app = [])
    {
        $result = false;

        $envValues = $this->getValuesFromFile($env, false);
        $exampleValues = $this->getValuesFromFile($example);
        $allValues = array_merge($exampleValues, $envValues, $app);

        $content = '';
        foreach ($allValues as $key => $value) {
            $content .= $key . '=' . $value . "\n";
        }

        if (file_put_contents($env, $content)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get .env values from a given file
     *
     * @throws \RuntimeException when file is missing and $failOnMissing is true
     * @param string $file Path to file
     * @param bool $failOnMissing Whether or not fail on missing file
     * @return array
     */
    public function getValuesFromFile($file, $failOnMissing = true)
    {
        $result = [];

        if (!FileSystem::isFileReadable($file)) {
            if ($failOnMissing) {
                throw new RuntimeException("Path [$file] is not a readable file");
            }
            return $result;
        }

        $file = file($file);
        if (empty($file)) {
            return $result;
        }

        foreach ($file as $line) {
            $line = trim($line);
            if (!preg_match('#^(.*)?=(.*)?$#', $line, $matches)) {
                continue;
            }
            $result[$matches[1]] = $matches[2];
        }

        return $result;
    }
}
