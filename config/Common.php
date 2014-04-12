<?php
namespace Aura\Project_Kernel\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', true);
        
        $di->set('logger', $di->lazyNew('Psr\Log\NullLogger'));
    }
}
