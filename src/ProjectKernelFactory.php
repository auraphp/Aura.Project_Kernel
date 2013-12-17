<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Project_Kernel
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Project_Kernel;

use Aura\Di\Config;
use Aura\Di\Container;
use Aura\Di\Factory;
use Aura\Includer\Includer;

/**
 * 
 * Factory to create a project kernel object.
 * 
 * @package Aura.Project_Kernel
 * 
 */
class ProjectKernelFactory
{
    /**
     * 
     * Factory Method to create a new instance of a project kernel.
     * 
     * I don't like statics, or the use of `require` to get the autoloader,
     * but this is the most straightforward way to 
     * make the functionality available to a bootstrap script without
     * introducing extra global variables. Tradeoffs, tradeoffs.
     * 
     * @param string $base The project base directory.
     * 
     * @param array $env A copy of $_ENV.
     * 
     */
    public static function newInstance($base, $env)
    {
        // get composer autoloader and add project src/ directory
        $loader = require "{$base}/vendor/autoload.php";
        $loader->add('', "{$base}/src");

        // create the project info object
        $project = new Project($base, $env);

        // create the di container, add project and loader
        $di = new Container(new Config, new Factory);
        $di->set('loader', $loader);
        $di->set('project', $project);

        // an Includer prototype
        $includer = new Includer;

        // create and invoke the kernel
        return new ProjectKernel($project, $di, $includer);
    }
}
