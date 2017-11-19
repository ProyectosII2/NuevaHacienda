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
    /**
     * Chequea si existe residente por Codigo
     * Retorna True si existe
     */
    public function Exist($code)
    {

        $query = $this->createQueryBuilder('p')
        ->from('AppBundle:resident','r')
        ->where('r.resident_code = :code')
        ->setParameter('code', $code)
        ->getQuery();

        if(empty($query->getResult())){ return false; }
        return true;
    }
    /**
     * Select All de Residentes
     */
    public function GetAll()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT u.id_resident, u.resident_code, u.first_name, u.last_name, u.email, u.phone
        FROM AppBundle\Entity\Resident u 
        ORDER BY u.last_name ASC, u.first_name ASC');
        $residents = $query->getResult();
        return $residents;
        
        /* Forma Alternativa:
        return $this->createQueryBuilder('r')
        ->getQuery()
        ->getArrayResult();
        */
    }
    /**
     * Obtiene el Residente por su Codigo
     */
    public function Get_by_Code($code)
    {
        return $this->createQueryBuilder('r')
        ->where('r.resident_code = :code')
        ->setParameter(':code', $code)
        ->getQuery()
        ->getOneOrNullResult();
    }
    /**
     * Query para hacer update
     */
    public function Update($oldResident, $DPI, $mail, $first, $last)
    {
        $oldResident->setFirstName($first);
        $oldResident->setCode($DPI);
        $oldResident->setLastName($last);
        $oldResident->setEmail($mail);
        $oldResident->setUpdateTime();
        $em = $this->getEntityManager();
        $em->flush();
        return true;
    }
    /**
     * Delete permanente de residente
     */
    public function DelResident($resid)
    {
       $em=$this->getEntityManager();
       $em->remove($this->Get_by_Code($resid));
       $em->flush();
    }

}
?>