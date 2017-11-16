<?php
namespace AppBundle\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * Metodo para chequear Login (Usado por Symfony)
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('(u.username = :username OR u.email = :email) AND u.isActive=TRUE')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * Metodo para Insertar Usuario
     */
    public function InsertUser($username, $pass, $mail, $rol)
    {
        $em = $this->getEntityManager();
        $user = new User();
        $user->constructor($username, $pass, $mail, $rol);
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($user);
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
        return true;
    }
    /**
     * Select All Usuarios
     */
    public function GetAll()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT u.username, u.email, u.role, u.isActive 
        FROM AppBundle\Entity\User u 
        ORDER BY u.isActive Desc, u.role Asc, u.username Asc');
        $users = $query->getResult();
        return $users;
    
    }
    /**
     * Obtiene Usuario por Username
     */
    public function Get_by_User($username)
    {
        return $this->createQueryBuilder('u')
        ->where('u.username=:username')
        ->setParameter('username', $username)
        ->getQuery()
        ->getOneOrNullResult();
    }
    /**
     * Chequea si existe usuario por username
     * True si existe, False si no
     */
    public function Exist($username)
    {
        $res = $this->Get_by_User($username);
        if(empty($res)){ return false; }
        return true;
    }
    /**
     * Recibe entidad (olduser), modifica parametros y escribe
     */
    public function UpdateUser($olduser, $newuser, $mail, $rol, $active)
    {
        $olduser->setUsername($newuser);
        $olduser->setEmail($mail);
        $olduser->setRol($rol);
        $olduser->setActive($active);
        $em = $this->getEntityManager();
        $em->flush();
        return true;
    }
}
