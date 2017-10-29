<?php

//src/AppBundle/Controller/Ds.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LandingController extends Controller
{
    /**
     * @Route("/" ,name="homepage")
     */
    public function Mostrar()
    {       
        return $this->render('vistas/landingPage.html.twig');
    }

    public function MostrarLogin(Request $request)
    {     

        if ($request["REQUEST_METHOD"] == "POST") {           
        return $this->render('vistas/login.html.twig');
          }
    }
}