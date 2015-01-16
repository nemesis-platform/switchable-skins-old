<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 16.01.2015
 * Time: 15:24
 */

namespace ScayTrase\SwitchableThemeBundle\Controller;


use ScayTrase\SwitchableThemeBundle\Entity\ThemeInstance;
use ScayTrase\SwitchableThemeBundle\Service\ConfigurableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ThemeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InstanceController
 *
 * @package ScayTrase\SwitchableThemeBundle\Controller
 * @Route("/themes")
 */
class InstanceController extends Controller
{
    /**
     * @param Request $request
     * @Route("/create", name="switchable_theme_instance_create")
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $builder = $this->createFormBuilder();

        $themes = $this->get('scaytrase.theme_registry')->all();


        $choices = array_combine(
            array_keys($themes),
            array_map(
                function (ThemeInterface $theme) {
                    $theme->getDescription();
                },
                $themes
            )
        );

        $builder->add('theme', 'choice', array('choices' => $choices));
        $builder->add('submit', 'submit');

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $theme = $form->get('theme')->getData();

            $instance = new ThemeInstance();
            $instance->setTheme($theme);
            $instance->setConfig(array());

            $this->getDoctrine()->getManager()->persist($instance);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('switchable_theme_instance_list');
        }

        return array('form' => $form->createView());
    }

    /**
     * @return Response
     * @Route("/list", name="switchable_theme_instance_list")
     */
    public function listAction()
    {
        $instances = $this
            ->getDoctrine()->getManager()->getRepository('SwitchableThemeBundle:ThemeInstance')->findAll();

        return array('themes' => $instances);
    }

    /**
     * @param ThemeInstance $instance
     *
     * @Route("/{instance}/delete", name="switchable_theme_instance_delete")
     * @return RedirectResponse
     */
    public function deleteAction(ThemeInstance $instance)
    {
        $this->getDoctrine()->getManager()->remove($instance);

        return $this->redirectToRoute('switchable_theme_instance_list');
    }

    /**
     * @param Request       $request
     * @param ThemeInstance $instance
     * @Route("/{instance}/edit", name="switchable_theme_instance_edit")
     * @return Response
     */
    public function editAction(Request $request, ThemeInstance $instance)
    {
        $config = $instance->getConfig();

        $theme = $this->get('scaytrase.theme_registry')->get($instance->getTheme());

        if ($theme instanceof ConfigurableThemeInterface) {
            $builder = $theme->getForm(array());
            $builder->setData($config);
        } else {
            $builder = $this->createFormBuilder();
        }
        $builder->add('submit', 'submit');
        $form = $builder->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $config = $form->getData();
            $instance->setConfig($config);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('switchable_theme_instance_list');
        }

        return array('form' => $form->createView());
    }

    /**
     * @param ThemeInstance $instance
     * @Route("/{instance}/clone", name="switchable_theme_instance_clone")
     * @return RedirectResponse
     */
    public function cloneAction(ThemeInstance $instance)
    {
        $clone = clone $instance;

        $this->getDoctrine()->getManager()->persist($clone);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('switchable_theme_instance_list');
    }
}
