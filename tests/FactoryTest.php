<?php
namespace Aura\Project_Kernel;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
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

    /**
     * @var string Composer1 installed.json
     */
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

    public function testNewKernel()
    {
        $factory = new Factory;

        $kernel = $factory->newKernel(
            dirname(__DIR__),
            'Aura\Project_Kernel\FakeKernel'
        );

        $this->assertInstanceOf('Aura\Project_Kernel\FakeKernel', $kernel);
    }

    public function testNewProjectWithComposer1Installed()
    {
        $composer_file = __DIR__ . '/composer.json';
        $installed_file = __DIR__ . '/installed.json';
        file_put_contents($composer_file, $this->composer);
        file_put_contents($installed_file, $this->installed);

        $factory = new Factory;

        $project = $factory->newProject(
            $this->path,
            $this->mode,
            $composer_file,
            $installed_file
        );

        $this->assertInstanceOf('Aura\Project_Kernel\Project', $project);

        unlink($composer_file);
        unlink($installed_file);
    }
}
