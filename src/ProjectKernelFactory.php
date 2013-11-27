<?php
namespace Aura\Project_Kernel;

use Aura\Di\Config;
use Aura\Di\Container;
use Aura\Di\Forge;
use Aura\Includer\Includer;

class ProjectKernelFactory
{
    protected $class = 'Aura\Project_Kernel\ProjectKernel';
    
    public function newInstance($base, $mode, $loader)
    {
        // objects for kernel instance
        $project  = new Project($base, $mode);
        $di       = new Container(new Forge(new Config));
        $includer = new Includer;
        
        // set project and loader into the container
        $di->set('project', $project);
        $di->set('loader', $loader);
        
        // return the new kernel instance
        $class = $this->class;
        return new $class($project, $di, $includer);
    }
}
