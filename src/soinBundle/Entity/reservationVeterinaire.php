<?php

namespace soinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * reservationVeterinaire
 *
 * @ORM\Table(name="reservation_veterinaire")
 * @ORM\Entity(repositoryClass="soinBundle\Repository\reservationVeterinaireRepository")
 */
class reservationVeterinaire
{

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="id")
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_user;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="id")
     * @ORM\JoinColumn(nullable=true)
     */

    private $id_veterinaire;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;




    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $date_debut;




    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime")
     */
    private $date_fin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;





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
     * Set idVeterinaire
     *
     * @param \UserBundle\Entity\User $idVeterinaire
     *
     * @return reservationVeterinaire
     */
    public function setIdVeterinaire(\UserBundle\Entity\User $idVeterinaire = null)
    {
        $this->id_veterinaire = $idVeterinaire;

        return $this;
    }

    /**
     * Get idVeterinaire
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdVeterinaire()
    {
        return $this->id_veterinaire;
    }







    /**
     * Set description
     *
     * @param string $description
     *
     * @return reservationVeterinaire
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return reservationVeterinaire
     */
    public function setDateDebut($dateDebut)
    {
        $this->date_debut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return reservationVeterinaire
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Set idUser
     *
     * @param \UserBundle\Entity\User $idUser
     *
     * @return reservationVeterinaire
     */
    public function setIdUser(\UserBundle\Entity\User $idUser = null)
    {
        $this->id_user = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \UserBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->id_user;
    }
}
