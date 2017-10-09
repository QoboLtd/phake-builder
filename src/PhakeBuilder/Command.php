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
 * Command Interface
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
interface Command
{
    /**
     * Constructor
     *
     * @param string $command Path to executable
     * @return object
     */
    public function __construct($command = null);
}
