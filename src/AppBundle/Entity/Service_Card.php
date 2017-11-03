<?php
// src/AppBundle/Entity/Service_Card.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="service_card")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Service_CardRepository")
 */
class Service_Card
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="service_card_gen", initialValue=1)
     */
    private $id_service_card;

    /**
     * @ORM\ManyToOne(targetEntity="residence", inversedBy="service_cards")
     * @ORM\JoinColumn(name="id_residence", referencedColumnName="id_residence")
     */
	private $id_residence;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $service_card_code;

    /**
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    public function __construct($service_card_code)
    {
        $this->service_card_code = $service_card_code;
		$this->isActive = true;
    }
	
	public function getId_residence()
	{
		return $this->id_residence;
	}
	
    public function getService_card_code()
    {
        return $this->service_card_code;
    }
}
?>