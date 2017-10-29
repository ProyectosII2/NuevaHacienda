<?php

//src/AppBundle/Controller/Ds.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DashboardController extends Controller
{
    /**
     * @Route("/dashboards")
     */
    public function Mostrar()
    {       
        return $this->render('vistas/dashboard.html.twig');
    }

}