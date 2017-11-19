<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Monthly_PayRepository")
 */
class Monthly_BillRepository
{
    public function loadMonthly_PayByIdMonthly_pay($id_monthly_pay)
    {
        return $this->createQueryBuilder('m')
			->select('m.total, m.description')
			->from('monthly_pay', 'm')
            ->where('m.id_monthly_pay = :id_monthly_pay')
            ->setParameter('id_monthly_pay', $id_monthly_pay)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
?>