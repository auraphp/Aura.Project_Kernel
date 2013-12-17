<?php
/**
 * 
 * This file is part of Aura for PHP.
 * 
 * @package Aura.Project_Kernel
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @var Aura\Project_Kernel\ProjectKernel $project_kernel
 * 
 */
$project_kernel = require __DIR__ . '/kernel.php';
$project_kernel->cacheConfig('define');
$project_kernel->cacheConfig('modify');
