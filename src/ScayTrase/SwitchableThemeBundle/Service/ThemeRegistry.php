<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 26.08.2014
 * Time: 10:53
 */

namespace ScayTrase\SwitchableThemeBundle\Service;


use ScayTrase\AutoRegistryBundle\Service\RegistryInterface;
use ScayTrase\Core\Registry\TypedObjectInterface;
use ScayTrase\SwitchableThemeBundle\Entity\ThemeInstance;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class ThemeRegistry implements RegistryInterface
{

    /** @var  ThemeInterface[] */
    private $themes = array();

    /**
     * @param ThemeInstance|string $type
     * @param string               $layout
     *
     * @return null|string
     */
    public function getTemplate($type, $layout = 'base')
    {
        $instance = null;

        if ($type instanceof ThemeInstance) {
            $instance = $type;
            $type     = $instance->getTheme();
        }

        if (!array_key_exists($type, $this->themes)) {
            return null;
        }

        $theme = $this->themes[$type];

        if ($theme instanceof ConfigurableThemeInterface && $instance) {
            $theme->setConfiguration($instance->getConfig());
        }

        return $theme->get($layout);

    }

    /** @return ThemeInterface[] */
    public function all()
    {
        return $this->themes;
    }

    /**
     * @param TypedObjectInterface $element
     */
    public function add(TypedObjectInterface $element)
    {
        if (!($element instanceof ThemeInterface)) {
            throw new LogicException($element->getType().' is not a theme');
        }

        $this->themes[$element->getType()] = $element;
    }

    /**
     * @param TypedObjectInterface $element
     */
    public function removeElement(TypedObjectInterface $element)
    {
        if (!array_key_exists($element->getType(), $this->themes)) {
            throw new LogicException($element->getType().' not found in theme registry');
        }

        unset($this->themes[$element->getType()]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param $key string
     *
     * @return TypedObjectInterface
     */
    public function has($key)
    {
        return array_key_exists($key, $this->themes);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param $key string
     *
     * @return TypedObjectInterface
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->themes)) {
            throw new LogicException($key.' is not found in the registry');
        }

        return $this->themes[$key];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->replace($value);
    }

    /**
     * @param TypedObjectInterface $object
     */
    public function replace(TypedObjectInterface $object)
    {
        $this->themes[$object->getType()] = $object;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @param string $type
     */
    public function remove($type)
    {
        if (!array_key_exists($type, $this->themes)) {
            throw new LogicException($type.' not found in theme registry');
        }

        unset($this->themes[$type]);
    }

    /**
     * @return string[] All types stored at the registry
     */
    public function keys()
    {
        return array_keys($this->themes);
    }
}
