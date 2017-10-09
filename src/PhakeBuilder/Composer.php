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
