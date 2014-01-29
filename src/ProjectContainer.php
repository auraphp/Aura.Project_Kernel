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
 * Builder to create a DI container for the project.
 * 
 * @package Aura.Project_Kernel
 * 
 */
class ProjectContainer
{
    /**
     * 
     * Creates and returns a new Container object.
     * 
     * There's a lot to hate about this method. It touches the file system
     * directly, it uses the created DI container as a service locator to
     * write to a log, etc.  The only plea I can make is that it is bootstrap
     * code; it's either here, or in a script somewhere, and this is easier
     * to call and test.
     * 
     * @return Container
     * 
     */
    public static function factory(
        $base,
        $loader,
        array $env,
        $log_service = null
    ) {
        // get the composer json for installed packages
        $file = "{$base}/vendor/composer/installed.json";
        $installed = json_decode(file_get_contents($file));

        // create the project information object
        $project = new Project($base, $env, $installed);
        
        // create the container and set services
        $di = new Container(new Config, new Factory);
        $di->set('loader', $loader);
        $di->set('project', $project);
        
        // create a project config object
        $includer = new Includer;
        $project_config = new ProjectConfig($project, $di, $includer);
        
        // two-stage config: load definitions, lock, and load modifications
        $project_config->load('define');
        $di->lock();
        $project_config->load('modify');
        
        // log debug messages from config?
        if ($log_service) {
            $logger = $di->get($log_service);
            foreach ($project_config->getDebug() as $message) {
                $logger->debug($message);
            }
        }
        
        // done! return the container.
        return $di;
    }
}
