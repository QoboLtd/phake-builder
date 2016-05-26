<?php
// HipChat related tasks
group('hipchat', function () {

    desc('Send message to HipChat room');
    task('message', ':builder:init', function ($app) {
        printSeparator();
        printInfo('Sending message');

        $token = requireValue('HIPCHAT_TOKEN', $app);
        $room = requireValue('HIPCHAT_ROOM', $app);
        $msg = requireValue('HIPCHAT_MESSAGE', $app);
        $from = requireValue('HIPCHAT_FROM', $app);
        $color = getValue('HIPCHAT_COLOR', $app);

        \PhakeBuilder\HipChat::message($token, $room, $msg, $from, $color);
        printSuccess("SUCCESS!");
    });

});
