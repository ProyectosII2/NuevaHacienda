<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity;
use AppBundle\Entity\Monthly_Bill;
use AppBundle\Entity\Monthly_Pay;
use AppBundle\Entity\Residence;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Monthly_BillRepository")
 */
class Monthly_BillRepository extends EntityRepository
{
    /**
     * Método que permite obtener los datos de monthly_bill
     * utilizando como parametro el id
     */
    public function loadMonthly_BillById($id_monthly_bill)
    {
        return $this->createQueryBuilder('m')
                    ->select('m.id_residence, m.id_monthly_pay')
                    ->from('monthly_bill', 'm')
                    ->where('m.id_monthly_bill = :id_monthly_bill')
                    ->setParameter('id_monthly_bill', $id_monthly_bill)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * Método que permite crear una monthly_bill con sus parametros
     */
    public function createMonthlyBill($id_residence, $date){

        $residence = $this->getEntityManager()
                         ->getRepository("AppBundle:Residence")
                         ->findOneBy(
                            array(
                                'id_residence' => $id_residence
                            )
                        );
        
        $monthly_bill = new Monthly_Bill(
            $residence, 
            $date
        );

        $em = $this->getEntityManager();
        $em->persist($monthly_bill);
        $em->flush();
        return $monthly_bill;
    }

    /**
     * Método que permite verificar el ingreso de una nueva cuenta
     */
    public function Exist($id_residence, $date){
        
        $query = $this->createQueryBuilder('r')
                      ->where('r.id_residence = :id_residence AND r.date = :date')
                      ->setParameter(':id_residence', $id_residence)
                      ->setParameter(':date', $date)
                      ->getQuery();

        if(isset($query) && empty($query->getResult())){ return false; }
        return true;
    }

    /**
     * Método que permite obtener todas las cuentas asignadas que no han sido pagadas
     */
    public function GetAll_with_Residence_and_NoPayment()
    {   
        return $this->createQueryBuilder('m')
        ->select('m','r', 'p')
        ->innerJoin('m.id_residence', 'r')
        ->innerJoin('r.id_resident', 'p')
        ->where('m.id_monthly_pay IS NULL')
        ->getQuery()
        ->getArrayResult();
    }
    /**
     * Método que permite obtener todas las cuentas asignadas que no han sido pagadas
     */
    public function GetAll_AlreadyPaid()
    {   
        return $this->createQueryBuilder('m')
        ->select('m','p', 'r', 'q')
        ->innerJoin('m.id_monthly_pay', 'p')
        ->innerJoin('m.id_residence', 'r')
        ->innerJoin('r.id_resident', 'q')
        ->orderBy('m.date', 'ASC')
        ->orderBy('r.residence_code', 'ASC')
        ->getQuery()
        ->getArrayResult();
    }
    /**
     * Update de monthly bill
     */
    public function Update($fechapago, $residencia, $pago)
    {
        //where b.date >= '2017-11-01' AND b.date <= '2017-11-30' AND b.id_residence = '1';
        $inicio = $fechapago->format('Y-m-d');
        $parts = explode('-',$inicio);
        $fin   = date($parts[0] . '-' . $parts[1] . '-t' );
        $bill = $this->createQueryBuilder('b')
        ->where('b.date >= :inicio AND b.date <= :fin AND b.id_residence = :idres')
        ->setParameter('inicio', $inicio)
        ->setParameter('fin', $fin)
        ->setParameter('idres', $residencia->getId())
        ->getQuery()
        ->getOneOrNullResult();
        if($bill != null)
        {
            $bill->setPay($pago);
            $em = $this->getEntityManager();
            $em->flush();
            return true;
        }
        return false;
         
    }

    /**
     * Obtiene el la cuenta por código 
     */
    public function Get_by_Code($code)
    {
        return $this->createQueryBuilder('p')
                    ->where('p.id_monthly_bill = :code')
                    ->setParameter('code', $code)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * Eliminación de la base de datos de cuentas
     */
    public function DelMonthly_bill($id_monthly_bill)
    {
       $em= $this->getEntityManager();
       $em->remove(
           $this->Get_by_Code(
               $id_monthly_bill
            )
        );
        $em->flush();
    }
}

?>
