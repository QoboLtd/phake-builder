<?php
namespace PhakeBuilder;

/**
 * Composer Helper Class
 *
 * This class helps with running composer commands.  The commands
 * are not actually executed, but returned as strings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class Composer extends BaseCommand
{

    /**
     * Composer command string
     */
    protected $command = 'composer --no-interaction';

    /**
     * Default options for commands
     */
    protected $defaultOptions = [
        'install' => [
            '--no-dev',
            '--no-progress',
            '--nosuggest',
            '--optimize-autoloader',
        ],
        'update' => [
            '--no-dev',
            '--no-progress',
            '--nosuggest',
            '--optimize-autoloader',
        ],
    ];

    /**
     * Install composer dependecies
     *
     * @param array $options Options
     * @return string
     */
    public function install(array $options = [])
    {
        if (empty($options) && !empty($this->defaultOptions[__FUNCTION__])) {
            $options = $this->defaultOptions[__FUNCTION__];
        }
        $result = $this->command . ' install ' . implode(' ', $options);
        return $result;
    }

    /**
     * Update composer dependecies
     *
     * @param array $options Options
     * @return string
     */
    public function update(array $options = [])
    {
        if (empty($options) && !empty($this->defaultOptions[__FUNCTION__])) {
            $options = $this->defaultOptions[__FUNCTION__];
        }
        $result = $this->command . ' update ' . implode(' ', $options);

        return $result;
    }
}
