<?php
namespace Aura\Project_Kernel\_Config;

use Aura\Di\ContainerConfig as Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di): void
    {
        $di->set('aura/project-kernel:logger', $di->lazyNew('Psr\Log\NullLogger'));
    }
}
