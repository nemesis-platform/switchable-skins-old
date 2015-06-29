<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:31
 */

namespace ScayTrase\SwitchableThemeBundle\Theme;

use ScayTrase\SwitchableThemeBundle\Service\ConfigurableThemeInterface;

abstract class AbstractConfigurableBootstrapTheme extends AbstractBootstrapTheme implements ConfigurableThemeInterface
{
    protected function getCompilationOptions()
    {
        return array_merge_recursive(
            parent::getCompilationOptions(),
            array(
                'configuration' => $this->getConfiguration(),
            )
        );
    }
}
