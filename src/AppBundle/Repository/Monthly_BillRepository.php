<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Monthly_BillRepository")
 */
class Monthly_BillRepository
{
    public function loadMonthly_BillByIdMonthly_bill($id_monthly_bill)
    {
        return $this->createQueryBuilder('m')
			->select('m.total, m.description')
			->from('monthly_bill', 'm')
            ->where('m.id_monthly_bill = :id_monthly_bill')
            ->setParameter('id_monthly_bill', $id_monthly_bill)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
?>