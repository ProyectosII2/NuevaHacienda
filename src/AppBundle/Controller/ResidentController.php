<?php

//src/AppBundle/Controller/ResidentController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ResidentController extends Controller
{

    /**
     * @Route("/addresident",name="addresident")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function loadResidentAddForm(Request $request)
    {
        return $this->render('vistas/addvecino.html.twig');
    }

    /**
     * @Route("/updateresident",name="updateresident")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function updateResidentForm(Request $request)
    {
        return $this->render('vistas/updatevecino.html.twig');
    }

    /**
     * @Route("/allvecinos",name="allvecinos")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function formGetAll(Request $request)
    {
        $usuarios [] = "";
        return $this->render('vistas/tablaVecinos.html.twig',
        array('error'=>$_SESSION['error']
    ));
    
    }
}