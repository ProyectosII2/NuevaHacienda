<?php

//src/AppBundle/Controller/LoginTestController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
     */
    public function loginAction(Request $request)
    {
        if($request->request->has('username') && $request->request->has('pass'))
        {
            $vect = array('username'=>$request->request->get('username'),'password'=>$request->request->get('pass'));
            //Hay post
            return $this->redirectToRoute("success", $vect);

        } else{
            //NO hay Post
            return $this->render('logintest/login.html.twig');
        }
    }
}