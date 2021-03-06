<?php

namespace ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationPetsitter
 *
 * @ORM\Table(name="reservation_petsitter")
 * @ORM\Entity(repositoryClass="ServiceBundle\Repository\ReservationPetsitterRepository")
 */
class ReservationPetsitter
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
     * @var date
     *
     * @ORM\Column(name="dateD", type="date")
     */
    private $dateDebut;

    /**
     * @var date
     *
     * @ORM\Column(name="dateF", type="date")
     */
    private $dateFin;
    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;


    /**
     * @var float
     *
     * @ORM\Column(name="encaisser", type="float")
     */
    private $encaisser;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="idPetsitter",referencedColumnName="id")
     */
    private $idPetsitter;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="idUser",referencedColumnName="id")
     */
    private $idUser;


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
     * Set idPetsitter
     *
     * @param \UserBundle\Entity\User $idPetsitter
     *
     * @return ReservationPetsitter
     */
    public function setIdPetsitter(\UserBundle\Entity\User $idPetsitter = null)
    {
        $this->idPetsitter = $idPetsitter;

        return $this;
    }

    /**
     * Get idPetsitter
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdPetsitter()
    {
        return $this->idPetsitter;
    }

    /**
     * Set idUser
     *
     * @param \UserBundle\Entity\User $idUser
     *
     * @return ReservationPetsitter
     */
    public function setIdUser(\UserBundle\Entity\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set prix
     *
     * @param float $prix
     *
     * @return ReservationPetsitter
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set encaisser
     *
     * @param float $encaisser
     *
     * @return ReservationPetsitter
     */
    public function setEncaisser($encaisser)
    {
        $this->encaisser = $encaisser;

        return $this;
    }

    /**
     * Get encaisser
     *
     * @return float
     */
    public function getEncaisser()
    {
        return $this->encaisser;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return ReservationPetsitter
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return ReservationPetsitter
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
}
