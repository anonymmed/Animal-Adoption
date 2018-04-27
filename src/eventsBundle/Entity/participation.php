<?php

namespace eventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="eventsBundle\Repository\participationRepository")
 */
class participation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="eventsBundle\Entity\concours")
     * @ORM\JoinColumn(name="idc", referencedColumnName="id")
     */
    private $idc;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="idp", referencedColumnName="id")
     */

    private $idp;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idp
     *
     * @param \UserBundle\Entity\User $idp
     *
     * @return participation
     */
    public function setIdp(\UserBundle\Entity\User $idp = null)
    {
        $this->idp = $idp;

        return $this;
    }

    /**
     * Get idp
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdp()
    {
        return $this->idp;
    }

    /**
     * Set idc
     *
     * @param \eventsBundle\Entity\concours $idc
     *
     * @return participation
     */
    public function setIdc(\eventsBundle\Entity\concours $idc = null)
    {
        $this->idc = $idc;

        return $this;
    }

    /**
     * Get idc
     *
     * @return \eventsBundle\Entity\concours
     */
    public function getIdc()
    {
        return $this->idc;
    }
}
