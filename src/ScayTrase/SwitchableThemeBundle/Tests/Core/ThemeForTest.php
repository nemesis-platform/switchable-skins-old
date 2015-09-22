<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 22.09.2015
 * Time: 12:14
 */

namespace ScayTrase\SwitchableThemeBundle\Tests\Core;

use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;

class ThemeForTest implements ThemeInterface
{
    const DEFAULT_TEMPLATE = 'base.html.twig';

    private static $themeMap = array(
        'base' => self::DEFAULT_TEMPLATE,
        'extended' => 'extended.html.twig',
    );

    /**
     * @param string $layout
     *
     * @return string
     */
    public function get($layout = 'base')
    {
        if (!array_key_exists($layout, self::$themeMap)) {
            return self::DEFAULT_TEMPLATE;
        }

        return self::$themeMap[$layout];
    }

    /** @return string[] */
    public function all()
    {
        return self::$themeMap;
    }

    /**
     * @return string Name key for the object
     */
    public function getType()
    {
        return 'test';
    }
}
