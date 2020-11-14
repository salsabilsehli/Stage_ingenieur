<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
/**
 * Produit_Boutique
 *
 * @ORM\Table(name="produit__boutique")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\Produit_BoutiqueRepository")
 */
class Produit_Boutique
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="boutiques")
     * @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="produits")
     * @ORM\JoinColumn(name="id_boutique", referencedColumnName="id")
     */
    private $boutique;

    /**
     * @return string
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param string $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
    }

    /**
     * @return int
     */
    public function getBoutique()
    {
        return $this->boutique;
    }

    /**
     * @param int $boutique
     */
    public function setBoutique($boutique)
    {
        $this->boutique = $boutique;
    }



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

