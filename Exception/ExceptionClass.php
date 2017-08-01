<?php
/**
 * 异常处理类
 * Created by PhpStorm.
 * User:  zxt778@gmail.com
 * Date: 2017/8/1
 * Time: 下午2:26
 */
class ExceptionClass
{
    /**
     * 致命错误捕获
     */
    public static function fatalErrorHandle()
    {
        if ($e = error_get_last()) {
            $errorStr = "错误原因:{$e['message']} 错误文件:{$e['file']} 第 {$e['line']} 行.";
            Log::write($errorStr);
        }
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    public static function errorHandle($errno, $errstr, $errfile, $errline)
    {
        $errorStr = "错误原因:$errstr 错误文件:" . $errfile . " 第 $errline 行.";
        Log::write($errorStr);
    }

    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    public static function exceptionHandler($e)
    {
        $error = array();
        $error['message'] = $e->getMessage();
        $trace = $e->getTrace();
        if ('E' == $trace[0]['function']) {
            $error['file'] = $trace[0]['file'];
            $error['line'] = $trace[0]['line'];
        } else {
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
        }
        $error['trace'] = $e->getTraceAsString();
        $errorStr = "异常错误:{$error['message']} 错误文件:{$error['file']} 错误行号:{$error['line']}";
        Log::write($errorStr);
    }
}


//require Log/Log.php

// 捕获致命错误
//register_shutdown_function('ExceptionClass::fatalErrorHandle');
// 自定义错误处理
//set_error_handler('ExceptionClass::errorHandle');
// 自定义异常处理
//set_exception_handler('ExceptionClass::exceptionHandler');