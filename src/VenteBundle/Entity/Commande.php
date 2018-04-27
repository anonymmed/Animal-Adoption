<?php
/**
 * Created by PhpStorm.
 * User: bn-sk
 * Date: 20/02/2018
 * Time: 19:59
 */

namespace VenteBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;
use Oro\ORM\Query\AST\Platform\Functions\Mysql\Date;

/**
 * @ORM\Table(name="commandes")
 * @ORM\Entity
 */
class Commande
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction") */
    private $paymentInstruction;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string",length=255)
     */
    private $description;
    /**
     * @ORM\Column(name="date_commande", type="date",length=255)
     */
    private $dateCommande;

    /** @ORM\Column(type="decimal", precision=10, scale=5) */
    private $amount;


    public function __construct($amount,$description,$dateCommande)
    {
        $this->amount = $amount;
        $this->description=$description;
        $this->dateCommande=$dateCommande;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Commande
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }
}
