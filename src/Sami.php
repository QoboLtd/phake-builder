<?php
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
     * Default location for configuration file
     */
    const DEFAULT_CONFIG = 'etc/sami.config.php';

    /**
     * Sami command string
     */
    protected $command = './vendor/bin/sami.php';

    /**
     * Update documentation
     *
     * @throws InvalidArgumentException
     * @param string $config Path to sami.php configuration to use
     * @return string
     */
    public function update($config = self::DEFAULT_CONFIG)
    {
        if (empty($config)) {
            $config = self::DEFAULT_CONFIG;
        }

        if (!file_exists($config)) {
            throw new \InvalidArgumentException("Configuration file [$config] does not exist");
        }

        $result = $this->command . ' update ' . $config;
        return $result;
    }
}
