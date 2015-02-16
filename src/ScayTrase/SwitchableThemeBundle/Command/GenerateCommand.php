<?php

/**
 * This file is part of BraincraftedBootstrapBundle.
 *
 * (c) 2012-2013 by Florian Eckerstorfer
 */

namespace ScayTrase\SwitchableThemeBundle\Command;

use Exception;
use ScayTrase\SwitchableThemeBundle\Service\CompilableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ConfigurableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $output->setDecorated(true);
        $this->executeGenerateBootstrap($input, $output);
    }

    protected function executeGenerateBootstrap(InputInterface $input, OutputInterface $output)
    {
        /** @var ThemeRegistry $theme_registry */
        $theme_registry = $this->getContainer()->get('scaytrase.theme_registry');
        /** @var ThemeInterface[] $themes */
        $themes = $theme_registry->all();
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        foreach ($themes as $theme) {
            if ($theme instanceof CompilableThemeInterface) {
                if ($theme instanceof ConfigurableThemeInterface) {
                    $output->writeln(
                        sprintf('<info>Generating theme "<comment>%s</comment>"</info>', $theme->getDescription())
                    );

                    $configurations = $manager->getRepository('SwitchableThemeBundle:ThemeInstance')->findBy(
                        array('theme' => $theme->getType())
                    );

                    if (empty($configurations)) {
                        $output->writeln('<warning>NO CONFIGURATIONS FOUND</warning>');
                        continue;
                    }

                    foreach ($configurations as $instance) {
                        $output->write(
                            sprintf(" - <info>Configuration <comment>%s</comment></info>", $instance->getDescription())
                        );
                        $theme->setConfiguration($instance->getConfig());
                        try {
                            $theme->compile();
                        } catch (Exception $exception) {
                            $output->writeln(' [<error>FAIL</error>]');
                            $output->writeln(sprintf('<error>%s</error>', $exception->getTraceAsString()));
                            continue;
                        }

                        $output->writeln(' [<info>DONE</info>]');
                    }

                } else {

                    $output->write(
                        sprintf('<info>Generating theme <comment>%s</comment></info>', $theme->getDescription())
                    );
                    try {
                        $theme->compile();
                    } catch (Exception $exception) {
                        $output->writeln(' [<error>FAIL</error>]');
                        $output->writeln(sprintf('<error>%s</error>', $exception->getTraceAsString()));
                        continue;
                    }

                    $output->writeln(' [<info>DONE</info>]');
                }
            }

        }
    }
}
