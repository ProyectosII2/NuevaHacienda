<?php

//src/AppBundle/Controller/Ds.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UsuariosController extends Controller
{
    /**
     * @Route("/usuarios")
     */
    public function Mostrar()
    {       
        return $this->render('vistas/tablaUsuarios.html.twig');
    }

}