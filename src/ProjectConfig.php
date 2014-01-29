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

use Aura\Di\Container;
use Aura\Includer\Includer;

/**
 * 
 * Builder to create a container for the project.
 * 
 * @package Aura.Project_Kernel
 * 
 */
class ProjectConfig
{
    /**
     * 
     * The log of config activity; retained here because we don't have a
     * logger configured before configuration occurs.
     * 
     * @var array
     * 
     */
    protected $debug = array();
    
    /**
     * 
     * An Includer prototype.
     * 
     * @var Includer
     * 
     */
    protected $includer;
    
    /**
     * 
     * Aura-style packages identified via Composer's list of installed
     * packages, organized by Aura package type in loading order (libraries
     * first, then kernels, etc.).
     * 
     * @var array
     * 
     */
    protected $packages = array(
        'library' => array(),
        'kernel' => array(),
        'bundle' => array(),
    );
    
    /**
     * 
     * Information about the project.
     * 
     * @var Project
     * 
     */
    protected $project;
    
    /**
     * 
     * Constructor.
     * 
     * @param Project $project A project information object.
     * 
     * @param Container $di A dependency injection container.
     * 
     * @param Includer $includer An Includer prototype.
     * 
     */
    public function __construct(
        Project $project,
        Container $di,
        Includer $includer
    ) {
        $this->project = $project;
        $this->di = $di;
        $this->includer = $includer;
        
        // build the $packages property
        $installed = $this->project->getInstalled();
        foreach ($installed as $package) {
            if (! isset($package->extra->aura->type)) {
                continue;
            }
            $type = $package->extra->aura->type;
            $dir = $this->project->getVendorPath($package->name);
            $this->packages[$type][$package->name] = $dir;
        }
    }
    
    /**
     * 
     * Adds to the debug messages.
     * 
     * @param array|string $debug The additional debug messages.
     * 
     * @return null
     * 
     */
    protected function addDebug($debug)
    {
        $this->debug = array_merge($this->debug, (array) $debug);
    }
    
    /**
     * 
     * Get the debug messages.
     * 
     * @param array|string $debug The additional debug messages.
     * 
     * @return null
     * 
     */
    public function getDebug()
    {
        return $this->debug;
    }
    
    /**
     * 
     * Loads the config files for each of the Aura-style packages.
     * 
     * @param string $stage The configuration stage: 'define' or 'modify'.
     * 
     * @return null
     * 
     */
    public function load($stage)
    {
        $this->addDebug(__METHOD__);
        $includer = $this->newIncluder($stage);
        $includer->load();
        $this->addDebug($includer->getDebug());
    }
    
    /**
     * 
     * Returns a cloned includer for the config mode and stage.
     * 
     * @param string $stage The configuration stage: 'define' or 'modify'.
     * 
     * @return Includer
     * 
     */
    protected function newIncluder($stage)
    {
        // the project config mode
        $mode = $this->project->getMode();
        
        // clone the includer prototype
        $includer = clone $this->includer;
        
        // pass DI container to the config files
        $includer->setVars(array('di' => $this->di));
        
        // always load the default configs
        $includer->setFiles(array(
            "config/default/{$stage}.php",
            "config/default/{$stage}/*.php",
        ));
        
        // load any non-default configs
        if ($mode != 'default') {
            $includer->addFiles(array(
                "config/{$mode}/{$stage}.php",
                "config/{$mode}/{$stage}/*.php",
            ));
        }
        
        // load in this order: library packages, kernel packages, project
        $includer->addDirs($this->packages['library']);
        $includer->addDirs($this->packages['kernel']);
        $includer->addDir($this->project->getBasePath());
        
        // set the cached config file location
        $includer->setCacheFile(
            $this->project->getTmpPath("cache/config/{$mode}/{$stage}.php")
        );
        
        // done!
        return $includer;
    }
}
