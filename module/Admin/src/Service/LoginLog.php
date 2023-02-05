<?php

namespace Admin\Service;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Log\Logger;
use Laminas\Log\Writer;
use Laminas\Log\Processor;

class LoginLog
{
    private Logger $logger;
    private AdapterInterface $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->logger = new Logger();
        $this->confSaveToDatabase();
    }

    private function confSaveToDatabase()
    {
        $mapping = [
            'timestamp' => 'time',
            'message' => 'message',
            'extra' => [
                'ip' => 'ip',
                'date' => 'date'
            ],
        ];
        
        $processor = new Processor\PsrPlaceholder();
        $this->logger->addProcessor($processor);
        $writer = new Writer\Db($this->adapter, 'logowania_log', $mapping);
        $this->logger->addWriter($writer);

    }

    public function sendInfoLog($message)
    {
        $this->logger->info($message, ['ip' => $_SERVER['REMOTE_ADDR'], 'date' => date("Y-m-d")]);
    }
}
?>