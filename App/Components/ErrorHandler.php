<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 10.11.2017
 * Time: 17:52
 */

namespace IhorRadchenko\App\Components;

use IhorRadchenko\App\Controllers\Error;
use IhorRadchenko\App\Exceptions\DbException;

class ErrorHandler
{
    /**
     * @var Logger
     */
    private $logger;
    private static $fatalErrors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        ob_start();
        register_shutdown_function([$this, 'fatalErrorHandler']);
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * @param int $errno
     * @param string $errstr
     * @param string $file
     * @param int $line
     * @return bool
     * @throws DbException
     */
    public function errorHandler(int $errno, string $errstr, string $file, int $line)
    {
        switch ($errno) {
            case 8:
            case 1024:
            case 2048:
            case 8192:
            case 16384:
                $this->logger->warning($errstr, ['Error' => self::getErrorName($errno), 'File' => $file, 'Line' => $line]);
                break;
            case 2:
            case 512:
                $this->logger->error($errstr, ['Error' => self::getErrorName($errno), 'File' => $file, 'Line' => $line]);
                break;
        }

        $this->showError($errno, $errstr, $file, $line);

        return true;
    }

    /**
     * Метод, который будет обрабатывать исключения,
     * вызванные вне блока try/catch
     *
     * @param \Throwable $e
     * @throws DbException
     */
    public function exceptionHandler(\Throwable $e)
    {
        $this->logger->critical($e->getMessage(), ['Trowable' => get_class($e), 'File' => $e->getFile(), 'Line' => $e->getLine()]);
        $this->showError(get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
    }

    /**
     * @throws DbException
     */
    public function fatalErrorHandler()
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], self::$fatalErrors, true)) {
            $this->logger->alert($error['message'], ['Fatal Error' => self::getErrorName($error['type']), 'File' => $error['file'], 'Line' => $error['line']]);
            ob_end_clean();
            $this->showError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $file
     * @param $line
     * @param int $response
     * @throws DbException
     */
    private function showError($errno, $errstr, $file, $line, $response = 503)
    {
        if (ob_get_status()) {
            ob_end_clean();
        }
        header("HTTP/1.1 $response");
        $message = '<b>' . self::getErrorName($errno) . "</b><br>$errstr<br>File : $file<br>Line : $line";
        (new Error())->action('Error', $message);
        die();
    }

    /**
     * @param $error
     * @return string
     */
    static private function getErrorName($error){
        $errors = [
            E_ERROR             => 'ERROR',
            E_WARNING           => 'WARNING',
            E_PARSE             => 'PARSE',
            E_NOTICE            => 'NOTICE',
            E_CORE_ERROR        => 'CORE_ERROR',
            E_CORE_WARNING      => 'CORE_WARNING',
            E_COMPILE_ERROR     => 'COMPILE_ERROR',
            E_COMPILE_WARNING   => 'COMPILE_WARNING',
            E_USER_ERROR        => 'USER_ERROR',
            E_USER_WARNING      => 'USER_WARNING',
            E_USER_NOTICE       => 'USER_NOTICE',
            E_STRICT            => 'STRICT',
            E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
            E_DEPRECATED        => 'DEPRECATED',
            E_USER_DEPRECATED   => 'USER_DEPRECATED',
        ];
        if(array_key_exists($error, $errors)){
            return $errors[$error] . " [$error]";
        }
        return $error;
    }
}