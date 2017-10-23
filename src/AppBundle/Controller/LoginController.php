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
//$session = new Session();
//$session->start();


class LoginController extends Controller
{
    /**
     * @Route("/homepage",name="homepage")
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_USER')") 
     */
    public function loadLanding(Request $request)
    {
        //$lastusername -> getName();
        $lastusername = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('vistas/dashboard.html.twig', 
            array ('username' => $lastusername, 'variable' => $_SESSION['variable']
        ));
    }
    /**
     * @Route("/", name="login")
     * 
     */
    public function loginAction(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        $_SESSION['variable'] = "esta es una variable de sesion";
        return $this->render('vistas/login.html.twig');

        /*
        $session->set('name', $request->request->get('_username'));
        if($request->request->has('username_parameter') && $request->request->has('password_parameter'))
        {
            //$vect = array('username'=>$request->request->get('username_u'),'password'=>$request->request->get('pass'));
            //Hay post
            return $this->redirectToRoute("success");

        } else{
            //NO hay Post
            return $this->render('logintest/login.html.twig');
        }
        */
    }
}