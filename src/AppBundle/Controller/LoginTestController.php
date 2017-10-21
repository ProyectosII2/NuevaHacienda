<?php

//src/AppBundle/Controller/LoginTestController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginTestController extends Controller
{
    /**
     * @Route("/failure",name="failure")
     */
    public function loadFailure(Request $request)
    {
        return $this->render('logintest/failure.html.twig');
    }
    /**
     * @Route("/success",name="success")
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * 
     */
    public function loadSuccess(Request $request)
    {
        $username = "default";
        $password = "default";
        //$username = $param[0];
        //$password = $param[1];
        
        dump($request);
        return $this->render('logintest/success.html.twig', array ('username' => $username, 'password' => $password));
    }
    /**
     * @Route("/login", name="login")
     * 
     */
    public function loginAction(Request $request, AuthenticationUtils $utils)
    {
        dump($utils);
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return $this->render('vistas/login.html.twig');

        /*
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