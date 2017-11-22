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
     * @Route("/allresidences",name="allresidences")
     * @Security("has_role('ROLE_USER')") 
     * Carga todos las residencias
     */
    public function loadAllResidencesForm(Request $request)
    {
        $residences = $this->getDoctrine()->getManager()->getRepository(Residence::class)->GetAll();
  
        return $this->render('vistas/tablaResidencias.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'error'=>$_SESSION['error'], 
            'residences'=>$residences
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
        && $request->request->has('addr') && $request->request->has('sector') && $request->request->has('residente'))
        {
            //pasar parametros a variables
            $residencecode = $request->request->get('idres');
            $tele = $request->request->get('tel');
            $addre = strtolower($request->request->get('addr'));
            $sector = strtolower($request->request->get('sector'));
            $residentid = $request->request->get('residente');
            //Valida si tiene residente
            if($residentid=="")
            {
                $residentid=null;
            }
            if($this->AddCheckResidence($residencecode,$tele,$addre,$sector))
            {
                //Pasó validación
                $this->getDoctrine()->getManager()->getRepository(Residence::class)->createResidence($residencecode, $tele, $addre, $sector, $residentid);
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array(
                    "message"=> "Residencia Ingresada"
                     )
                );

            }

        }
        $residentes = $this->getDoctrine()->getManager()->getRepository('AppBundle\Entity\Resident')->GetAll();
        
        return $this->render('vistas/registroResidencia.html.twig', 
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'residentes' => $residentes,
            'error'=>$_SESSION['error']));
    }
    /**
     * @Route("/updateresidence/{code}", requirements={"code" = "\d+"}, name="updateresidence")
     * @Security("has_role('ROLE_ADMIN')") 
     * Ingreso de residencias
     */
    public function loadResidenceUpdateForm(Request $request, $code)
    {
        $_SESSION['error'] = "";
        $residencecode = $this->getDoctrine()->getManager()->getRepository(Residence::class)->Get_by_Code($code);
        //Obtener resident de residencia
        $resident = 0;
        if($residencecode->getid_resident()!=null)
        {
            if($residencecode->getid_resident()->getId()!=0)
            {
                $temp = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_ID($residencecode->getid_resident()->getId());
                if($temp!=null){ $resident = $temp->getId();}
            }
        }
        $residents = $this->getDoctrine()->getManager()->getRepository(Resident::class)->GetAll();
        return $this->render('vistas\actualizarResidencia.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'code' => $residencecode->getResidence_code(),
            'telefono' => $residencecode->getTelephone(),
            'direccion' => $residencecode->getAddress(),
            'sector' => $residencecode->getSector(),
            'residentes' => $residents,
            'duenioid' => $resident,
            'error'=>$_SESSION['error']
        ));
    }
    /**
     * @Route("/checkupdateresidence",name="checkupdateresidence")
     * @Security("has_role('ROLE_ADMIN')") 
     * Revisar parametros de update
     */
    public function loadUpdateCheckResidence(Request $request)
    {
        $message = "";
        $code = $request->request->get("original");
        if($request->request->has('idres') && $request->request->has('tel') 
        && $request->request->has('sector'))
        {
            //pasar parametros a variables
            $residencecode = $request->request->get('idres');
            $tele = $request->request->get('tel');
            $sector = strtolower($request->request->get('sector'));
            $residentid = null;
            if($request->request->has('residente')) { $residentid = $request->request->get('residente');} //ObtenerID
            if($residentid=="")
            {
                $residentid = null;
            }
            if($this->UpdateCheckResidence($tele, $code))
            {
                //Datos validos
                //Obtener residencia vieja
                $oldres = $this->getDoctrine()->getManager()->getRepository(Residence::class)->Get_by_Code($residencecode);
                if($residentid != null)
                {
                    $residentid = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_ID($residentid);
                }
                $this->getDoctrine()->getManager()->getRepository(Residence::class)->Update($oldres, $residencecode, $tele, $sector, $residentid);
                //Forward a Dashboard
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array(
                    "message"=>"Actualización exitosa de residencia"));

            }

        }
        $residencecode = $this->getDoctrine()->getManager()->getRepository(Residence::class)->Get_by_Code($code);
        //Obtener resident de residencia
        $resident = 0;
        if($residencecode->getid_resident()->getId()!=0)
        {
            $temp = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_ID($residencecode->getid_resident()->getId());
            if($temp!=null){ $resident = $temp->getId();}
        }
        $residents = $this->getDoctrine()->getManager()->getRepository(Resident::class)->GetAll();
      
        return $this->render('vistas\actualizarResidencia.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'code' => $residencecode->getResidence_code(),
            'telefono' => $residencecode->getTelephone(),
            'direccion' => $residencecode->getAddress(),
            'sector' => $residencecode->getSector(),
            'residentes' => $residents,
            'duenioid' => $resident,
            'error'=>$_SESSION['error']
        ));


    }
    
    /**
     * @Route("/residencesby/{dpi}",  requirements={"dpi" = "\d+"}, name="residencesby")
     * @Security("has_role('ROLE_USER')") 
     */
    public function loadresidencesbyresident(Request $request, $dpi)
    {
        //Obtiene el residente
        $residente = $this->getDoctrine()->getManager()->getRepository(Resident::class)->Get_by_Code($dpi);
        
        $residencias = $this->getDoctrine()->getManager()->getRepository(Residence::class)->ResidenciasDe($residente);
       
        return $this->render('vistas/tablaResidenciasVecinos.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'residencias' => $residencias,
            'residente' => $residente));
    }



    
    //-----------------------------------FUNCIONES PRIVADAS--------------------------------
    private function AddCheckResidence($residencecode, &$tele, $addr, $sector)
    {
        //Longitud no puede ser menor de 3 para el segundo
        if(strlen($addr)<=3)
        {
            $_SESSION['error'] = "Dirección no puede ser menor de 3 caracteres";
            return false;
        }
        if(strlen($sector)<1)
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
    private function UpdateCheckResidence(&$tele, $code)
    {
        //Longitud no puede ser menor de 3 para el segundo
        $tele = str_replace(' ', '', $tele);
        $tele = str_replace('-', '', $tele);
        //Chequear si no existe algun unique en la bdd, telephone
        if($this->getDoctrine()->getManager()->getRepository(Residence::class)->CheckIfPhoneExist($tele, $code))
        {
            $_SESSION['error'] = "Telefono ya existe";
            return false;
        }
        return true;
    }
}