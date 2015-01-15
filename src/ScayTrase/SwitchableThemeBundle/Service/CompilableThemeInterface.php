<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 15.01.2015
 * Time: 15:29
 */

namespace ScayTrase\SwitchableThemeBundle\Service;


use Symfony\Component\DependencyInjection\ContainerInterface;

interface CompilableThemeInterface
{
    public function compile(ContainerInterface $container);
}
