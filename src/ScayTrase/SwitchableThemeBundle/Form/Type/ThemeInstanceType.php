<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 19.01.2015
 * Time: 11:36
 */

namespace ScayTrase\SwitchableThemeBundle\Form\Type;

use ScayTrase\SwitchableThemeBundle\Entity\ThemeInstance;
use ScayTrase\SwitchableThemeBundle\Service\ConfigurableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThemeInstanceType extends AbstractType
{
    /** @var  ThemeRegistry */
    private $registry;
    /** @var  ContainerInterface */
    private $container;

    function __construct(ThemeRegistry $registry, ContainerInterface $container)
    {
        $this->registry = $registry;
        $this->container = $container;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description','text');

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                /** @var ThemeInstance $instance */
                $instance = $event->getData();

                $themes = $this->registry->all();
                $choices = array_combine(
                    array_keys($themes),
                    array_map(
                        function (ThemeInterface $theme) {
                            return $theme->getDescription();
                        },
                        $themes
                    )
                );

                if ($instance && $instance->getId()) {
                    $form->add(
                        'theme',
                        'choice',
                        array('choices' => $choices, 'read_only' => true, 'disabled' => true)
                    );
                    $theme = $this->registry->get($instance->getTheme());
                    if ($theme instanceof ConfigurableThemeInterface) {
                        $form->add(
                            $theme->buildForm(
                                $this->container->get('form.factory')->createNamedBuilder(
                                    'config',
                                    'form',
                                    $instance->getConfig(),
                                    array('auto_initialize' => false)
                                )
                            )->getForm()
                        );
                    }
                } else {
                    $form->add(
                        'theme',
                        'choice',
                        array('choices' => $choices)
                    );
                }
            }
        );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'ScayTrase\SwitchableThemeBundle\Entity\ThemeInstance'));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'switchable_theme_instance';
    }
}
