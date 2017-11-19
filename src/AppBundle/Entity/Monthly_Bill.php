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
     * @ORM\OneToOne(targetEntity="monthly_pay", inversedBy="monthly_bill_pay")
     * @ORM\JoinColumn(name="id_monthly_pay", referencedColumnName="id_monthly_pay")
     */
	private $id_monthly_pay;
	
	 /**
     * @ORM\Column(type="datetime", name="date")
     */
	 private $date;
	 
    public function __construct()
    {
        $this->id_residence = null;
		$this->id_monthly_pay = null;
    }
	
    public function getId_residence()
    {
        return $this->id_residence;
    }

	public function getId_monthly_pay()
    {
        return $this->id_monthly_pay;
    }
	
    public function getDate()
    {
        return $this->date;
    }
}
?>
