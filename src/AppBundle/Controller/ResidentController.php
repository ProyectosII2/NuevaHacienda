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
            $firstname = strtoupper($request->request->get('firstname'));
            $secondname = strtoupper($request->request->get('secondname'));
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
        return $this->render('vistas/registroVecino.html.twig', 
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'error'=>$_SESSION['error']));
    }
    /**
     * @Route("/allresidents",name="allresidents")
     * @Security("has_role('ROLE_USER')") 
     * Carga todos los residentes
     */
    public function loadAllResidentsForm(Request $request)
    {
        $residentes = $this->getDoctrine()->getManager()->getRepository(Resident::class)->GetAll();
        return $this->render('vistas/tablaVecinos.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'error'=>$_SESSION['error'], 'residentes'=>$residentes
    ));
    }

    /**
     * @Route("/updateresident/{code}", requirements={"code" = "\d+"}, name="updateresident")
     * @Security("has_role('ROLE_ADMIN')") 
     * Controlador para hacer update de resident segun su codigo
     */
    public function loadResidentUpdateForm(Request $request, $code)
    {
        $dpicode = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_Code($code);
        return $this->render('vistas\actualizarVecino.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'code'=>$dpicode->getResident_code(),
            'first'=>$dpicode->getFirst_Name(),
            'second'=>$dpicode->getLast_name(),
            'mail'=>$dpicode->getEmail(),
            'phone'=>$dpicode->getPhone(),
            'error'=>""
        ));
    }
    /**
     * @Route("/checkupdateresident", name="checkupdateresident")
     * @Security("has_role('ROLE_ADMIN')") 
     * Controlador para hacer update de resident
     */
    public function CheckUpdateResident(Request $request)
    {
        //Revisar que esten los parametros
        $message = "";
        $oldDPI = $request->request->get('oldDPI'); //residente
        if($request->request->has("DPI") && $request->request->has("secondname") && 
        $request->request->has("firstname") && $request->request->has("email") &&
        $request->request->has("phone"))
        {
            //Asignar Variables
            $dpi = strtolower($request->request->get("DPI"));
            $firstname = strtoupper($request->request->get("firstname"));
            $lastname = strtoupper($request->request->get("secondname"));
            $phone = $request->request->get("phone");
            $mail = strtolower($request->request->get("email"));
            if($this->UpdateCheck($firstname, $lastname, $mail, $phone, $dpi, $oldDPI))
            {
                //Datos validos
                //Obtener entidad anterior
                $oldResident = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_Code($oldDPI);
                $this->getDoctrine()->getManager()->getRepository(Resident::class)->Update($oldResident, $dpi, $mail, $firstname, $lastname, $phone);
                //Forward a Dashboard
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array(
                    "message"=>"Actualización exitosa de residente"));
            }

        }
        $dpicode = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_Code($oldDPI);
        return $this->render('vistas\actualizarVecino.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'code'=>$dpicode->getResident_code(),
            'first'=>$dpicode->getFirst_Name(),
            'second'=>$dpicode->getLast_name(),
            'mail'=>$dpicode->getEmail(),
            'phone'=>$dpicode->getPhone(),
            'error'=>$_SESSION['error']
        ));
    }
    /**
     * @Route("/deleteresident/{code}",  requirements={"code" = "\d+"}, name="deleteresident")
     * @Security("has_role('ROLE_ADMIN')") 
     */
    public function deleteResident(Request $request, $code)
    {
        //Obtiene el residente
        $this->getDoctrine()->getManager()->getRepository(Resident::class)->DelResident($code);
        return $this->forward('AppBundle\Controller\DashboardController::loaddash',
        array( "message"=>"Residente eliminado exitosamente"));
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
    /**
     * Funcion que chequea variables de residente para hacer update
     */
    private function UpdateCheck($first, $last, $mail, &$phone, &$DPI, $oldDPI)
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
        if($oldDPI!=$DPI)
        {
            if($this->getDoctrine()->getManager()->getRepository(Resident::class)->Exist($DPI))
            {
                $_SESSION['error'] = "Residente ya existe";
                return false;
            }
        }
        if($this->getDoctrine()->getManager()->getRepository(Resident::class)->CheckIfPhoneExist($phone, $DPI))
        {
            $_SESSION['error'] = "Telefono ya existe";
            return false;
        }
        if($this->getDoctrine()->getManager()->getRepository(Resident::class)->CheckIfMailExist($mail, $DPI))
        {
            $_SESSION['error'] = "Email ya existe";
            return false;
        }
        return true;
    }
}