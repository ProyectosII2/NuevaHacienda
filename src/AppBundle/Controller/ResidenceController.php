<?php

//src/AppBundle/Controller/ResidenceController.php
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
use AppBundle\Entity\Residence;
use AppBundle\Entity\Resident;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class ResidenceController extends Controller
{
    /**
     * @Route("/showall",name="showall")
     * Ver sectores
     */
    public function loadResidencesForm(Request $request)
    {
        return $this->render('vistas/tablaResidencias.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole()
    ));
    }

    /**
     * @Route("/viewresidence",name="viewresidence")
     * Ver sectores
     */
    public function viewResidencesForm(Request $request)
    {
        return $this->render('vistas/tablaResidenciasVecinos.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole()
    ));
    }

    /**
     * @Route("/allresidences",name="allresidences")
     * @Security("has_role('ROLE_USER')") 
     * Carga todos las residencias
     */
    public function loadAllResidencesForm(Request $request)
    {
        $residences = $this->getDoctrine()->getManager()->getRepository(Residence::class)->GetAll();
        return $this->render('vistas_test/exito.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'error'=>$_SESSION['error'], 'residences'=>$residences
    ));
    }

    /**
     * @Route("/addresidence",name="addresidence")
     * @Security("has_role('ROLE_ADMIN')") 
     * Ingreso de residencias
     */
    public function loadResidenceAddForm(Request $request)
    {
        $_SESSION['error'] = null;
        //Revisa si hay POST
        if($request->request->has('idres') && $request->request->has('tel') 
        && $request->request->has('addr') && $request->request->has('sector'))
        {
            //pasar parametros a variables
            $residencecode = $request->request->get('idres');
            $tele = $request->request->get('tel');
            $addre = strtolower($request->request->get('addr'));
            $sector = strtolower($request->request->get('sector'));
            $residentid = null;
            //Valida si tiene residente
            if($request->request->has('residente')) { $resident = $request->request->get('residente');} //ObtenerID
            //Validación de parametros
            if($this->AddCheckResidence($residencecode,$tele,$addre,$sector,$residentid))
            {
                //Pasó validación
                $this->getDoctrine()->getManager()->getRepository(Residence::class)->
                createResidence($residencecode, $tele, $addr, $sector, $residentid);
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array("message"=>"Residente Ingresado"));

            }

        }
        $residentes = $this->getDoctrine()->getManager()->getRepository('AppBundle\Entity\Resident')->GetAll();
        dump($residentes);
        return $this->render('vistas/verResidencia.html.twig', 
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'residentes' => $residentes,
            'error'=>$_SESSION['error']));
    }


    
    //-----------------------------------FUNCIONES PRIVADAS--------------------------------
    private function AddCheckResidence($residencecode, &$tele, $addr, $sector, $residentid)
    {
        //Longitud no puede ser menor de 3 para el segundo
        if(strlen($addr)<=3)
        {
            $_SESSION['error'] = "Dirección no puede ser menor de 3 caracteres";
            return false;
        }
        if(strlen($sector)<=1)
        {
            $_SESSION['error'] = "Sector no puede ser menor de 1 caracteres";
            return false;
        }
        $tele = str_replace(' ', '', $tele);
        $tele = str_replace('-', '', $tele);
        if($this->getDoctrine()->getManager()->getRepository(Residence::class)->Exist($residencecode))
        {
            $_SESSION['error'] = "Residencia ya existe";
            return false;
        }
        return true;
    }
}