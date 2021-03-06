<?php
// src/AppBundle/Entity/Resident.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="resident")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidentRepository")
 */
class Resident
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="resident_gen", initialValue=1)
     */
    private $id_resident;

    /**
     * @ORM\Column(type="string", length=13, unique=true)
     */
    private $resident_code;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $last_name;
    
	/**
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $email;
	
	/**
     * @ORM\Column(type="integer", unique=true)
     */
    private $phone;
	
    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
	 
	 private $created_at;
	 
	 /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
	 
	 private $updated_at;

    public function __construct($resident_code, $first_name, $last_name, $email, $phone)
    {
        $this->resident_code = $resident_code;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
        $this->phone = $phone;
        $this->created_at = new \DateTime('America/Guatemala');
        $this->updated_at = new \DateTime('America/Guatemala');
        $this->created_at->format('Y\-m\-d\ h:i:s');
        $this->updated_at->format('Y\-m\-d\ h:i:s');
    }
    public function getId()
    {
        return $this->id_resident;
    }
    public function getResident_code()
    {
        return $this->resident_code;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }
	
    public function getEmail()
    {
        return $this->email;
    }
	
	public function getPhone()
    {
        return $this->phone;
    }
	
	public function getCreated_at()
	{
		return $this->created_at;
	}
	
	public function getUpdated_at()
	{
		return $this->updated_at;
    }
    public function setCode($dpi)
    {
        $this->resident_code = $dpi;
    }
    public function setFirstName($first)
    {
        $this->first_name = $first;
    }
    public function setLastName($last)
    {
        $this->last_name = $last;
    }
    public function setPhone($pphone)
    {
        $this->phone = $pphone;
    }
    public function setEmail($pemail)
    {
        $this->email = $pemail;
    }
    public function setUpdateTime()
    {
        $this->updated_at = new \DateTime('America/Guatemala');
        $this->updated_at->format('Y\-m\-d\ h:i:s');
    }
}
?>
