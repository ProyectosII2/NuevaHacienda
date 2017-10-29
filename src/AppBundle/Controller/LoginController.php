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
<<<<<<< HEAD
    public function loadLanding(Request $request)
=======
    /**
     * @Route("/", name="homepage")
     * 
     */
    public function loginLanding(Request $request, AuthenticationUtils $utils)
    {
        return $this->render('logintest/landing.html.twig');
    }
    /**
     * @Route("/dashboard",name="dashboard")
     * @Security("has_role('ROLE_USER')") 
     * 
     */
    public function loadDashboard(Request $request)
>>>>>>> master
    {
        //$lastusername -> getName();
        $lastusername = $this->get('security.token_storage')->getToken()->getUser();
        dump($this->get('security.token_storage')->getToken());
        return $this->render('vistas/dashboard.html.twig',
            array ('username' => $lastusername->getUsername(), 'variable' => $_SESSION['variable']
        ));
    }
    /**
     * @Route("/login", name="login")
     * 
     */
    public function loginAction(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        dump($error,$lastUsername,$request);

        $_SESSION['variable'] = "esta es una variable de sesion";
        return $this->render('vistas/login.html.twig',
            array ('error'=>$error)
        );

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