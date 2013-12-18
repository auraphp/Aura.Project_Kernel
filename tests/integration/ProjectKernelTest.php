<?php
namespace Aura\Project_Kernel;

use Aura\Di\Container;

class ProjectKernelTest extends \PHPUnit_Framework_TestCase
{
    protected $project_kernel;
    
    protected function setUp()
    {
        $env = $_ENV;
        $env['AURA_CONFIG_MODE'] = 'test';
        
        // project base relative to
        // {$base}/vendor/aura/project-kernel/tests/integration/ProjectKernelTest.php
        $base = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        
        $this->project_kernel = ProjectKernelFactory::newInstance($base, $env);
    }
    
    public function test__invoke()
    {
        $di = $this->project_kernel->__invoke();
        $this->assertInstanceOf('Aura\Di\Container', $di);
    }
}
