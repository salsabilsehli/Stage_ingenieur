<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Marque;
use BoutiqueBundle\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
class AdminController extends Controller
{
    public function commandesAction($username)
    {$em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);
        return $this->render('@Boutique/bkndPages/commandes.html.twig',[
            'username' => $username,
            'boutique' => $us,
        ]);
    }
    public function categoriesAction($username)
    {   $em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);
        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('@Boutique/bkndPages/categories.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'categorie' => $categories
        ]);
    }
    public function produitsAction($username)
    {$em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);
        $produits = $em->getRepository(Produit::class)->findAll();

        return $this->render('@Boutique/bkndPages/produits.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'produit' => $produits
        ]);
    }
    public function clientsAction($username)
    {$em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);
        return $this->render('@Boutique/bkndPages/clients.html.twig',[
            'username' => $username,
            'boutique' => $us,
        ]);
    }
    public function marquesAction($username)
    {$em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);

        $marques = $em->getRepository(Marque::class)->findAll();
        return $this->render('@Boutique/bkndPages/marques.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'marque' => $marques
        ]);
    }

    //editing main informations
    public function edit_profileAction($username)
    {$em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->findOneBy(['username'=> $username]);

        return $this->render('@Boutique/bkndPages/edit_profile.html.twig',[
        'username' => $username,
                'boutique' => $us,
        ]
        );
    }

}
