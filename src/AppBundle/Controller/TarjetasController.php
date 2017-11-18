<?php

//src/AppBundle/Controller/TarjetasController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TarjetasController extends Controller
{
    /**
     * @Route("/activecards", name="activecards")
     * @Security("has_role('ROLE_ADMIN')") 
     */
    public function loadActiveCardsForm(Request $request, AuthenticationUtils $utils)
    {
        return $this->render('vistas/tarjetasactivas.html.twig',
        array('appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
        'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
        'error'=>$_SESSION['error']));
    }
}