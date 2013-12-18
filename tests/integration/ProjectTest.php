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
    
    public function testGetInstalled()
    {
        $expect = array(
            'fake/project-1',
            'fake/project-2',
            'fake/project-3',
        );
        $actual = $this->project->getInstalled();
    }
}
