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
     * @Route("/payment", name="payment")
     * @Security("has_role('ROLE_USER')") 
     */
    public function loadPaymentForm(Request $request, AuthenticationUtils $utils)
    {
        return $this->render('vistas/pagos.html.twig');
    }
}