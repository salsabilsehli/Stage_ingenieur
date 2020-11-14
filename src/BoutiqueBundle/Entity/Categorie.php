<?php

namespace BoutiqueBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\CategorieRepository")
 */
class Categorie
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="couleur", type="boolean")
     */
    private $couleur;

    /**
     * @return bool
     */
    public function isCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param bool $couleur
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    /**
     * @return bool
     */
    public function isTaille()
    {
        return $this->taille;
    }

    /**
     * @param bool $taille
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="taille", type="boolean")
     */
    private $taille;


    /**
     * @ORM\OneToMany(targetEntity="Categorie_Boutique", mappedBy="categorie")
     */
    private $boutiques;

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
     * @var string
     *
     * @ORM\Column(name="Nom_categorie", type="string", length=255, unique=true)
     *
     */
    private $nomCategorie;

    /**
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="categorie")
     */
    private $produits;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->boutiques=new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param ArrayCollection $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }

    /**
     * Set nomCategorie
     *
     * @param string $nomCategorie
     *
     * @return Categorie
     */
    public function setNomCategorie($nomCategorie)
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * Get nomCategorie
     *
     * @return string
     */
    public function getNomCategorie()
    {
        return $this->nomCategorie;
    }



}

