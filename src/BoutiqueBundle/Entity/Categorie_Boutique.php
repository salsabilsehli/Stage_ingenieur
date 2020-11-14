<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie_Boutique
 *
 * @ORM\Table(name="categorie__boutique")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\Categorie_BoutiqueRepository")
 */
class Categorie_Boutique
{
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
     * @return mixed
     */
    public function getBoutique()
    {
        return $this->boutique;
    }

    /**
     * @param mixed $boutique
     */
    public function setBoutique($boutique)
    {
        $this->boutique = $boutique;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="boutiques")
     * @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     */
    private $categorie;


    /**
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="categorie")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity="Filtre", mappedBy="categorie")
     */
    private $filtres;

    /**
     * @return mixed
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="categories")
     * @ORM\JoinColumn(name="id_boutique", referencedColumnName="id")
     */
    private $boutique;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

