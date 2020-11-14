<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $Date;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="commandes")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="commandes_client")
     * @ORM\JoinColumn(name="id_boutique", referencedColumnName="id")
     */
    private $boutique;

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
     * @return bool
     */



    /**
     * @ORM\OneToMany(targetEntity="Produit_Commande", mappedBy="commande")
     */
    private $produits;


    /**
     * @var integer
     *
     * @ORM\Column(name="Valide", type="integer")
     */
    private $valide;

    /**
     * @var integer
     *
     * @ORM\Column(name="nnValide", type="integer")
     */
    private $nnvalide;

    /**
     * @return int
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * @param int $valide
     */
    public function setValide($valide)
    {
        $this->valide = $valide;
    }

    /**
     * @return int
     */
    public function getNnvalide()
    {
        return $this->nnvalide;
    }

    /**
     * @param int $nnvalide
     */
    public function setNnvalide($nnvalide)
    {
        $this->nnvalide = $nnvalide;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
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
     * @var float
     *
     * @ORM\Column(name="prix_commande", type="float")
     */
    private $prix;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * @param \DateTime $Date
     */
    public function setDate($Date)
    {
        $this->Date = $Date;
    }

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="Livraison_gratuite", type="boolean")
     */
    private $livgrat;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    public function __construct()
    {
        $this->Date = new \DateTime('now');
    }


}

