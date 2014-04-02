<?php
namespace Aura\Project_Kernel;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testNewKernel()
    {
        $kernel = (new Factory)->newKernel(
            __DIR__,
            'Aura\Project_Kernel\FakeKernel'
        );

        $this->assertInstanceOf('Aura\Project_Kernel\FakeKernel', $kernel);
    }
}
