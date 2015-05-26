<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 26.08.2014
 * Time: 10:06
 */

namespace ScayTrase\SwitchableThemeBundle\Service;

use ScayTrase\Core\Registry\TypedObjectInterface;

interface ThemeInterface extends TypedObjectInterface
{
    /**
     * @param string $layout
     *
     * @return string
     */
    public function get($layout = 'base');

    /** @return string[] */
    public function all();
}
