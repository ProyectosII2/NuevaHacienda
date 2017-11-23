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
     * @Route("/payments",name="payments")
     * @Security("has_role('ROLE_USER')") 
     * hacer pago
     */
    public function loadAllPayments(Request $request)
    {
        $pendient =  $this->getDoctrine()->getManager()->getRepository(Monthly_Bill::class)->GetAll_with_Residence_and_NoPayment();
        $payments = $this->getDoctrine()->getManager()->getRepository(Monthly_Bill::class)->GetAll_AlreadyPaid();
        return $this->render('vistas\tablaPagos.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'pendient' => $pendient,
            'payments' => $payments,
            'error' => ""));
    }
    /**
     * @Route("/addpayment",name="addpayment")
     * @Security("has_role('ROLE_ADMIN')") 
     * hacer pago
     */
    public function loadPaymentForm(Request $request)
    {
        if($request->request->has('bank') && $request->request->has('desc') && $request->request->has('mes')
        && $request->request->has('monto') && $request->request->has('residencia') && $request->request->has('total')
        && $request->request->has('type') && $request->request->has('voucher'))
        {
            $bank = strtolower($request->request->get('bank'));
            $desc = strtolower($request->request->get('desc'));
            $mes = $request->request->get('mes');
            $monto = $request->request->get('monto');
            if($monto == "")
            {
                $residence = $request->request->get('residencia');
                $total = $request->request->get('total');
                $type = strtolower($request->request->get('type'));
                $voucher = strtolower($request->request->get('voucher'));
                $mes = $this->ConvertToDate($mes);
                $residence = $this->getDoctrine()->getManager()->getRepository(Residence::class)->Get_by_Code($residence);
                //Se ingresa pago
                $pago = $this->getDoctrine()->getManager()->getRepository(Monthly_Pay::class)
                ->createMonthlyPay($type, $bank, $voucher, $total, $desc);
                //Se actualiza monthlyBill
                $res = $this->getDoctrine()->getManager()->getRepository(Monthly_Bill::class)
                ->Update($mes, $residence, $pago);
                return $this->forward('AppBundle\Controller\DashboardController::loaddash',
                array(
                    "message"=> "Pago Ingresado Exitosamente"
                    )
                );
            }
            $_SESSION['error'] = "Pago ya a asociado a esta factura";
        }
        $residences = $this->getDoctrine()->getManager()->getRepository(Residence::class)->GetAll_with_Residents();
        $bills = $this->getDoctrine()->getManager()->getRepository(Monthly_Bill::class)->GetAll_with_Residence_and_NoPayment();
        $meses = $this->ArrayMeses();
        return $this->render('vistas\registroPago.html.twig',
        array(
            'appuser' => $this->get('security.token_storage')->getToken()->getUser()->getUsername(), 
            'approle' => $this->get('security.token_storage')->getToken()->getRoles()[0]->getRole(),
            'meses' => $meses,
            'residences' => $residences,
            'bills' => $bills,
            'error'=>$_SESSION['error']
        ));
    }
    //------------------------------FUNCIONES PRIVADAS----------------------
    /**
     * Crea array de meses
     */
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
    /**
     * Converite formato mes:anio a una variable DateTime
     */
    private function ConvertToDate($mes)
    {
        $porciones = explode(":", $mes);
        $tiempo = $porciones[1] . '-' . $porciones[0] . '-' . '1';
        $date = new \DateTime($tiempo);;
        return $date;
    }
}