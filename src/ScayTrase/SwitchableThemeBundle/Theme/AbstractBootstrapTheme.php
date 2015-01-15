<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:31
 */

namespace ScayTrase\SwitchableThemeBundle\Theme;

use ScayTrase\SwitchableThemeBundle\Service\CompilableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractBootstrapTheme implements ThemeInterface, CompilableThemeInterface
{
    abstract protected function getBootstrapLessFile();
    abstract protected function getVariablesFile();
    abstract protected function getAssetsDir();
    abstract protected function getBootstrapTemplate();

    public function compile(ContainerInterface $container)
    {
        // In the template for bootstrap.less we need the path where Bootstraps .less files are stored and the path
        // to the variables.less file.
        // Absolute path do not work in LESSs import statement, we have to calculate the relative ones

        $fs = new Filesystem;
        $fs->mkdir(dirname($this->getOptions()['bootstrap_less_file']));

        $assets_dir = $fs->makePathRelative(
            realpath($this->getOptions()['assets_dir']),
            realpath(dirname($this->getOptions()['bootstrap_less_file']))
        );

        $variablesDir = $fs->makePathRelative(
            realpath(dirname($this->getOptions()['variables_file'])),
            realpath(dirname($this->getOptions()['bootstrap_less_file']))
        );

        $variablesFile = sprintf(
            '%s%s',
            $variablesDir,
            basename($this->getOptions()['variables_file'])
        );

        // We can now use Twig to render the bootstrap.less file and save it
        $content = $container->get('twig')->render(
            $this->getOptions()['bootstrap_template'],
            array(
                'variables_dir' => $variablesDir,
                'variables_file' => $variablesFile,
                'assets_dir' => $assets_dir
            )
        );

        file_put_contents($this->getOptions()['bootstrap_less_file'], $content);

    }
}
