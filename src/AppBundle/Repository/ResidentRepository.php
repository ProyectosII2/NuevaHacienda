<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Resident;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidentRepository")
 */
class ResidentRepository extends EntityRepository
{
    /**
     * Método que permite obtener los residentes de base de datos
     */
    public function loadResidentDataByIdResident($id_resident)
    {
        $queryBuilder = $this->createQueryBuilder('a')
                             ->select('r.resident_code, r.first_name, r.last_name')
                             ->from('AppBundle:resident', 'r')
                             ->where('r.id_resident = :id_resident')
                             ->setParameter(':id_resident', $id_resident)
                             ->getQuery()
                             ->getOneOrNullResult();

        return $queryBuilder;
    }

    /**
     * Método que permite crear residentes con sus datos
     */
    public function createResident($resident_code, $firstname, $lastname, $email, $phone){

        $resident = new Resident($resident_code, 
                                 $firstname,
                                 $lastname,
                                 $email,
                                 $phone
                                );
        
        $em = $this->getEntityManager();
        $em->persist($resident);
        $em->flush();


        return $resident;
    }
}
?>