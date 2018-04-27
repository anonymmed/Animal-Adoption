<?php

namespace VenteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ligne
 *
 * @ORM\Table(name="ligne")
 * @ORM\Entity(repositoryClass="VenteBundle\Repository\LigneRepository")
 */
class Ligne
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
     * @ORM\ManyToOne(targetEntity="VenteBundle\Entity\Produit")
     * @ORM\JoinColumn(name="produit", referencedColumnName="id")
     */
    private $Produit;

    /**
     * @var int
     *
     * @ORM\Column(name="idClient", type="integer")
     */
    private $idClient;
    /**
     * @var string
     *
     * @ORM\Column(name="Image", type="string", length=255)
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     *
     */
    private $Image;
    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;


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
     * Set idClient
     *
     * @param integer $idClient
     *
     * @return Ligne
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return int
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Ligne
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Ligne
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Ligne
     */
    public function setImage($image)
    {
        $this->Image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->Image;
    }

    /**
     * Set produit
     *
     * @param \VenteBundle\Entity\Produit $produit
     *
     * @return Ligne
     */
    public function setProduit(\VenteBundle\Entity\Produit $produit = null)
    {
        $this->Produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \VenteBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->Produit;
    }
}
