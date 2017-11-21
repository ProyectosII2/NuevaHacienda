<?php

//src/AppBundle/Controller/PayController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Monthly_Pay;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class PayController extends Controller
{
    /**
     * @Route("/addpayment",name="addpayment")
     * @Security("has_role('ROLE_USER')") 
     * hacer pago
     */
    public function loadPaymentForm(Request $request)
    {
        return $this->render('vistas_test\addpay.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'error'=>""
        ));
    }
}