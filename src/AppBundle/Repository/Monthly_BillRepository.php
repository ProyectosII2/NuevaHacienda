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
     * Método que permite obtener todas las cuentas 
     */
    public function GetAll()
    {   
        return $this->createQueryBuilder('r')
                    ->orderBy('r.date', 'ASC')
                    ->getQuery()
                    ->getArrayResult();
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
