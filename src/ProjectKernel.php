<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Web
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\Project_Kernel;

use Aura\Di\ContainerInterface;
use Composer\Autoload\ClassLoader;

/**
 * 
 * A generic Aura project kernel; sets up the DI container and not much else.
 * 
 * @package Aura.Project_Kernel
 * 
 */
class ProjectKernel
{
    /**
     * 
     * A dependency injection container.
     * 
     * @var ContainerInterface
     * 
     */
    protected $di;
    
    /**
     * 
     * The base directory where the project resides.
     * 
     * @var string
     * 
     */
    protected $base;
    
    /**
     * 
     * The operational mode (default, develop, testing, staging, prod, etc).
     * 
     * @var string
     * 
     */
    protected $mode;
    
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
    );
    
    /**
     * 
     * The log of config activity; retained here because we don't have a
     * logger configured before configuration occurs.
     * 
     * @var array
     * 
     */
    protected $config_log = array();
    
    /**
     * 
     * Constructor.
     * 
     * @param ClassLoader $loader An autoloader, typically the Composer
     * autoloader. This will be retained in the DI container as a service
     * named 'loader'.
     * 
     * @param ContainerInterface $di A dependency injection container.
     * 
     * @param string $base The base directory for the project.
     * 
     * @param string $mode The operational mode.
     * 
     */
    public function __construct(
        ClassLoader $loader,
        ContainerInterface $di,
        $base,
        $mode
    ) {
        $loader->add('', "{$base}/src");
        $di->set('loader', $loader);
        
        $this->di   = $di;
        $this->base = $base;
        $this->mode = $mode;
    }
    
    /**
     * 
     * Invokes the kernel (i.e., runs it).
     * 
     * @return null
     * 
     */
    public function __invoke()
    {
        // find the Aura-style packages
        $this->loadPackages();
        
        // 1st stage config: define params, setters, services
        $this->includeConfig('define');
        
        // lock the DI container
        $this->di->lock();
        
        // 2nd stage config: modify services programmatically
        $this->includeConfig('modify');
        
        // log the config activity
        $logger = $this->di->get('logger');
        $logger->debug(__METHOD__);
        foreach ($this->config_log as $messages) {
            foreach ($messages as $message) {
                $logger->debug(__METHOD__ . " {$message}");
            }
        }
    }
    
    /**
     * 
     * Determines the installed Aura-style packages.
     * 
     * @return null
     * 
     */
    protected function loadPackages()
    {
        $file = str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            "{$this->base}/vendor/composer/installed.json"
        );
        
        $installed = json_decode(file_get_contents($file));
        foreach ($installed as $package) {
            if (! isset($package->extra->aura->type)) {
                continue;
            }
            $type = $package->extra->aura->type;
            $dir = "{$this->base}/vendor/{$package->name}";
            $this->packages[$type][$package->name] = $dir;
        }
    }
    
    /**
     * 
     * Includes the config files for each of the Aura-style packages in a
     * limited scope, passing only the `$di` property.
     * 
     * @param string $stage The configuration stage: 'define' or 'modify'.
     * 
     * @return null
     * 
     */
    protected function includeConfig($stage)
    {
        // the config includer
        $includer = $this->di->newInstance('Aura\Includer\Includer');
        
        // pass DI container to the config files
        $includer->setVars(array('di' => $this->di));
        
        // always load the default configs
        $includer->setFiles(array(
            "config/default/{$stage}.php",
            "config/default/{$stage}/*.php",
        ));
        
        // load any non-default configs
        if ($this->mode != 'default') {
            $includer->addFiles(array(
                "config/{$this->mode}/{$stage}.php",
                "config/{$this->mode}/{$stage}/*.php",
            ));
        }
        
        // load in this order: library packages, kernel packages, project
        $includer->addDirs($this->packages['library']);
        $includer->addDirs($this->packages['kernel']);
        $includer->addDir($this->base);
        
        // actually do the loading
        $includer->load();
        
        // retain the debug messages for logging
        $this->config_log[] = $includer->getDebug();
    }
}
