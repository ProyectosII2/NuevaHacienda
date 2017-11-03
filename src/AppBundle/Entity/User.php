<?php
// src/AppBundle/Entity/User.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_gen", initialValue=1)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role;
    
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
   // $username, $pass, $mail, $rol
    public function constructor($puser, $ppass, $pemail, $prole)
    {
        $this->username = $puser;
        $this->password = $ppass;
        $this->email = $pemail;
        $this->role = $prole;
        $this->isActive = true;
    }
    public function __construct()
    {
        $this->isActive = true;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function getRoles() 
    {
        return array($this->role);
    }
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->role,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->role,
        ) = unserialize($serialized);
    }
}
