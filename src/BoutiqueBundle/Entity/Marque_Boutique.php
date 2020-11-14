<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marque_Boutique
 *
 * @ORM\Table(name="marque__boutique")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\Marque_BoutiqueRepository")
 */
class Marque_Boutique
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
     * @ORM\ManyToOne(targetEntity="Marque", inversedBy="boutiques")
     * @ORM\JoinColumn(name="id_marque", referencedColumnName="id")
     */
    private $marque;

    /**
     * @ORM\OneToMany(targetEntity="Produit_Boutique", mappedBy="marque")
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="marques")
     * @ORM\JoinColumn(name="id_boutique", referencedColumnName="id")
     */
    private $boutique;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

