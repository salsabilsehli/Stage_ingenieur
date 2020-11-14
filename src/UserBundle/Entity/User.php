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
        $this->categories = new ArrayCollection();
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
     * @return mixed
     */
    public function getMarques()
    {
        return $this->marques;
    }

    /**
     * @param mixed $marques
     */
    public function setMarques($marques)
    {
        $this->marques = $marques;
    }


    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Categorie_Boutique", mappedBy="boutique")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\WishList_Boutique", mappedBy="boutique")
     */
    private $wishlists;

    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Commande", mappedBy="client")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Commande", mappedBy="boutique")
     */
    private $commandes_client;


    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Marque_Boutique", mappedBy="boutique")
     */
    private $marques;

    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\WishList", mappedBy="client")
     */
    private $produitsDuClient;

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return mixed
     */
    public function getProduitsDuClient()
    {
        return $this->produitsDuClient;
    }

    /**
     * @param mixed $produitsDuClient
     */
    public function setProduitsDuClient($produitsDuClient)
    {
        $this->produitsDuClient = $produitsDuClient;
    }

    /**
     * @param ArrayCollection $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }



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
     * @var string
     *
     * @ORM\Column(name="num_tlph", type="string",nullable=true)
     *
     */
    private $num;

    /**
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param string $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }

    /**
     * @return string
     */
    public function getRaisonsociale()
    {
        return $this->raisonsociale;
    }

    /**
     * @param string $raisonsociale
     */
    public function setRaisonsociale($raisonsociale)
    {
        $this->raisonsociale = $raisonsociale;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="raison_sociale", type="string", length=255, nullable=true)
     *
     *
     */
    private $raisonsociale;

    /**
     * @var string
     *
     * @ORM\Column(name="date_naissance", type="string", length=255, nullable=true)
     *
     *
     */
    private $dateNaissance;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="nom_de_famille", type="string", length=255, nullable=true)
     *
     *
     */
    private $nomDeFamille;

    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="string", length=255, nullable=true)
     *
     *
     */
    private $Adresse;

    /**
     * @return mixed
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * @param mixed $commandes
     */
    public function setCommandes($commandes)
    {
        $this->commandes = $commandes;
    }

    /**
     * @return mixed
     */
    public function getCommandesClient()
    {
        return $this->commandes_client;
    }

    /**
     * @param mixed $commandes_client
     */
    public function setCommandesClient($commandes_client)
    {
        $this->commandes_client = $commandes_client;
    }

    /**
     * @return mixed
     */
    public function getWishlists()
    {
        return $this->wishlists;
    }

    /**
     * @param mixed $wishlists
     */
    public function setWishlists($wishlists)
    {
        $this->wishlists = $wishlists;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255, nullable=true)
     *
     *
     */
    private $theme;

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->Adresse;
    }

    /**
     * @param string $Adresse
     */
    public function setAdresse($Adresse)
    {
        $this->Adresse = $Adresse;
    }

    /**
     * @return string
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * @param string $dateNaissance
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @return string
     */
    public function getNomDeFamille()
    {
        return $this->nomDeFamille;
    }

    /**
     * @param string $nomDeFamille
     */
    public function setNomDeFamille($nomDeFamille)
    {
        $this->nomDeFamille = $nomDeFamille;
    }


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

    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return mixed
     */
    public function getBoutiques()
    {
        return $this->boutiques;
    }

    /**
     * @param mixed $boutiques
     */
    public function setBoutiques($boutiques)
    {
        $this->boutiques = $boutiques;
    }

    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Client_Boutique", mappedBy="boutique")
     */
    private $clients;

    /**
     * @ORM\OneToMany(targetEntity="BoutiqueBundle\Entity\Client_Boutique", mappedBy="client")
     */
    private $boutiques;

}
