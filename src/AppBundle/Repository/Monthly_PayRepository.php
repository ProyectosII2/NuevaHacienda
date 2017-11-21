<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Monthly_Pay;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Monthly_PayRepository")
 */
class Monthly_PayRepository extends EntityRepository
{

    /**
     * Método que permite crear un nuevo pago
     */
    public function createMonthlyPay($payment_type, $payment_bank, $payment_voucher, $total, $description){
        
        $monthly_pay = new Monthly_Pay($payment_type,
                                       $payment_bank,
                                       $payment_voucher,
                                       $total, 
                                       $description);

        $em = $this->getEntityManager();
        $em->persist($monthly_pay);
        $em->flush();
        return $monthly_pay;
    }

    /**
     * Método que permite verificar la existencia del pago realizado en 
     * la tabla monthly_bill y que tenga asociada un pago
     */
    public function Exist($date, $id_residence){
        $query = $this->createQueryBuilder('mp')
                      ->from('AppBundle:monthly_bill','mb')
                      ->where('mb.date = :date AND mb.id_residence = :id_residence AND mb.id_monthly_pay IS NOT NULL')
                      ->setParameter('date', $date)
                      ->setParameter('id_residence', $id_residence)
                      ->getQuery();
        
        if(empty($query->getResult())){ return false; }
        return true;
    }

    /**
     * Método para obtener todos los datos de la tabla de pagos
     */
    public function GetAll(){
        return $this->createQueryBuilder('r')
                    ->getQuery()
                    ->getArrayResult();
    }

    /**
     * Método para obtener los pagos por id_monthly_pay
     */
    public function Get_by_ID($id)
    {
        return $this->createQueryBuilder('mp')
                    ->where('mp.id_monthly_pay = :id')
                    ->setParameter(':id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * Método que permite realizar un update de un pago
     */
    public function Update($pay, $payment_type, $payment_bank, $payment_voucher, $total, $description)
    {
        $pay->setPayment_type($payment_type);
        $pay->setPayment_bank($payment_bank);
        $pay->setPayment_voucher($payment_voucher);
        $pay->setTotal($total);
        $pay->setDescription($description);

        $em = $this->getEntityManager();
        $em->flush();
        return true;
    }
    
    /**
     * Delete permanente de un pago
     */
    public function DelResident($monthly_pay_id)
    {
       $em=$this->getEntityManager();
       $em->remove($this->Get_by_ID($monthly_pay_id));
       $em->flush();
    }





}
?>