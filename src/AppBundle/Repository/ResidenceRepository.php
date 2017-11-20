<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity;
use AppBundle\Entity\Residence;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidenceRepository")
 */
class ResidenceRepository extends EntityRepository
{
    /**
     * Método que permite crear una nueva residencia enviando los parametros necesarios de cada residencia
     */
    public function createResidence($residence_code, $telephone, $address, $sector, $residentid){

        $resident = $this->getEntityManager()->getRepository("AppBundle:Resident")->findOneBy(
            array(
                'id_resident' => $residentid
            )
        );

        $residence = new Residence($residence_code,
                                   $telephone,
                                   $address,
                                   $sector,
                                   $resident
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
        $query = $this->createQueryBuilder('r')
                      ->where('r.residence_code = :code')
                      ->setParameter(':code', $code)
                      ->getQuery();

        if(isset($query) && empty($query->getResult())){ return false; }
        return true;
    }

    /**
     * Método que permite obtener todas las residencias en
     * base de datos 
     */
    public function GetAll()
    {
        /*
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT u.residence_code, u.telephone, u.address, u.sector, u.id_resident
        FROM AppBundle\Entity\Residence u 
        ORDER BY u.sector ASC, u.residence_code ASC' );

        $residence = $query->getResult();

        return $residence;*/
        
        return $this->createQueryBuilder('r')
        ->getQuery()
        ->getArrayResult();
    }

    /**
     * Obtiene el Residente por su codigo
     */
    public function Get_by_Code($code)
    {
        return $this->createQueryBuilder('p')
        ->where('p.residence_code = :code')
        ->setParameter('code', $code)
        ->getQuery()
        ->getOneOrNullResult();
    }
    /**
     * Obtiene el núm de telefono y chequea si existe
     */
    public function CheckIfPhoneExist($phone, $code)
    {
        $query = $this->createQueryBuilder('r')
        ->select('r.telephone')
        ->where('r.telephone = :phone AND r.residence_code != :code')
        ->setParameter('phone', $phone)
        ->setParameter('code', $code)
        ->getQuery();

        if(empty($query->getResult())) { return false;}
        return true;

    }
    /**
     * Query para hacer update
     */
    public function Update($oldreside, $code, $telephone, $address, $sector, $id_resident)
    {
        $oldreside->setResidence_code($code);
        $oldreside->setTelephone($telephone);
        $oldreside->setAddress($address);
        $oldreside->setSector($sector);
        $oldreside->setid_resident($id_resident);
        $em = $this->getEntityManager();
        $em->flush();
        return true;
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