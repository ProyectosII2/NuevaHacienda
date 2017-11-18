<?php
// src/AppBundle/Entity/Residence.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="residence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResidenceRepository")
 */
class Residence
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="residence_gen", initialValue=1)
     */
    private $id_residence;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $residence_code;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $address;
    
	/**
     * @ORM\Column(type="string", length=30)
     */
    private $sector;

    public function __construct($residence_code, $telephone, $address, $sector)
    {
        $this->residence_code = $residence_code;
		$this->telephone = $telephone;
		$this->address = $address;
		$this->sector = $sector;
    }
	
    public function getResidence_code()
    {
        return $this->residence_code;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getAddress()
    {
        return $this->address;
    }
	
    public function getSector()
    {
        return $this->sector;
    }

    public function setResidence_code($residence_code){
        $this->residence_code = $residence_code;
    }

    public function setTelephone($telephone){
        $this->telephone = $telephone;
    }

    public function setAddress($address){
        $this->address = $address;
    }

    public function setSector($sector){
        $this->sector = $sector;
    }
}
?>