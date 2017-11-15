<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
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
        $queryBuilder = $this->createQueryBuilder();

        return $queryBuilder;
    }

    /**
     * Método que permite crear residentes con sus datos
     */
    public function createResident($resident_code, $firstname, $lastname, $email, $phone){

        $em = $this->getDoctrine()->getManager();

        $resident = new Resident();
        $resident->constructor(
            $resident_code,
            $firstname,
            $lastname,
            $email,
            $phone,
            date('dd-mm-yyyy'),
            date('dd-mm-yyyy')
        );

        $em->persist($resident);

        $em->flush();    
    }
}
?>