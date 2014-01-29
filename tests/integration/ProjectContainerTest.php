<?php
namespace Aura\Project_Kernel;

use Aura\Di\Container;
use StdClass;

class ProjectContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $base;
    
    public function setUp()
    {
        // project base relative to
        // {$base}/vendor/aura/project-kernel/tests/integration/ProjectContainerTest.php
        $this->base = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
    }
    
    public function testFactory()
    {
        $base = $this->base;
        $loader = require "$base/vendor/autoload.php";
        $env = $_ENV;
        $env['AURA_CONFIG_MODE'] = 'integration';
        $log_service = 'logger';
        $di = ProjectContainer::factory($base, $loader, $env, $log_service);
        $this->assertInstanceOf('Aura\Di\Container', $di);
    }
}
