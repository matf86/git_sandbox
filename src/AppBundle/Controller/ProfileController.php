<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class ProfileController extends Controller
{
    /**
     * @Route("/profile/{id}", name="profile_index")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction($id, Request $request)
    {
        if($this->getUser()->getId() != $id) {
            throw new \Exception('You are not allowed to access this page');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository('AppBundle:User')
            ->find($id);

        // replace this example code with whatever you need
        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }
}
