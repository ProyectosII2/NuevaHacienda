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
     * @ORM\Column(type="string", length=30,unique=true)
     */
    private $payment_voucher; 
	 
	 /**
     * @ORM\Column(type="decimal", name="total")
     */
	 private $total;
	 
	 /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $description;
	 
    public function __construct($payment_type, $payment_bank, $payment_voucher, $ptotal, $pdescription)
    {
		$this->payment_type = $payment_type;
		$this->payment_bank = $payment_bank;
		$this->payment_voucher = $payment_voucher;
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

	public function setPayment_type($payment_type)
    {
        $this->payment_type = $payment_type;
    }
	
	public function setPayment_bank($payment_bank)
    {
        $this->payment_bank = $payment_bank;
    }
	
    public function setPayment_voucher($payment_voucher)
    {
        $this->payment_voucher = $payment_voucher;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }
	
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
?>
