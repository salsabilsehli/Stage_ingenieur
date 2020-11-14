<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filtre
 *
 * @ORM\Table(name="filtre")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\FiltreRepository")
 */
class Filtre
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
     * @ORM\ManyToOne(targetEntity="Categorie_Boutique", inversedBy="filtres")
     * @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



}

