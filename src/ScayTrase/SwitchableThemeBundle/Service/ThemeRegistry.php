<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 26.08.2014
 * Time: 10:53
 */

namespace ScayTrase\SwitchableThemeBundle\Service;


use ScayTrase\AutoRegistryBundle\Service\RegistryElementInterface;
use ScayTrase\AutoRegistryBundle\Service\RegistryInterface;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class ThemeRegistry implements RegistryInterface
{

    /** @var  ThemeInterface[] */
    private $themes = array();

    public function getTemplate($type, $layout = 'base')
    {
        if (!array_key_exists($type, $this->themes)) {
            return null;
        }

        return $this->themes[$type]->get($layout);

    }

    /** @return ThemeInterface[] */
    public function all()
    {
        return $this->themes;
    }

    /**
     * @param $key string
     * @return RegistryElementInterface
     */
    public function has($key)
    {
        return array_key_exists($key, $this->themes);
    }

    /**
     * @param $key string
     * @return RegistryElementInterface
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->themes)) {
            throw new LogicException($key . ' is not found in the registry');
        }

        return $this->themes[$key];
    }

    /**
     * @param RegistryElementInterface $element
     */
    public function add(RegistryElementInterface $element)
    {
        if (!($element instanceof ThemeInterface)) {
            throw new LogicException($element->getType() . ' is not a theme');
        }

        $this->themes[$element->getType()] = $element;
    }

    /**
     * @param RegistryElementInterface $element
     */
    public function remove(RegistryElementInterface $element)
    {
        if (!array_key_exists($element->getType(), $this->themes)) {
            throw new LogicException($element->getType() . ' not found in theme registry');
        }

        unset($this->themes[$element->getType()]);
    }
}
