<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:14
 */

namespace ScayTrase\SwitchableThemeBundle\Service;


use ScayTrase\AutoRegistryBundle\Service\ConfigurableElementInterface;

interface ConfigurableThemeInterface extends ConfigurableElementInterface
{
    /** @return string */
    public function getConfiguration();
}
