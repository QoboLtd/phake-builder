<?php
namespace PhakeBuilder;

use \GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use \GorkaLaucirica\HipchatAPIv2Client\Client;
use \GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
use \GorkaLaucirica\HipchatAPIv2Client\Model\Message;

/**
 * HipChat Helper Class
 *
 * This class helps with sending messages to HipChat
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class HipChat
{

    /**
     * Default value from From
     */
    const DEFAULT_FROM = 'PhakeBuilder';

    /**
     * Default value for color of the HipChat message
     */
    const DEFAULT_COLOR = Message::COLOR_PURPLE;

    /**
     * Send room notification
     *
     * @param string  $token OAuth2 token from HipChat
     * @param integer $room  ID of the room
     * @param string  $msg   HTML message
     * @param string  $from  From who the message is
     * @param string  $color Color (red, yellow, green, purple, gray)
     *
     * @return void
     */
    public static function message($token, $room, $msg, $from = null, $color = null)
    {

        $from = $from ?: self::DEFAULT_FROM;
        $color = $color ?: self::DEFAULT_COLOR;

        $auth = new OAuth2($token);
        $client = new Client($auth);
        $roomAPI = new RoomAPI($client);

        $message = new Message();
        $message = $message->setMessage($msg);
        $message = $message->setFrom($from);
        $message = $message->setColor($color);

        $roomAPI->sendRoomNotification($room, $message);
    }
}
