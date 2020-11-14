<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Categorie_Boutique;
use BoutiqueBundle\Entity\Marque;
use BoutiqueBundle\Entity\Marque_Boutique;
use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\Produit_Boutique;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class AdminController extends Controller
{
    public function commandesAction($username)
    {$em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }

        //liste des clients



        $queryids=$em->createQuery(
            'SELECT  u.nomDeFamille , u.logo , SUM(c.valide) , SUM(c.nnvalide) , IDENTITY(c.client)
    FROM BoutiqueBundle:Commande c , UserBundle:User u
    WHERE c.boutique = :usid AND u.id = c.client GROUP BY u.username ORDER BY u.username ASC'
        )->setParameter('usid', $usid);

        $prdsids=$queryids->getResult();


        return $this->render('@Boutique/bkndPages/commandes.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'prdids'=>$prdsids
        ]);
    }
    public function categoriesAction($username)
    {   $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $categoriesduboutique = $em->getRepository(Categorie_Boutique::class)->findBy(['boutique' => $usid]);

        return $this->render('@Boutique/bkndPages/categories.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'categorie' => $categoriesduboutique
        ]);
    }
    public function produitsAction($username)
    {$em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $produitsduboutique = $em->getRepository(Produit_Boutique::class)->findBy(['boutique' => $usid]);

        return $this->render('@Boutique/bkndPages/produits.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'produits' => $produitsduboutique
        ]);
    }
    public function clientsAction($username)
    {$em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $query=$em->createQuery(
            'SELECT  c
    FROM BoutiqueBundle:Client_Boutique c 
    WHERE c.boutique = :usid '
        )->setParameter('usid', $usid);

        $clts=$query->getResult();




        return $this->render('@Boutique/bkndPages/clients.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'clts'=>$clts
        ]);
    }
    public function marquesAction($username)
    {$em = $this->getDoctrine()->getManager();

        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $marquesduboutique = $em->getRepository(Marque_Boutique::class)->findBy(['boutique' => $usid]);
        return $this->render('@Boutique/bkndPages/marques.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'marque' => $marquesduboutique
        ]);
    }

    //editing main informations
    public function ajoutprofileAction(Request $request,$username)
    {
        $em = $this->getDoctrine()->getManager();
        $q = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role AND u.username = :username'
            )->setParameter('role', '%"ROLE_AGENT"%')
            ->setParameter('username', $username);

        $user = $q->getResult();
        foreach ($user as $u) {
            $us = $u;
        }
        foreach ($user as $u) {
            $usid = $u->getId();
        }
        $form=$this->createForm('UserBundle\Form\AdminType',$us);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($us);
            $em->flush();
            foreach ($user as $u) {
                $rsbd= $u->getRaisonsociale();
            }
            foreach ($user as $u) {
                $numbd=$u->getNum();
            }
            foreach ($user as $u) {
                $theme=$u->getTheme();
            }

            //5 nouveaux produits du boutique
            $query=$em->createQuery(
                'SELECT p
    FROM BoutiqueBundle:Produit_Boutique p 
    WHERE p.boutique = :usid')
                ->setParameter('usid', $usid );
            $query->setFirstResult(0);
            $query->setMaxResults(5);
            $data = $query->getResult();


            return $this->render('@Boutique/Default/backend.html.twig',[
                'username' => $username,
                'boutique' => $us,
                'rs' => $rsbd,
                'num' => $numbd,
                'theme'=>$theme,
                'data'=>$data
            ]);

        }



        return $this->render('@Boutique/bkndPages/ajout_profile.html.twig',array(

                'username' => $username,
                'boutique' => $us,
                'form'=>$form->createView(),

            )
        );
    }





}
