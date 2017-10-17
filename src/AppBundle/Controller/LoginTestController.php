<?php

//src/AppBundle/Controller/LoginTestController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LoginTestController extends Controller
{
    /**
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
       /* $form = $this->createFormBuilder()
            ->add('Username')
            ->add('Submit', SubmitType::class)
            ->getForm();
        return $this->render('::base.html.twig', [
            'form'=> $form->CreateView(),
            ]);
            */
        dump($request);
            
        return $this->render('logintest/login.html.twig');
    }
    /**
     * @Route("/result")
     */
    public function postReturn(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('pass');
        
        return $this->render('logintest/credentials.html.twig', array ('username' => $username, 'password' => $password));
    }
}