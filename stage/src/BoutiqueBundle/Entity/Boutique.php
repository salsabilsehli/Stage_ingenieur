<?php

namespace BoutiqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Boutique
 *
 * @ORM\Table(name="boutique")
 * @ORM\Entity(repositoryClass="BoutiqueBundle\Repository\BoutiqueRepository")
 */
class Boutique implements UserInterface
{


    /**
     * @var string
     *
     * @ORM\Column(name="nom_boutique", type="string", length=255, unique=true)
     *
     * @ORM\Id
     *
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-z]{4,15}$/",
     *     message="seulement des lettres (longeur de 4 à 15)"
     * )
     */
    private $nomBoutique;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @Assert\Email(
     *     message="l'e-mail '{{ value }}' n'est pas valide"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mot_de_passe", type="string", length=255)
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @Assert\Length(
     *     min="4",
     *     max="10",
     *     minMessage="le mot de passe doit contenir au moins 4 caractères",
     *     maxMessage="le mot de passe doit contenir au maximun 10 caractères"
     * )
     *
     */
    private $motDePasse;

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
     * @Assert\NotNull(
     *     message="doit etre remplie"
     * )
     * @ORM\Column(name="raison_sociale", type="string", length=255)
     */
    private $raisonSociale;


    /**
     * Set nomBoutique
     *
     * @param string $nomBoutique
     *
     * @return Boutique
     */
    public function setNomBoutique($nomBoutique)
    {
        $this->nomBoutique = $nomBoutique;

        return $this;
    }

    /**
     * Get nomBoutique
     *
     * @return string
     */
    public function getNomBoutique()
    {
        return $this->nomBoutique;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Boutique
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set motDePasse
     *
     * @param string $motDePasse
     *
     * @return Boutique
     */
    public function setMotDePasse($motDePasse)
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * Get motDePasse
     *
     * @return string
     */
    public function getMotDePasse()
    {
        return $this->motDePasse;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Boutique
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Boutique
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [
            'ROLE_USER'
        ];

    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}

