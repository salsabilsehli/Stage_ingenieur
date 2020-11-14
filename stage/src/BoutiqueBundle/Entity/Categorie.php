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
     * @var string
     * @ORM\Id
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

