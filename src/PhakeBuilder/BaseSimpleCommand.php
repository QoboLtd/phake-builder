<?php
namespace PhakeBuilder;

/**
 * BaseSimpleCommand Helper Class
 *
 * Base class for command-line helper classes, which
 * mostly deal with simple commands, for which the
 * command string can be constructed by just appending
 * arguments.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 * @abstract
 */
abstract class BaseSimpleCommand extends BaseCommand
{
    /**
     * Default options for commands
     */
    protected $defaultOptions = [];

    /**
     * Dynamic support for commands
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
