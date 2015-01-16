<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:18
 */

namespace ScayTrase\SwitchableThemeBundle\Entity;

class ThemeInstance
{
    /** @var  int|null */
    private $id;
    /** @var  array */
    private $config;

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }
    /** @var  string */
    private $theme;

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}
