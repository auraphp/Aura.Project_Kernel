<?php
namespace Aura\Project_Kernel;

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $logger = new Logger;
        $this->assertNull($logger->emergency('test'));
        $this->assertNull($logger->alert('test'));
        $this->assertNull($logger->critical('test'));
        $this->assertNull($logger->error('test'));
        $this->assertNull($logger->warning('test'));
        $this->assertNull($logger->notice('test'));
        $this->assertNull($logger->info('test'));
        $this->assertNull($logger->debug('test'));
        $this->assertNull($logger->log('CUSTOM_LEVEL', 'test'));
    }
}
