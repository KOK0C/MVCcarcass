<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 07.11.2017
 * Time: 17:25
 */

namespace App\Components;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class Logger extends AbstractLogger implements LoggerInterface
{
    private $dateFormat = '[d-m-Y H:i:s]';
    private $fileName = '/tmp/error.log';

    public function log($level, $message, array $context = array())
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $this->fileName;
        $errorMessage = $this->getDate() . ' ' . strtoupper($level) . "\n";
        $errorMessage .= $message . "\n=======================================================================\n";
        error_log($errorMessage, 3, $filePath);
    }

    private function getDate()
    {
        return (new \DateTime())->format($this->dateFormat);
    }
}