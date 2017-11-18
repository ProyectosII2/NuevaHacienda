<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Residence;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidenceRepository")
 */
class ResidenceRepository
{
    /**
     * Método que permite obtener cada residencia utilizando como
     * parametro el id de la residencia
     */
    public function loadResidenceDataByIdResidence($id_residence)
    {
        $queryBuilder = $this->createQueryBuilder('r')
                             ->select('r.code_residence, r.address, r.telephone, r.sector')
                             ->where('r.id_residence = :id_residence')
                             ->setParameter('id_residence', $id_residence)
                             ->getQuery()
                             ->getOneOrNullResult();

        return $queryBuilder;
    }

    /**
     * Método que permite crear una nueva residencia enviando los parametros
     * necesarios de cada residencia
     */
    public function createResidence($residence_code, $telephone, $address, $sector){

        $residence = new Residence($residence_code,
                                   $telephone,
                                   $address,
                                   $sector
                                 );
        
        $em = $this->getEntityManager();
        $em->persist($residence);
        $em->flush();
        return $residence;
    }
}
?>