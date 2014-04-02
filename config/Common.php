<?php
namespace Aura\Project_Kernel\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('logger', $di->lazyNew('Aura\Project_Kernel\Logger'));
    }
}
