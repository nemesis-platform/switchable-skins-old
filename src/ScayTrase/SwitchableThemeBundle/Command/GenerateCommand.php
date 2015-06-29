<?php

/**
 * Based on BraincraftedBootstrapBundle generator command ((c) 2012-2013 by Florian Eckerstorfer)
 */

namespace ScayTrase\SwitchableThemeBundle\Command;

use Exception;
use ScayTrase\SwitchableThemeBundle\Entity\ThemeInstance;
use ScayTrase\SwitchableThemeBundle\Service\CompilableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ConfigurableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeRegistry;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    protected function configure()
    {
        $this
            ->setName('scaytrase:themes:generate')
            ->setDescription('Compile custom themes to end assets');

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
        /** @var ThemeInterface[]|CompilableThemeInterface[]|ConfigurableThemeInterface[] $themes */
        $themes = $theme_registry->all();
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        foreach ($themes as $theme) {

            $output->write(
                sprintf('<info>Generating theme <comment>%s</comment></info>', $theme->getType())
            );

            if ($theme instanceof CompilableThemeInterface) {
                if ($theme instanceof ConfigurableThemeInterface) {
                    $output->writeln('');
                    $output->writeln(
                        sprintf('<info>Generating theme "<comment>%s</comment>"</info>', $theme->getDescription())
                    );

                    /** @var ThemeInterface|CompilableThemeInterface|ConfigurableThemeInterface $theme */
                    /** @var ThemeInstance[] $configurations */
                    $configurations = $manager->getRepository('SwitchableThemeBundle:ThemeInstance')->findBy(
                        array('theme' => $theme->getType())
                    );

                    if (count($configurations) === 0) {
                        $output->writeln('<comment>NO CONFIGURATIONS FOUND</comment>');
                    } else {
                        foreach ($configurations as $instance) {
                            $output->write(
                                sprintf(
                                    ' - <info>Configuration <comment>%s</comment></info>',
                                    $instance->getDescription()
                                )
                            );
                            try {
                                $theme->setConfiguration($instance->getConfig());
                                $theme->compile();
                                $output->writeln(' [<info>DONE</info>]');
                            } catch (Exception $exception) {
                                $output->writeln(' [<error>FAIL</error>]');
                                $output->writeln(sprintf('<error>%s</error>', $exception->getTraceAsString()));
                                continue;
                            }
                        }
                    }

                }

                try {
                    $theme->compile();
                    $output->writeln(' [<info>DONE</info>]');
                } catch (Exception $exception) {
                    $output->writeln(' [<error>FAIL</error>]');
                    $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
                    $output->writeln(sprintf('<error>%s</error>', $exception->getTraceAsString()));
                    continue;
                }
            }

        }
    }
}
