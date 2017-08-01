<?php

/**
 * 日志类
 * Created by PhpStorm.
 * User:  zxt778@gmail.com
 * Date: 2017/8/1
 * Time: 下午2:26
 */
class Log
{

    const LEVEL_ERROR = 'error';

    const LEVEL_WARNING = 'warning';

    const LEVEL_INFO = 'info';

    const LEVEL_TRACE = 'trace';

    const LOG_PATH = '/var/log/app';


    /**
     * @param $message
     * @param $level
     */
    public static function write($message, $level = self::LEVEL_ERROR)
    {
        if (is_array($message) || is_object($message)) {
            $message = var_export($message, true);
        }
        $msg = sprintf("[%s] [%s] %s\r\n", date("Y-m-d H:i:s", time()), $level, $message);
        file_put_contents(self::LOG_PATH . "/log_" . date("Ymd") . ".log", $msg, FILE_APPEND);
    }

}

//Log::write("error message",Log::LEVEL_ERROR);
