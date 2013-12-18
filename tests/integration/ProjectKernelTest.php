<?php
namespace Aura\Project_Kernel;

use Aura\Di\Container;

class ProjectKernelTest extends \PHPUnit_Framework_TestCase
{
    protected $project_kernel;
    
    protected $cache_define;
    
    protected $cache_modify;
    
    protected function getBase()
    {
        // project base relative to
        // {$base}/vendor/aura/project-kernel/tests/integration/ProjectKernelTest.php
        return dirname(dirname(dirname(dirname(dirname(__DIR__)))));
    }
    
    protected function setUp()
    {
        $env = $_ENV;
        $env['AURA_CONFIG_MODE'] = 'integration';
        $base = $this->getBase();
        $this->project_kernel = ProjectKernelFactory::newInstance($base, $env);
        
        $this->cache_define = "{$base}/tmp/cache/config/integration/define.php";
        if (file_exists($this->cache_define)) {
            unlink($this->cache_define);
        }
        
        $this->cache_modify = "{$base}/tmp/cache/config/integration/modify.php";
        if (file_exists($this->cache_modify)) {
            unlink($this->cache_modify);
        }
    }
    
    public function test__invoke()
    {
        $di = $this->project_kernel->__invoke();
        $this->assertInstanceOf('Aura\Di\Container', $di);
    }
    
    public function testCaching()
    {
        $this->assertFalse(file_exists($this->cache_define));
        $this->project_kernel->cacheConfig('define');
        $this->assertTrue(is_readable($this->cache_define));
        
        $this->assertFalse(file_exists($this->cache_modify));
        $this->project_kernel->cacheConfig('modify');
        $this->assertTrue(is_readable($this->cache_modify));
    }
}
