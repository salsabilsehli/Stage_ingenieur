<?php

namespace BoutiqueBundle\Controller;

use BoutiqueBundle\Entity\Boutique;
use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Categorie_Boutique;
use BoutiqueBundle\Entity\Filtre;
use BoutiqueBundle\Entity\Produit;
use BoutiqueBundle\Entity\Produit_Boutique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //na9ra les noms de boutiques mel bd

        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT u FROM UserBundle:User u WHERE u.roles LIKE :role'
            )->setParameter('role', '%"ROLE_AGENT"%' );

        $boutiques = $query->getResult();
        return $this->render('@Boutique/Default/index.html.twig',array(
            'boutiques' => $boutiques,
        )); //nab3eth les noms des boutiques
    }


    public function adminAction($username)
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
        foreach ($user as $u) {
            $rsbd= $u->getRaisonsociale();
        }
        foreach ($user as $u) {
            $numbd=$u->getNum();
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
            'data'=>$data
        ]);
    }

    public function frontBoutiqueAction(Request $request,$username)
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



        //produits du boutique
        $queryids = $em->createQuery(
            'SELECT IDENTITY(p.produit)
    FROM BoutiqueBundle:Produit_Boutique p
    WHERE p.boutique = :usid'
        )->setParameter('usid', $usid);
        $prdsids = $queryids->getResult();


        //produits

        $query = $em->createQuery(
            'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) '
        )
            ->setParameter('prdsid', $prdsids);


        $prds = $query->getResult();

        //products pagination
        $prdspaginate = $this->get('knp_paginator')->paginate($prds, $request->query->get('page', 1), 9);
        //categories du boutique
        $categoriesduboutique = $em->getRepository(Categorie_Boutique::class)->findBy(['boutique' => $usid]);

        foreach ($user as $u) {
            $num = $u->getNum();
        }
        foreach ($user as $u) {
            $email = $u->getEmail();
        }
        foreach ($user as $u) {
            $theme = $u->getTheme();
        }


        //filtre form
        $filtre = new Filtre();
        $form = $this->createForm('BoutiqueBundle\Form\FiltreType', $filtre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $categorie = $filtre->getCategorie();
            $idc = $categorie->getId();
            //0 non
            $livraison = $filtre->isLivgrat();
            //prix min et max

            $prixmin = $request->request->get('prixmin');
            $prixmax = $request->request->get('prixmax');

            if ($filtre->isLivgrat() == 'rien') {
                if ($prixmin == null && $prixmax == null) {

                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);
                } elseif ($prixmax == null && $prixmin != null) {
                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix >= :prixmin'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('prixmin', $prixmin);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);

                } elseif ($prixmin == null && $prixmax != null) {
                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix <= :prixmax'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('prixmax', $prixmax);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);
                } elseif ($prixmin != null && $prixmax != null) {
                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
                         FROM BoutiqueBundle:Produit p
                         WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix <= :prixmax AND p.prix >= :prixmin'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('prixmax', $prixmax)
                        ->setParameter('prixmin', $prixmin);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);
                }


            }
            elseif ($filtre->isLivgrat() != 'rien') {
                if ($prixmin == null && $prixmax == null) {

                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('liv', $livraison);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);
                } elseif ($prixmax == null && $prixmin != null) {
                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix >= :prixmin AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('prixmin', $prixmin)
                        ->setParameter('liv', $livraison);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);

                } elseif ($prixmin == null && $prixmax != null) {
                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid AND p.prix <= :prixmax AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('prixmax', $prixmax)
                        ->setParameter('liv', $livraison);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);
                } elseif ($prixmin != null && $prixmax != null) {
                    //produits filtrés

                    $query = $em->createQuery(
                        'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid p.prix <= :prixmax AND p.prix >= :prixmin AND p.livgrat = :liv'
                    )
                        ->setParameter('prdsid', $prdsids)
                        ->setParameter('catid', $idc)
                        ->setParameter('prixmax', $prixmax)
                        ->setParameter('prixmin', $prixmin)
                        ->setParameter('liv', $livraison);


                    $prodsfiltres = $query->getResult();
                    $prdspaginatefiltre = $this->get('knp_paginator')->paginate($prodsfiltres, $request->query->get('page', 1), 9);


                    return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig', [
                        'username' => $username,
                        'boutique' => $us,
                        'num' => $num,
                        'email' => $email,
                        'produits' => $prdspaginatefiltre,
                        'ttprds' => $prds,
                        'produitss' => $prodsfiltres,
                        'categories' => $categoriesduboutique,
                        'form' => $form->createView(),
                        'theme' => $theme


                    ]);
                }

            }
        }
                return $this->render('@Boutique/Default/frontBoutique.html.twig', [

                    'boutique' => $us,
                    'num' => $num,
                    'email' => $email,
                    'produits' => $prdspaginate,
                    'produitss' => $prds,
                    'categories' => $categoriesduboutique,
                    'form' => $form->createView(),
                    'theme' => $theme,

                ]);

        }

//filtre
    public function frontBoutiquefiltreAction(Request $request,$username)
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


        //produits du boutique
        $queryids=$em->createQuery(
            'SELECT IDENTITY(p.produit)
    FROM BoutiqueBundle:Produit_Boutique p
    WHERE p.boutique = :usid'
        )->setParameter('usid', $usid);
        $prdsids=$queryids->getResult();



        //produits filtrés

        $query=$em->createQuery(
            'SELECT p
    FROM BoutiqueBundle:Produit p
    WHERE p.id IN (:prdsid) AND p.categorie = :catid p.livgrat = :liv'
    )
            ->setParameter('prdsid',$prdsids)

        ;



        $prods = $query->getResult();


        //products pagination
        $prdspaginate=$this->get('knp_paginator')->paginate($prods,$request->query->get('page',1),9);

        //categories du boutique
        $categoriesduboutique=$em->getRepository(Categorie_Boutique::class)->findBy(['boutique' => $usid]);

        foreach ($user as $u) {
            $num = $u->getNum();
        }
        foreach ($user as $u) {
            $email = $u->getEmail();
        }
        foreach ($user as $u) {
            $theme = $u->getTheme();
        }
        return $this->render('@Boutique/Default/frontBoutiqueCat.html.twig',[
            'username' => $username,
            'boutique' => $us,
            'num' => $num,
            'email' => $email,
            'produits' => $prdspaginate,
            'produitss' => $prods,
            'categories' => $categoriesduboutique,
            'theme' => $theme,


        ]);
    }





}
