<?php

//src/AppBundle/Controller/PagosController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PagosController extends Controller
{
    /**
     * @Route("/pagos", name="pagos")
     * @Security("has_role('ROLE_USER')") 
     */
    public function loadLanding(Request $request, AuthenticationUtils $utils)
    {
        return $this->render('vistas/pagos.html.twig');
    }
}