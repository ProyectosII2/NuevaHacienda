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
        //Alternativa para almacenar username y rol, chequear UserRepository
        //$_SESSION['username'] = $this->get('security.token_storage')->getToken()->getUser()->getUsername();
        //$_SESSION['rol'] = $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole();
        return $this->render(
            'vistas/dashboard.html.twig', 
            array (
                'username' => $_SESSION['username'], 
                'role' => $_SESSION['rol'],
                'message' => $message,
                'residences' => null,
                'residents' => null,
                'payments' => null,
            )
        );
    }
}