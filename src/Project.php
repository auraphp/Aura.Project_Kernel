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

/**
 * 
 * Project information.
 * 
 * @package Aura.Project_Kernel
 * 
 */
class Project
{
    /**
     * 
     * The path to the project root directory.
     * 
     * @var string
     * 
     */
    protected $path;

    /**
     * 
     * The 'composer.json' data.
     * 
     * @var array
     * 
     */
    protected $composer;

    /**
     * 
     * The array of classes to be used for the configuration mode.
     * 
     * @var array
     * 
     */
    protected $config_classes;

    /**
     * 
     * The 'vendor/composer/installed.json' data.
     * 
     * @var array
     * 
     */
    protected $installed;

    /**
     * 
     * The config mode.
     * 
     * @var string
     * 
     */
    protected $mode;

    /**
     * 
     * Constructor.
     * 
     * @param string $path The path to the project root directory.
     * 
     * @param string $mode The project configuration mode.
     * 
     * @param object $composer The 'composer.json' data.
     * 
     * @param array $installed The 'vendor/composer/installed.json' data.
     * 
     */
    public function __construct($path, $mode, $composer, array $installed)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->mode = $mode;

        if (! is_object($composer)) {
            throw new Exception('The "composer.json" data is not an object.');
        }
        $this->composer = $composer;

        $this->installed = $installed;
    }

    /**
     * 
     * Gets the path to the project root, with an optional subpath.
     * 
     * @param string $sub An optional subpath.
     * 
     * @return The full path, with proper directory separators.
     * 
     */
    public function getPath($sub = null)
    {
        if ($sub) {
            $sub = ltrim($sub, DIRECTORY_SEPARATOR);
            return $this->path . str_replace('/', DIRECTORY_SEPARATOR, $sub);
        } else {
            return $this->path;
        }
    }

    /**
     * 
     * Gets the config mode.
     * 
     * @return string The operational mode.
     * 
     */
    public function getMode()
    {
        return $this->mode;
    }
    
    /**
     * 
     * Returns the 'composer.json' data.
     * 
     * @return array
     * 
     */
    public function getComposer()
    {
        return $this->composer;
    }

    /**
     * 
     * Returns the 'vendor/composer/installed.json' data.
     * 
     * @return array
     * 
     */
    public function getInstalled()
    {
        return $this->installed;
    }
    
    /**
     * 
     * Returns the list of classes recognized in the project as Aura configs.
     * 
     * @return array
     * 
     */
    public function getConfigClasses()
    {
        if ($this->config_classes === null) {
            $this->setConfigClasses();
        }

        return $this->config_classes;
    }

    /**
     * 
     * Sets the list of config classes by examining the `$composer` and
     * `$installed` data objects.
     * 
     * @return null
     * 
     */
    protected function setConfigClasses()
    {
        $this->config_classes = array(
            'library' => array(),
            'kernel' => array(),
            'project' => array(),
        );

        foreach ($this->installed as $package) {
            $this->addConfigClasses($package);
        }

        $this->addConfigClasses($this->composer);

        $this->config_classes = array_merge(
            $this->config_classes['library'],
            $this->config_classes['kernel'],
            $this->config_classes['project']
        );
    }

    /**
     * 
     * Adds any config classes recognized in the specification.
     * 
     * @param object $spec A data object from `$composer` or `$installed`.
     * 
     * @return null
     * 
     */
    protected function addConfigClasses($spec)
    {
        if (! isset($spec->extra->aura->config)) {
            return;
        }

        $config = $spec->extra->aura->config;

        $type = isset($spec->extra->aura->type)
              ? $spec->extra->aura->type
              : 'library';

        $mode = $this->mode;
        
        if (isset($config->common)) {
            $this->config_classes[$type][] = $config->common;
        }

        if (isset($config->$mode)) {
            $this->config_classes[$type][] = $config->$mode;
        }
    }
}
