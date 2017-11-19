<?php
// src/AppBundle/Entity/Monthly_Pay.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="monthly_pay")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Monthly_PayRepository")
 */
class Monthly_Pay
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="monthly_pay_gen", initialValue=1)
     */
    private $id_monthly_pay;

	/**
     * @ORM\Column(type="string", length=20)
     */
    private $payment_type;
	
	 /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $payment_bank;
	 
	/**
     * @ORM\Column(type="string", length=30)
     */
    private $payment_voucher; 
	 
	 /**
     * @ORM\Column(type="decimal", name="total")
     */
	 private $total;
	 
	 /**
     * @ORM\Column(type="string", length=50)
     */
    private $description;
	 
    public function __construct($ppayment_type, $ppayment_bank, $ppayment_voucher, $ptotal, $pdescription)
    {
		$this->payment_type = $ppayment_type;
		$this->payment_bank = $ppayment_bank;
		$this->payment_voucher = $ppayment_voucher;
        $this->total = $ptotal;
		$this->description = $pdescription;
    }

	public function getPayment_type()
    {
        return $this->payment_type;
    }
	
	public function getPayment_bank()
    {
        return $this->payment_bank;
    }
	
    public function getPayment_voucher()
    {
        return $this->payment_voucher;
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
