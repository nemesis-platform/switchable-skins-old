<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:14
 */

namespace ScayTrase\SwitchableThemeBundle\Service;


use Symfony\Component\Form\FormBuilderInterface;

/**
 * Interface ConfigurableThemeInterface
 *
 * @package ScayTrase\SwitchableThemeBundle\Service
 */
interface ConfigurableThemeInterface
{
    /** @return string */
    public function getDefualtConfiguration();

    /**
     * @param array                $options
     * @param mixed                $data
     * @param FormBuilderInterface $builder
     *
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array());
}
