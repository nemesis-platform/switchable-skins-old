<?php
/**
 * Created by PhpStorm.
 * User: batanov.pavel
 * Date: 22.09.2015
 * Time: 12:12
 */

namespace ScayTrase\SwitchableThemeBundle\Tests\Core;


use ScayTrase\SwitchableThemeBundle\SwitchableThemeBundle;
use ScayTrase\Testing\KernelForTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThemeTest extends WebTestCase
{
    public function testTheme()
    {
        $kernel = new KernelForTest('test', true, array(new SwitchableThemeBundle()));
        $kernel->boot();

        $container = $kernel->getContainer();

        $theme = new ThemeForTest();

        $container->get('twig.loader')->addPath(__DIR__, $namespace = '__main__');
        $container->get('scaytrase.theme_registry')->add($theme);
        self::assertEquals(
            'TEST BASE TEMPLATE',
            $container->get('twig')->render('test.html.twig', ['theme' => 'test', 'layout' => 'base'])
        );
        self::assertEquals(
            'TEST EXTENDED TEMPLATE',
            $container->get('twig')->render('test.html.twig', ['theme' => 'test', 'layout' => 'extended'])
        );
    }
}
