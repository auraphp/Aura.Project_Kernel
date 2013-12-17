<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Project_Kernel
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @var string $base The base directory.
 * 
 */
namespace Aura\Project_Kernel;

// the project base directory, relative to
// {$project}/vendor/aura/project-kernel/scripts/kernel.php
if (! isset($base)) {
    $base = dirname(dirname(dirname(dirname(__DIR__))));
}

// load any $_ENV changes
require "{$base}/config/_env.php";

// load the kernel factory
require "{$base}/vendor/aura/project-kernel/src/ProjectKernelFactory.php";

// return a new project kernel
return ProjectKernelFactory::newInstance($base, $_ENV);
