<?php
namespace Aura\Project_Kernel;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testNewKernel()
    {
        $factory = new Factory;

        $kernel = $factory->newKernel(
            dirname(__DIR__),
            'Aura\Project_Kernel\FakeKernel'
        );

        $this->assertInstanceOf('Aura\Project_Kernel\FakeKernel', $kernel);
    }
}
