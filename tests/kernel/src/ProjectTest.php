<?php
namespace Aura\Project_Kernel;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    protected $project;

    protected $path = '/path/to/project';

    protected $mode = 'dev';

    protected $composer = '{
        "name": "vendor/package",
        "type": "library",
        "extra": {
            "aura": {
                "type": "project",
                "config": {
                    "common": ["ApplicationConfigCommon", "ApplicationConfigAnotherCommon"],
                    "dev": "ApplicationConfigDev"
                }
            }
        }
    }';

    protected $installed = '[
        {
            "name": "aura/web",
            "description": "type, without config",
            "extra": {
                "aura": {
                    "type": "library"
                }
            }
        },
        {
            "name": "aura/di",
            "description": "config, without type",
            "extra": {
                "aura": {
                    "config": {
                        "common": "DiConfigCommon"
                    }
                }
            }
        },
        {
            "name": "some/bundle",
            "description": "some bundle",
            "extra": {
                "aura": {
                    "type": "bundle",
                    "config": {
                        "common": "SomeBundleConfigCommon",
                        "test": "SomeBundleConfigTest"
                    }
                }
            }
        },
        {
            "name": "aura/project-kernel",
            "description": "kernel common and test config",
            "extra": {
                "aura": {
                    "type": "kernel",
                    "config": {
                        "common": "ProjectKernelConfigCommon",
                        "test": "ProjectKernelConfigTest"
                    }
                }
            }
        },
        {
            "name": "aura/dispatcher",
            "description": "dev config only",
            "extra": {
                "aura": {
                    "type": "library",
                    "config": {
                        "dev": "DispatcherConfigDev"
                    }
                }
            }
        },
        {
            "name": "aura/web-kernel",
            "description": "kernel config",
            "extra": {
                "aura": {
                    "type": "kernel",
                    "config": {
                        "common": "WebKernelConfigCommon"
                    }
                }
            }
        },
        {
            "name": "aura/router",
            "description": "common and dev config",
            "extra": {
                "aura": {
                    "type": "library",
                    "config": {
                        "common": "RouterConfigCommon",
                        "dev": "RouterConfigDev"
                    }
                }
            }
        },
        {
            "name": "vendor/package",
            "description": "non-aura package"
        }
    ]';

    protected function setUp()
    {
        $this->composer = json_decode($this->composer);
        $this->installed = json_decode($this->installed);
        $this->project = new Project(
            $this->path,
            $this->mode,
            $this->composer,
            $this->installed
        );
    }

    public function testNoComposer()
    {
        $this->setExpectedException('Aura\Project_Kernel\Exception');
        $this->project = new Project(
            $this->path,
            $this->mode,
            null,
            array()
        );
    }

    public function testGetPath()
    {
        $expect = $this->path . DIRECTORY_SEPARATOR;
        $actual = $this->project->getPath();
        $this->assertSame($expect, $actual);

        $expect = $this->path . '/foo/bar/baz.txt';
        $actual = $this->project->getPath('foo/bar/baz.txt');
        $this->assertSame($expect, $actual);
    }

    public function testGetMode()
    {
        $expect = $this->mode;
        $actual = $this->project->getMode();
        $this->assertSame($expect, $actual);
    }

    public function testGetInstalled()
    {
        $expect = $this->installed;
        $actual = $this->project->getInstalled();
        $this->assertSame($expect, $actual);
    }

    public function testGetComposer()
    {
        $expect = $this->composer;
        $actual = $this->project->getComposer();
        $this->assertSame($expect, $actual);
    }

    public function testGetConfigClasses()
    {
        $expect = array(
            0 => 'DiConfigCommon',
            1 => 'DispatcherConfigDev',
            2 => 'RouterConfigCommon',
            3 => 'RouterConfigDev',
            4 => 'SomeBundleConfigCommon',
            5 => 'ProjectKernelConfigCommon',
            6 => 'WebKernelConfigCommon',
            7 => 'ApplicationConfigCommon',
            8 => 'ApplicationConfigAnotherCommon',
            9 => 'ApplicationConfigDev',
        );
        $actual = $this->project->getConfigClasses();
        $this->assertSame($expect, $actual);
    }
}
