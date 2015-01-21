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

    /**
     * @param ContainerInterface $container
     *
     * @return bool
     */
    public function compile(ContainerInterface $container)
    {
        $fs = new Filesystem;
        $fs->mkdir(dirname($this->getBootstrapLessFile()));

        $assets_dir = $fs->makePathRelative(
            realpath($this->getAssetsDir()),
            realpath(dirname($this->getBootstrapLessFile()))
        );

        $variablesDir = $fs->makePathRelative(
            realpath(dirname($this->getVariablesFile())),
            realpath(dirname($this->getBootstrapLessFile()))
        );

        $variablesFile = sprintf(
            '%s%s',
            $variablesDir,
            basename($this->getVariablesFile())
        );

        $content = $container->get('twig')->render(
            $this->getBootstrapTemplate(),
            array(
                'variables_dir' => $variablesDir,
                'variables_file' => $variablesFile,
                'assets_dir' => $assets_dir
            )
        );

        file_put_contents($this->getBootstrapLessFile(), $content);

        return true;
    }
}
