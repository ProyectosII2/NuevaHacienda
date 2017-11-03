<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidentRepository")
 */
class ResidentRepository
{
    public function loadResidentDataByIdResident($id_resident)
    {
        return $this->createQueryBuilder('r')
			->select('r.code_resident, r.first_name, r.last_name')
			->from('resident', 'r')
            ->where('r.id_resident = :id_resident')
            ->setParameter('id_resident', $id_resident)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
?>