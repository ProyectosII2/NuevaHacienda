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

class LoginController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * 
     */
    public function loadLanding(Request $request, AuthenticationUtils $utils)
    {
        return $this->render('vistas/landingPage.html.twig');
    }
    private function Dash(Request $request)
    {
        return null;
    }
    /**
     * @Route("/login", name="login")
     * 
     */
    public function loginAction(Request $request, AuthenticationUtils $utils)
    {
        //Session Variables
        $_SESSION['error'] = null;
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        if($error != null)
        {
            $error = "Crendenciales erroneas";
        }

        $_SESSION['variable'] = "esta es una variable de sesion";
        return $this->render('vistas/login.html.twig',
            array ('error'=>$error)
        );
    }
}