<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BoutiqueBundle\Entity\Produit_Boutique;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Produit_Boutique", mappedBy="boutique")
     */
    private $produits;



    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @Assert\File(
     *     mimeTypes={ "image/jpeg" , "image/png" })
     *
     */
    private $logo;

    /**
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * @param string $raisonSociale
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;
    }

    /**
     * @var string
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @ORM\Column(name="raison_sociale", type="string", length=255)
     */
    private $raisonSociale;


    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
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
