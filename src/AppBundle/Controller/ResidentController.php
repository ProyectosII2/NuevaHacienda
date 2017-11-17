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

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Resident;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class ResidentController extends Controller
{

    /**
     * @Route("/addresident",name="addresident")
     * @Security("has_role('ROLE_ADMIN')") 
     * Ingreso de residentes
     */
    public function loadResidentAddForm(Request $request)
    {
        $_SESSION['error'] = null;
        //Revisa si hay POST
        if($request->request->has('email') && $request->request->has('firstname') 
        && $request->request->has('secondname') && $request->request->has('phone')
        && $request->request->has('DPI'))
        {
            //pasar parametros a variables
            $email = strtolower($request->request->get('email'));
            $firstname = strtolower($request->request->get('firstname'));
            $secondname = strtolower($request->request->get('secondname'));
            $phone = $request->request->get('phone');
            $code = $request->request->get('DPI');
            //Validación de parametros
            if($this->AddCheck($firstname,$secondname,$email,$phone,$code))
            {
                //Pasó validación
                $this->getDoctrine()->getManager()->getRepository(Resident::class)->
                createResident(strval($code), $firstname, $secondname, $email, $phone);
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array("message"=>"Residente Ingresado"));

            }

        }
        return $this->render('vistas_test/addresident.html.twig', 
        array('error'=>$_SESSION['error']));
    }

    /**
     * @Route("/updateresident",name="updateresident")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function updateResidentForm(Request $request)
    {

        return $this->render(
            'vistas/updatevecino.html.twig'
        );
    }

    /**
     * @Route("/allresidents",name="allresidents")
     * @Security("has_role('ROLE_ADMIN')") 
     * 
     */
    public function loadAllResidentsForm(Request $request)
    {
        $residentes = $this->getDoctrine()->getManager()->getRepository(Resident::class)->GetAll();
        return $this->render('vistas/tablaVecinos.html.twig',
        array('error'=>$_SESSION['error'], 'residentes'=>$residentes
    ));
    
    }
    //-----------------------------------FUNCIONES PRIVADAS--------------------------------
    /**
     * Funcion que chequea parametros de residente
     */
    private function AddCheck($first, $last, $mail, &$phone, &$DPI)
    {
        //Longitud no puede ser menor de 3 para el nombre
        if(strlen($first)<=3)
        {
            $_SESSION['error'] = "Primer nombre debe ser mayor a 3";
            return false;
        }//Longitud no puede ser menor de 3 para el segundo
        if(strlen($last)<=3)
        {
            $_SESSION['error'] = "Apellido nombre debe ser mayor a 3";
            return false;
        }
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mail)<10) 
        {
            $_SESSION['error'] = "Email Incorrecto";
            return false;
        }
        $DPI = str_replace(' ', '', $DPI);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('-', '', $phone);
        if($this->getDoctrine()->getManager()->getRepository(Resident::class)->Exist($DPI))
        {
            $_SESSION['error'] = "Residente ya existe";
            return false;
        }
        return true;
    }
}