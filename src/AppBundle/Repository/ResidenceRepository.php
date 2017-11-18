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
                             ->select('r.code_residence, r.resident_code, r.address, r.telephone, r.sector')
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
                                   $resident_code,
                                   $telephone,
                                   $address,
                                   $sector
                                 );
        
        $em = $this->getEntityManager();
        $em->persist($residence);
        $em->flush();
        return $residence;
    }

    /**
     * Validación de existencia de la residencia
     */
    public function Exist($code)
    {
        $query = $this->createQueryBuilder('p')
                      ->from('AppBundle:residence','r')
                      ->where('r.residence_code = :code')
                      ->setParameter('code', $code)
                      ->getQuery();

        if(empty($query->getResult())){ return false; }
        return true;
    }

    /**
     * Método que permite obtener todas las residencias en
     * base de datos 
     */
    public function GetAll()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT u.residence_code, u.resident_code, u.telephone, u.address, u.sector
                                   FROM AppBundle\Entity\Residence u 
                                   ORDER BY u.residence_code ASC, u.resident_code ASC'
                                 );

        $residents = $query->getResult();

        return $residents;
    }

    /**
     * Obtiene el Residente por su codigo
     */
    public function Get_by_Code($code)
    {
        return $this->createQueryBuilder('r')
                    ->where('r.residence_code = :code')
                    ->setParameter(':code', $code)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * Eliminación de la base de datos de residencias
     */
    public function DelResidence($resid)
    {
       $em= $this->getEntityManager();
       $em->remove(
           $this->Get_by_Code(
               $resid
            )
        );
        $em->flush();
    }
}
?>