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
class Composer extends BaseSimpleCommand
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
            '--no-suggest',
            '--optimize-autoloader',
        ],
        'update' => [
            '--no-dev',
            '--no-progress',
            '--no-suggest',
            '--optimize-autoloader',
        ],
    ];
}
