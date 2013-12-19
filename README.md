# Aura.Project_Kernel

Unlike Aura library packages, this Kernel package is *not* intended for
independent use. It exists as a base for [Aura.Cli_Kernel][],
[Aura.Web_Kernel][], and other future project kernels.

Because it is not an independent package, you cannot its integration tests
directly. To run the tests:

1. Install [Aura.Web_Project][] or [Aura.Cli_Project][].

2. Go to the `vendor/aura/project-kernel/tests` directory.

3. Issue `phpunit` to run the kernel integration tests within the project.

[Aura.Cli_Kernel]: https://github.com/auraphp/Aura.Cli_Kernel
[Aura.Web_Kernel]: https://github.com/auraphp/Aura.Web_Kernel
[Aura.Cli_Project]: https://github.com/auraphp/Aura.Cli_Project
[Aura.Web_Project]: https://github.com/auraphp/Aura.Web_Project

