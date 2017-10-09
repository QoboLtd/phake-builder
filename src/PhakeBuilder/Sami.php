<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace PhakeBuilder;

/**
 * Sami Helper Class
 *
 * This class helps with running Sami documentation generator.  The commands
 * are not actually executed, but returned as strings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Sami extends BaseCommand
{

    /**
     * Folder with Sami configuration files
     */
    const CONFIG_DIR = 'etc/sami/';

    /**
     * Configuration file extension
     */
    const CONFIG_EXT = '.php';

    /**
     * Name of the default configuration file
     */
    const CONFIG_DEFAULT = 'source';

    /**
     * Sami command string
     */
    protected $command = './vendor/bin/sami.php';

    /**
     * Construct config path from the name
     *
     * @param string $configName Name of the configuration to use
     * @return string
     */
    protected function getConfigByName($configName)
    {
        $result = self::CONFIG_DIR . $configName . self::CONFIG_EXT;

        return $result;
    }

    /**
     * Update documentation
     *
     * @throws InvalidArgumentException When configuration file does not exist
     * @param string $config Configuration to use (either name or full path)
     * @param bool $full Whether to treat $config as a full path or name
     * @return string
     */
    public function update($config = '', $fullPath = true)
    {
        if (empty($config)) {
            $config = self::CONFIG_DEFAULT;
            $fullPath = false;
        }

        if (!$fullPath) {
            $config = $this->getConfigByName($config);
        }

        if (!file_exists($config)) {
            throw new \InvalidArgumentException("Configuration file [$config] does not exist");
        }

        $result = $this->command . ' update ' . $config;

        return $result;
    }
}
