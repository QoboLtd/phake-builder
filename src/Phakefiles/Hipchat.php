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

// HipChat related tasks
group('hipchat', function () {

    desc('Send message to HipChat room');
    task('message', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: hipchat:message (Send message to HipChat room)");

        $token = requireValue('HIPCHAT_TOKEN', $app);
        $room = requireValue('HIPCHAT_ROOM', $app);
        $msg = requireValue('HIPCHAT_MESSAGE', $app);
        $from = requireValue('HIPCHAT_FROM', $app);
        $color = getValue('HIPCHAT_COLOR', $app);

        \PhakeBuilder\HipChat::message($token, $room, $msg, $from, $color);
        printSuccess("SUCCESS!");
    });
});
