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
use AppBundle\Entity\Monthly_Bill;
use AppBundle\Entity\Residence;
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
        if($request->request->has('bank') && $request->request->has('desc') && $request->request->has('mes')
        && $request->request->has('monto') && $request->request->has('residence') && $request->request->has('total')
        && $request->request->has('type') && $request->request->has('voucher'))
        {
            $bank = $request->request->get('bank');
            $desc = $request->request->get('desc');
            $mes = $request->request->get('mes');
            $monto = $request->request->get('monto');
            $residence = $request->request->get('residence');
            $total = $request->request->get('total');
            $type = $request->request->get('type');
            $voucher = $request->request->get('voucher');
            
            return $this->render('vistas_test\exito.html.twig',
            array(
                'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
                'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
                'error'=>""
            ));
        }
        $residences = $this->getDoctrine()->getManager()->getRepository(Residence::class)->GetAll();
        $bills = $this->getDoctrine()->getManager()->getRepository(Monthly_Bill::class)->GetAll_with_Residence();
        $meses = $this->ArrayMeses();
        dump($residences, $bills, $meses);
        return $this->render('vistas\registroPago.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'meses' => $meses,
            'residences' => $residences,
            'bills' => $bills,
            'error'=>""
        ));
    }
    //------------------------------FUNCIONES PRIVADAS----------------------
    private function ArrayMeses()
    {
        $m = date('m');
        $a = date('Y');
        $meses = array();
        $mes = null;
        for ($i = 1; $i <= 12; $i++) {
            if($m==0){ $m=12;}
            switch ($m) {
                case 1:
                 $mes = array('mes'=>'Enero', 'num'=>'01','anio' => $a);
                    break;
                case 2:
                $mes = array('mes'=>'Febrero', 'num'=>'02','anio' => $a);
                    break;
                case 3:
                $mes = array('mes'=>'Marzo', 'num'=>'03','anio' => $a);
                    break;
                case 4:
                $mes = array('mes'=>'Abril', 'num'=>'04','anio' => $a);
                    break;
                case 5:
                $mes = array('mes'=>'Mayo', 'num'=>'05','anio' => $a);
                    break;
                case 6:
                $mes = array('mes'=>'Junio', 'num'=>'06','anio' => $a);
                    break;
                case 7:
                $mes = array('mes'=>'Julio', 'num'=>'07','anio' => $a);
                    break;
                case 8:
                $mes = array('mes'=>'Agosto', 'num'=>'08','anio' => $a);
                    break;
                case 9:
                $mes = array('mes'=>'Septiembre', 'num'=>'09','anio' => $a);
                    break;
                case 10:
                $mes = array('mes'=>'Octubre', 'num'=>'10','anio' => $a);
                    break;
                case 11:
                $mes = array('mes'=>'Noviembre', 'num'=>'11','anio' => $a);
                    break;
                case 12:
                $a = $a-1;
                $mes = array('mes'=>'Diciembre', 'num'=>'12','anio' => $a);
                    break;
            }
            array_push($meses, $mes);
            $m=$m-1;
        }
        return $meses;
    }
}