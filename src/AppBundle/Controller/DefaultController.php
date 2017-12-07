<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ExampleUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'jsController' => 'DefaultController',
            'jsAction' => 'indexAction'
        ]);
    }

    /**
     * @Route("/sortableExample", name="sortExample")
     */
    public function sortableExampleAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/sortableExample.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'jsController' => 'DefaultController',
            'jsAction' => 'sortableExampleAction'
        ]);
    }

    /**
     * @Route("/setSortedExample", name="setSortedExample")
     */
    public function setSortedExampleAction(Request $request)
    {
        if(true) {
            return new JsonResponse([], 200);
        }
        else {
            return new JsonResponse([], 404);
        }
    }

    /**
     * Creates a new patient entity.
     *
     * @Route("/exampleNew", name="example_new")
     * @Method({"GET", "POST"})
     * @throws \LogicException
     */
    public function exampleNewAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(ExampleUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return new JsonResponse(['message'=>'ok']);
        }
        return $this->render('default/exampleNew.html.twig', [
            'form' => $form->createView(),
            'jsController' => 'DefaultController',
            'jsAction' => 'formExampleAction',
        ]);
    }
}
