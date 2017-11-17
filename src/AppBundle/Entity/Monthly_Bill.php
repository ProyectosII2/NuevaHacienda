<?php
// src/AppBundle/Entity/Monthly_Bill.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="monthly_bill")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Monthly_BillRepository")
 */
class Monthly_Bill
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="monthly_bill_gen", initialValue=1)
     */
    private $id_monthly_bill;

	/**
     * @ORM\ManyToOne(targetEntity="residence", inversedBy="monthly_bills")
     * @ORM\JoinColumn(name="id_residence", referencedColumnName="id_residence")
     */
	private $id_residence;
	
	 /**
     * @ORM\Column(type="datetime", name="date")
     */
	 private $date;
	 
	 /**
     * @ORM\Column(type="decimal", name="total")
     */
	 private $total;
	 
	 /**
     * @ORM\Column(type="string", length=50)
     */
    private $description;
	 
    public function __construct($ptotal, $pdescription)
    {
        $this->total = $ptotal;
		$this->description = $pdescription;
    }
	
    public function getId_residence()
    {
        return $this->id_residence;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getTotal()
    {
        return $this->total;
    }
	
    public function getDescription()
    {
        return $this->description;
    }
}
?>