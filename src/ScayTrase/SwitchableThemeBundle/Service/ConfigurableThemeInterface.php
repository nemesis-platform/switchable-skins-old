<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:14
 */

namespace ScayTrase\SwitchableThemeBundle\Service;

/**
 * Interface ConfigurableThemeInterface
 *
 * @package ScayTrase\SwitchableThemeBundle\Service
 */
interface ConfigurableThemeInterface
{
    /**
     * @return mixed
     */
    public function getConfiguration();

    /**
     * @param $config
     *
     * @return mixed
     */
    public function setConfiguration($config);
}
