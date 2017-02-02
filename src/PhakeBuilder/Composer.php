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
     * Dynamic support for composer commands
     *
     * @throws \InvalidArgumentException when options are not an array
     * @param string $name Function to call
     * @param array $arguments Arguments to pass to function
     * @return string
     */
    public function __call($name, array $arguments)
    {
        $result = $this->command . ' ' . $name;

        $options = [];

        if (!empty($arguments)) {
            $options = $arguments[0];
        }

        if (empty($options) && !empty($this->defaultOptions[$name])) {
            $options = $this->defaultOptions[$name];
        }

        if (!is_array($options)) {
            throw new \InvalidArgumentException("$name() only accepts an array of options");
        }

        $result = $this->command . ' ' . $name . ' ' . implode(' ', $options);

        return $result;
    }
}
