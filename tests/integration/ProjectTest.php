<?php
namespace Aura\Project_Kernel;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    protected $project;
    
    protected function setUp()
    {
        $env = $_ENV;
        $env['AURA_CONFIG_MODE'] = 'integration';
        
        $base = '/path/to/project';
        
        $installed = array(
            'fake/project-1',
            'fake/project-2',
            'fake/project-3',
        );
        
        $this->project = new Project($base, $env, $installed);
    }
    
    public function testGetMode()
    {
        $expect = 'integration';
        $actual = $this->project->getMode();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetInstalled()
    {
        $expect = array(
            'fake/project-1',
            'fake/project-2',
            'fake/project-3',
        );
        $actual = $this->project->getInstalled();
    }
    
    public function testGetBasePath()
    {
        $expect = '/path/to/project/';
        $actual = $this->project->getBasePath();
        $this->assertSame($expect, $actual);
        
        $expect = '/path/to/project/foo/bar/baz.txt';
        $actual = $this->project->getBasePath('foo/bar/baz.txt');
        $this->assertSame($expect, $actual);
    }
    
    public function testGetTmpPath()
    {
        $expect = '/path/to/project/tmp/';
        $actual = $this->project->getTmpPath();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetConfigPath()
    {
        $expect = '/path/to/project/config/';
        $actual = $this->project->getConfigPath();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetSrcPath()
    {
        $expect = '/path/to/project/src/';
        $actual = $this->project->getSrcPath();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetVendorPath()
    {
        $expect = '/path/to/project/vendor/';
        $actual = $this->project->getVendorPath();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetWebPath()
    {
        $expect = '/path/to/project/web/';
        $actual = $this->project->getWebPath();
        $this->assertSame($expect, $actual);
    }
    
    public function getCliPath()
    {
        $expect = '/path/to/project/cli/';
        $actual = $this->project->getCliPath();
        $this->assertSame($expect, $actual);
    }
}
