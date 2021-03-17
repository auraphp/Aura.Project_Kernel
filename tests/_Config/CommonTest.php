<?php
namespace Aura\Project_Kernel\_Config;

use Aura\Di\AbstractContainerConfigTest;

class CommonTest extends AbstractContainerConfigTest
{
    protected function getConfigClasses()
    {
        return array(
            'Aura\Project_Kernel\_Config\Common',
        );
    }

    public function provideGet()
    {
        return array(
            array('aura/project-kernel:logger', 'Psr\Log\NullLogger'),
        );
    }
}
