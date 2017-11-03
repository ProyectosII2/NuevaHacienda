<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Service_CardRepository")
 */
class Service_CardRepository
{
    public function loadService_CardByIdService_card($id_service_card)
    {
        return $this->createQueryBuilder('s')
			->select('m.service_card_code')
			->from('service_card', 's')
            ->where('m.id_service_card = :id_service_card AND m.isActive = 1')
            ->setParameter('id_service_card', $id_service_card)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
?>