<?php

namespace BoutiqueBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Marque
 *
 * @ORM\Table(name="marque")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\MarqueRepository")
 */
class Marque
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="Nom_marque", type="string", length=255, unique=true)
     */
    private $nomMarque;

    /**
     * @ORM\OneToMany(targetEntity="Produit_Boutique", mappedBy="marque")
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
     * Set nomMarque
     *
     * @param string $nomMarque
     *
     * @return Marque
     */
    public function setNomMarque($nomMarque)
    {
        $this->nomMarque = $nomMarque;

        return $this;
    }

    /**
     * Get nomMarque
     *
     * @return string
     */
    public function getNomMarque()
    {
        return $this->nomMarque;
    }
}

