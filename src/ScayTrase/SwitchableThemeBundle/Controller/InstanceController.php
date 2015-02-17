<?php
/**
 * Created by PhpStorm.
 * User: Pavel Batanov <pavel@batanov.me>
 * Date: 16.01.2015
 * Time: 15:24
 */

namespace ScayTrase\SwitchableThemeBundle\Controller;


use ScayTrase\SwitchableThemeBundle\Entity\ThemeInstance;
use ScayTrase\SwitchableThemeBundle\Service\CompilableThemeInterface;
use ScayTrase\SwitchableThemeBundle\Service\ConfigurableThemeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     * @Template()
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm('switchable_theme_instance')->add('submit', 'submit');


        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var ThemeInstance $instance */
            $instance = $form->getData();
            $instance->setConfig(array());

            $this->getDoctrine()->getManager()->persist($instance);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('switchable_theme_instance_edit', array('instance' => $instance->getId()));
        }

        return array('form' => $form->createView());
    }

    /**
     * @return Response
     * @Route("/list", name="switchable_theme_instance_list")
     * @Template()
     */
    public function listAction()
    {
        /** @var ThemeInstance[] $instances */
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
     * @param ThemeInstance $instance
     *
     * @Route("/{instance}/regenerate", name="switchable_theme_instance_regenerate")
     * @return RedirectResponse
     */
    public function regenerateAction(ThemeInstance $instance)
    {
        $theme = $this->get('scaytrase.theme_registry')->get($instance->getTheme());

        if ($theme instanceof ConfigurableThemeInterface) {
            $theme->setConfiguration($instance->getConfig());
        }

        if ($theme instanceof CompilableThemeInterface) {
            $theme->compile();
        }

        return $this->redirectToRoute('switchable_theme_instance_list');
    }

    /**
     * @param Request       $request
     * @param ThemeInstance $instance
     * @Route("/{instance}/edit", name="switchable_theme_instance_edit")
     * @Template()
     *
     * @return Response
     */
    public function editAction(Request $request, ThemeInstance $instance)
    {
        $form = $this->createForm('switchable_theme_instance', $instance)
            ->add('submit', 'submit');

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('switchable_theme_instance_edit', array('instance' => $instance->getId()));
        }

        return array('form' => $form->createView(), 'instance' => $instance);
    }

    /**
     * @param ThemeInstance $instance
     * @Route("/{instance}/clone", name="switchable_theme_instance_clone")
     *
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
