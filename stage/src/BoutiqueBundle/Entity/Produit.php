<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="nom_prod", type="string", length=255, unique=true)
     *
     */
    private $nomProd;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="Qte_Stock", type="integer")
     */
    private $QteStock;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @Assert\File(
     *     mimeTypes={ "image/jpeg" , "image/png" })
     *
     */
    private $image;

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getQteStock()
    {
        return $this->QteStock;
    }

    /**
     * @param int $Qte_Stock
     */
    public function setQteStock($QteStock)
    {
        $this->QteStock = $QteStock;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="produits")
     * @ORM\JoinColumn(name="nom_categorie", referencedColumnName="Nom_categorie")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="Marque", inversedBy="produits")
     * @ORM\JoinColumn(name="nom_marque", referencedColumnName="Nom_marque")
     */
    private $marque;


    /**
     * @ORM\OneToMany(targetEntity="Produit_Boutique", mappedBy="produit")
     */
    private $boutiques;

    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getBoutiques()
    {
        return $this->boutiques;
    }

    /**
     * @param ArrayCollection $boutiques
     */
    public function setBoutiques($boutiques)
    {
        $this->boutiques = $boutiques;
    }




    /**
     * @return mixed
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * @param mixed $marque
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }


    /**
     * Set nomProd
     *
     * @param string $nomProd
     *
     * @return Produit
     */
    public function setNomProd($nomProd)
    {
        $this->nomProd = $nomProd;

        return $this;
    }

    /**
     * Get nomProd
     *
     * @return string
     */
    public function getNomProd()
    {
        return $this->nomProd;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
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
     * Set prix
     *
     * @param float $prix
     *
     * @return Produit
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


}

