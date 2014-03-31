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

use Aura\Di\ContainerBuilder;

/**
 * 
 * Factory for kernel-related objects.
 * 
 * @package Aura.Project_Kernel
 * 
 */
class Factory
{
    /**
     * 
     * Creates and returns a new Container for the project.
     * 
     * @param string $path The path to the project root directory.
     * 
     * @param string $mode The project configuration mode.
     * 
     * @param string $composer_file The path to the `composer.json` file.
     * 
     * @param string $installed_file The path to the `installed.json` file.
     * 
     * @return Container
     * 
     */
    public function newContainer($path, $mode, $composer_file, $installed_file)
    {
        $project = $this->newProject(
            $path,
            $mode,
            $composer_file,
            $installed_file
        );
        $builder = new ContainerBuilder;
        return $builder->newInstance(
            array('project' => $project),
            $project->getConfigClasses()
        );
    }

    /**
     * 
     * Creates and returns a new Project information object.
     * 
     * @param string $path The path to the project root directory.
     * 
     * @param string $mode The project configuration mode.
     * 
     * @return Container
     * 
     */
    public function newProject($path, $mode, $composer_file, $installed_file)
    {
        return new Project(
            $path,
            $mode,
            $this->readFile($composer_file),
            $this->readFile($installed_file)
        );
    }

    /**
     * 
     * Reads a JSON file and returns the decoded data.
     * 
     * @return mixed
     * 
     * @todo Allow for `.php` files that can be included and returned directly.
     * 
     */
    protected function readFile($file)
    {
        return json_decode(file_get_contents($file));
    }
}
