<?php
namespace Aura\Project_Kernel;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testNewContainer()
    {
        $path = __DIR__;
        $mode = 'integration';
        $di = (new Factory)->newContainer(
            $path,
            $mode,
            "$path/composer.json",
            "$path/vendor/composer/installed.json"
        );

        $this->assertInstanceOf('Aura\Di\Container', $di);
    }
}
