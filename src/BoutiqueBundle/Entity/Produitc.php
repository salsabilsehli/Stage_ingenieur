<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produitc
 *
 * @ORM\Table(name="produitc")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\ProduitcRepository")
 */
class Produitc
{
    /**
     * @return mixed
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param mixed $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="couleurs")
     * @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     */
    private $produit;

    /**
     * @var array
     *
     * @ORM\Column(name="couleurs", type="array", nullable=true)
     */
    private $couleurs;


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
     * Set couleurs
     *
     * @param array $couleurs
     *
     * @return Produitc
     */
    public function setCouleurs($couleurs)
    {
        $this->couleurs = $couleurs;

        return $this;
    }

    /**
     * Get couleurs
     *
     * @return array
     */
    public function getCouleurs()
    {
        return $this->couleurs;
    }
}

