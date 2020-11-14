<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client_Boutique
 *
 * @ORM\Table(name="client__boutique")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\Client_BoutiqueRepository")
 */
class Client_Boutique
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="boutiques")
     * @ORM\JoinColumn(name="id_boutique", referencedColumnName="id")
     */
    private $boutique;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="clients")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;



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

