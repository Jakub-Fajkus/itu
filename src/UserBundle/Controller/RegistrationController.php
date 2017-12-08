<?php

namespace UserBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends \FOS\UserBundle\Controller\RegistrationController
{
    public function registerAction(Request $request)
    {
        $response = parent::registerAction($request);

        if ($response instanceof RedirectResponse && $this->getUser() instanceof User) {
           $em = $this->getDoctrine()->getManager();

           $project = new Project();
           $project
               ->setName('Bez projektu')
               ->setOrder(0)
               ->setUser($this->getUser())
               ->setIsDefault(true);

           $em->persist($project);
           $em->flush();
        }

        return $response;
    }
}