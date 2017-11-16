<?php

//src/AppBundle/Controller/LoginTestController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DashboardController extends Controller
{
    /**
    * @Route("/dashboard",name="dashboard")
     * @Security("has_role('ROLE_USER')") 
    */
    public function loaddash(Request $request, $message=null)
    {
        
        return $this->render(
            'vistas/dashboard.html.twig', 
            array (
                'username' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
                'role' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
                'message' => $message,
                'residences' => null,
                'residents' => null,
                'payments' => null, 
            )
        );
    }
}