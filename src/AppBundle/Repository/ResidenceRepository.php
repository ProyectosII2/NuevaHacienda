<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidenceRepository")
 */
class ResidenceRepository
{
    public function loadResidenceDataByIdResidence($id_residence)
    {
        return $this->createQueryBuilder('r')
			->select('r.code_residence, r.address')
			->from('residence', 'r')
            ->where('r.id_residence = :id_residence')
            ->setParameter('id_residence', $id_residence)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
?>