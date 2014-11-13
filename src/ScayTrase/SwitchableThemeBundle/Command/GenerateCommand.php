<?php

/**
 * This file is part of BraincraftedBootstrapBundle.
 *
 * (c) 2012-2013 by Florian Eckerstorfer
 */

namespace ScayTrase\SwitchableThemeBundle\Command;

use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * GenerateCommand
 *
 * @package    BraincraftedBootstrapBundle
 * @subpackage Command
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2012-2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 * @link       http://bootstrap.braincrafted.com BraincraftedBootstrapBundle
 */
class GenerateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    protected function configure()
    {
        $this
            ->setName('scaytrase:themes:generate')
            ->setDescription('Install assets for themes');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeGenerateBootstrap($input, $output);
    }

    protected function executeGenerateBootstrap(InputInterface $input, OutputInterface $output)
    {
        /** @var ThemeRegistry $theme_registry */
        $theme_registry = $this->getContainer()->get('scaytrase.theme_registry');
        /** @var ThemeInterface[] $themes */
        $themes = $theme_registry->all();

        foreach ($themes as $theme) {

            // In the template for bootstrap.less we need the path where Bootstraps .less files are stored and the path
            // to the variables.less file.
            // Absolute path do not work in LESSs import statement, we have to calculate the relative ones

            $fs = new Filesystem;

            try {
                $fs->mkdir(dirname($theme->getOptions()['bootstrap_less_file']));
            } catch (IOException $e) {
                $output->writeln(
                    sprintf(
                        '<error>Could not create directory %s.</error>',
                        dirname($theme->getOptions()['bootstrap_less_file'])
                    )
                );

                return;
            }

            $lessDir = $fs->makePathRelative(
                dirname($theme->getOptions()['bootstrap_less_file']),
                $theme->getOptions()['assets_dir']
            );

            $variablesDir = $fs->makePathRelative(
                realpath(dirname($theme->getOptions()['variables_file'])),
                realpath(dirname($theme->getOptions()['bootstrap_less_file']))
            );

            $variablesFile = sprintf(
                '%s%s',
                $variablesDir,
                basename($theme->getOptions()['variables_file'])
            );

            // We can now use Twig to render the bootstrap.less file and save it
            $content = $this->getContainer()->get('twig')->render(
                $theme->getOptions()['bootstrap_template'],
                array(
                    'variables_dir' => $variablesDir,
                    'variables_file' => $variablesFile,
                    'assets_dir' => $lessDir
                )
            );
            file_put_contents($theme->getOptions()['bootstrap_less_file'], $content);
            $output->writeln('Generating file ' . $theme->getOptions()['bootstrap_less_file']);
        }
    }
}
