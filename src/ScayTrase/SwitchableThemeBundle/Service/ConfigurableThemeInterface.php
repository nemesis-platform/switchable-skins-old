<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:14
 */

namespace ScayTrase\SwitchableThemeBundle\Service;


interface ConfigurableThemeInterface
{
    public function buildTheme();
    public function getOptions();
}
