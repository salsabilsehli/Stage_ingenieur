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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @ORM\OneToMany(targetEntity="WishList", mappedBy="produit")
     */
    private $clients;

    /**
     *
     * @var string
     * @ORM\Column(name="nom_prod", type="string", length=255, unique=true)
     *
     */
    private $nomProd;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     *
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
     * @var int
     *
     * @ORM\Column(name="Remise", type="integer")
     */
    private $Remise;

    /**
     * @return int
     */
    public function getRemise()
    {
        return $this->Remise;
    }

    /**
     * @param int $Remise
     */
    public function setRemise($Remise)
    {
        $this->Remise = $Remise;
    }

    /**
     * @return bool
     */
    public function isLivgrat()
    {
        return $this->livgrat;
    }

    /**
     * @param bool $livgrat
     */
    public function setLivgrat($livgrat)
    {
        $this->livgrat = $livgrat;
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="Livraison_gratuite", type="boolean")
     */
    private $livgrat;

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
     * @ORM\ManyToOne(targetEntity="Categorie_Boutique", inversedBy="produits")
     * @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="Marque_Boutique", inversedBy="produits")
     * @ORM\JoinColumn(name="id_marque", referencedColumnName="id")
     */
    private $marque;


    /**
     * @ORM\OneToMany(targetEntity="Produit_Boutique", mappedBy="produit")
     */
    private $boutiques;


    /**
     * @ORM\OneToMany(targetEntity="Produitc", mappedBy="produit")
     */
    private $couleurs;

    /**
     * @ORM\OneToMany(targetEntity="Produit_Commande", mappedBy="produit")
     */
    private $commandes;

    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
        $this->couleurs=[];
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

