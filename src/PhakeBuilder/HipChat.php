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
     * Default value for message
     */
    const DEFAULT_MESSAGE = 'Hello world!';

    protected static $from;
    protected static $color;
    protected static $token;
    protected static $auth;
    protected static $client;
    protected static $roomAPI;
    protected static $message;

    /**
     * Reset all configuration
     *
     * @return void
     */
    public static function resetAll()
    {
        static::$from = null;
        static::$color = null;
        static::$token = null;
        static::$auth = null;
        static::$client = null;
        static::$roomAPI = null;
        static::$message = null;
    }

    /**
     * Get From string
     *
     * @return string
     */
    public static function getFrom()
    {
        $result = static::$from;

        if (empty($result)) {
            $result = static::DEFAULT_FROM;
        }

        return $result;
    }

    /**
     * Set From string
     *
     * @param string $from From string
     * @return void
     */
    public static function setFrom($from)
    {
        static::$from = $from;
    }

    /**
     * Get Color
     *
     * @return string
     */
    public static function getColor()
    {
        $result = static::$color;

        if (empty($result)) {
            $result = static::DEFAULT_COLOR;
        }

        return $result;
    }

    /**
     * Set Color
     *
     * @param string $color Color
     * @return void
     */
    public static function setColor($color)
    {
        static::$color = $color;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public static function getToken()
    {
        return static::$token;
    }

    /**
     * Set Token
     *
     * @param string $token Token
     * @return void
     */
    public static function setToken($token)
    {
        static::$token = $token;
    }

    /**
     * Get Auth
     *
     * @return object
     */
    public static function getAuth()
    {
        $result = static::$auth;

        if (empty($result)) {
            $result = new OAuth2(static::getToken());
        }
        return $result;
    }

    /**
     * Set Auth
     *
     * @param object $auth Auth
     * @return void
     */
    public static function setAuth($auth)
    {
        static::$auth = $auth;
    }

    /**
     * Get Client
     *
     * @return object
     */
    public static function getClient()
    {
        $result = static::$client;

        if (empty($result)) {
            $result = new Client(static::getAuth());
        }

        return $result;
    }

    /**
     * Set Client
     *
     * @param object $client Client
     * @return void
     */
    public static function setClient($client)
    {
        static::$client = $client;
    }

    /**
     * Get RoomAPI
     *
     * @return object
     */
    public static function getRoomAPI()
    {
        $result = static::$roomAPI;

        if (empty($result)) {
            $result = new RoomAPI(static::getClient());
        }

        return $result;
    }

    /**
     * Set RoomAPI
     *
     * @param object $roomAPI RoomAPI
     * @return void
     */
    public static function setRoomAPI($roomAPI)
    {
        static::$roomAPI = $roomAPI;
    }

    /**
     * Get Message
     *
     * @return object
     */
    public static function getMessage()
    {
        $result = static::$message;
        if (empty($result)) {
            static::setMessage(static::DEFAULT_MESSAGE);
            $result = static::$message;
        }
        return $result;
    }

    /**
     * Set Message
     *
     * @param object|string $message Message
     * @param string $from From
     * @param string $color Color
     * @return void
     */
    public static function setMessage($message, $from = null, $color = null)
    {
        if (!empty($from)) {
            static::setFrom($from);
        }
        if (!empty($color)) {
            static::setColor($color);
        }

        if (is_object($message)) {
            static::$message = $message;
        } else {
            static::$message = new Message();
            static::$message->setMessage($message);
            static::$message->setFrom(static::getFrom());
            static::$message->setColor(static::getColor());
        }
    }

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

        if (!empty($token)) {
            static::setToken($token);
        }
        static::setMessage($msg, $from, $color);

        $roomAPI = static::getRoomAPI();
        $message = static::getMessage();

        $roomAPI->sendRoomNotification($room, $message);
    }
}
